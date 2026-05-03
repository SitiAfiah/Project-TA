<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan scope query untuk filter
        $query = Kas::query();

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

      $data_kas = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        // Statistik tetap menggunakan data keseluruhan
        $saldo_sekarang = Kas::saldoTerakhir();

        $pemasukan_bulan_ini = Kas::where('jenis', 'masuk')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        $pengeluaran_bulan_ini = Kas::where('jenis', 'keluar')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        return view('kas.index', compact('data_kas', 'saldo_sekarang', 'pemasukan_bulan_ini', 'pengeluaran_bulan_ini'));
    }

    /**
     * Fungsi untuk menyimpan data transaksi baru
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal'  => 'required|date',
            'jenis'    => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'nominal'  => 'required|numeric|min:0',
        ]);

        // 2. Ambil saldo terakhir untuk kalkulasi saldo_akhir
        $saldo_terakhir = Kas::saldoTerakhir();

        // 3. Hitung saldo baru
        if ($request->jenis == 'masuk') {
            $saldo_baru = $saldo_terakhir + $request->nominal;
        } else {
            $saldo_baru = $saldo_terakhir - $request->nominal;
        }

        // 4. Simpan ke database
        Kas::create([
            'tanggal'     => $request->tanggal,
            'jenis'       => $request->jenis,
            'kategori'    => $request->kategori,
            'nominal'     => $request->nominal,
            'keterangan'  => $request->keterangan,
            'saldo_akhir' => $saldo_baru,
        ]);

        return redirect()->route('kas.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $kas = Kas::findOrFail($id);
        $kas->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'jenis' => 'required|in:masuk,keluar',
        'kategori' => 'required|string',
        'nominal' => 'required|numeric',
    ]);

    $kas = Kas::findOrFail($id);

    // Logika perhitungan ulang saldo:
    // Kita ambil saldo sebelum transaksi ini terjadi
    $saldo_sebelumnya = Kas::where('id', '<', $kas->id)->orderBy('id', 'desc')->first()->saldo_akhir ?? 0;

    // Hitung saldo akhir baru untuk baris ini
    $saldo_akhir_baru = ($request->jenis == 'masuk')
        ? $saldo_sebelumnya + $request->nominal
        : $saldo_sebelumnya - $request->nominal;

    $kas->update([
        'tanggal' => $request->tanggal,
        'jenis' => $request->jenis,
        'kategori' => $request->kategori,
        'nominal' => $request->nominal,
        'keterangan' => $request->keterangan,
        'saldo_akhir' => $saldo_akhir_baru
    ]);

    // Catatan: Idealnya setelah edit, semua baris setelahnya juga diupdate saldonya.
    // Tapi untuk tahap awal, ini sudah cukup fungsional.

    return redirect()->back()->with('success', 'Transaksi berhasil diperbarui!');
}
}
