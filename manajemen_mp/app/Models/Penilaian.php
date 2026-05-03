<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;
   protected $fillable = [
    'pelatih_id',
    'anggota_id',
    'bulan_evaluasi',
    'metode_pelatihan',
    'komunikasi',
    'sikap_kepribadian',
    'kepemimpinan',
    'konsistensi_komitmen',
    'kedekatan_interpersonal',
    'kritik_saran'
];

    // Relasi: Siapa pelatih yang sedang dinilai?
    public function pelatih()
    {
        return $this->belongsTo(Anggota::class, 'pelatih_id');
    }

    // Relasi: Siapa anggota yang memberikan nilai (penilai)?
    public function penilai()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function show($id)
{
    $pelatih = Anggota::with('kolat')->findOrFail($id);

    // Hitung rata-rata tiap kriteria bulan ini
    $rekap = Penilaian::where('pelatih_id', $id)
        ->whereMonth('bulan_evaluasi', now()->month)
        ->selectRaw('
            AVG(metode_pelatihan) as avg_metode,
            AVG(komunikasi) as avg_komunikasi,
            AVG(sikap_kepribadian) as avg_sikap,
            AVG(kepemimpinan) as avg_kepemimpinan,
            AVG(konsistensi_komitmen) as avg_komitmen,
            AVG(kedekatan_interpersonal) as avg_interpersonal
        ')->first();

    // Ambil ulasan/kritik saran (Pluck agar anonim)
    $ulasan = Penilaian::where('pelatih_id', $id)
        ->whereMonth('bulan_evaluasi', now()->month)
        ->whereNotNull('kritik_saran')
        ->pluck('kritik_saran');

    return view('penilaian.rekap', compact('pelatih', 'rekap', 'ulasan'));
}
}
