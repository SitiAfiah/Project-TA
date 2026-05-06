<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KolatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelatihController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SppController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =======================================================
// RUTE PUBLIK (TIDAK PERLU LOGIN)
// =======================================================
Route::get('/', function () { return view('index'); });
Route::get('/index', function () { return view('layout.app'); });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// =======================================================
// RUTE GLOBAL (SEMUA ROLE BISA AKSES SETELAH LOGIN)
// =======================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =======================================================
    // 1. BLOK KHUSUS ANGGOTA
    // =======================================================
    Route::middleware('checkrole:Anggota')->group(function () {
        // SPP Anggota (Upload Bukti)
        Route::prefix('anggota/spp')->name('spp.anggota.')->group(function () {
            Route::get('/', [SppController::class, 'indexAnggota'])->name('index');
            Route::get('/bayar/{id}', [SppController::class, 'formBayarAnggota'])->name('formBayar');
            Route::post('/bayar/{id}', [SppController::class, 'prosesBayarAnggota'])->name('prosesBayar');
        });

        // Presensi Mandiri
        Route::prefix('presensi/anggota')->name('presensi.anggota.')->group(function () {
            Route::get('/', [PresensiController::class, 'indexAnggota'])->name('index');
            Route::get('/riwayat', [PresensiController::class, 'riwayatAnggota'])->name('riwayat');
            Route::get('/scan/{jadwal_id}', [PresensiController::class, 'scan'])->name('scan');
            Route::post('/store-mandiri', [PresensiController::class, 'storeMandiri'])->name('storeMandiri');
        });

        // Penilaian (Anggota mengisi nilai)
        Route::get('/penilaian/create/{id}', [PenilaianController::class, 'create'])->name('penilaian.create');
        Route::post('/penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');
    });

    // =======================================================
    // 2. BLOK KHUSUS PENGURUS & PELATIH (Bisa Akses Bersama)
    // =======================================================
    Route::middleware('checkrole:Pengurus,Pelatih')->group(function () {
        // Lihat Master Data Dasar
        Route::get('/anggota', [AnggotaController::class, 'anggota'])->name('anggota.anggota');
        Route::get('/anggota/tambah', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('/anggota/simpan', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::get('/kolat', [KolatController::class, 'kolat'])->name('kolat.index');
        Route::get('/manajemen-pelatih', [PelatihController::class, 'index'])->name('pelatih.index');
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/{id}/detail', [JadwalController::class, 'show'])->name('jadwal.show');

        // Presensi (Verifikasi & Manual)
        Route::prefix('presensi')->name('presensi.')->group(function () {
            Route::get('/', [PresensiController::class, 'index'])->name('index');
            Route::get('/kehadiran/{jadwal_id}', [PresensiController::class, 'kehadiran'])->name('kehadiran');
            Route::post('/konfirmasi/{presensi_id}', [PresensiController::class, 'konfirmasi'])->name('konfirmasi');
            Route::post('/tambah-manual', [PresensiController::class, 'storeManual'])->name('storeManual');
        });

         Route::post('/jadwal/{id}/konfirmasi', [JadwalController::class, 'konfirmasiSelesai'])->name('jadwal.konfirmasi');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{id}/update', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}/delete', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

        // Lihat SPP & Penilaian
        Route::get('/spp', [SppController::class, 'index'])->name('spp.index');
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::get('/penilaian/rekap/{id}', [PenilaianController::class, 'show'])->name('penilaian.show');

        // Laporan (Hanya Presensi)
        Route::get('/laporan/presensi', [LaporanController::class, 'presensi'])->name('laporan.presensi');
        Route::get('/laporan/presensi/excel', [LaporanController::class, 'presensiExcel'])->name('laporan.presensi.excel');
        Route::get('/laporan/presensi/pdf', [LaporanController::class, 'presensiPdf'])->name('laporan.presensi.pdf');

    // Menu Rekap Kelayakan (Ditambahkan di sini)
    Route::get('/rekap/kelayakan', [RekapController::class, 'index'])->name('rekap.index');

    });

    // =======================================================
    // 3. BLOK KHUSUS PENGURUS SAJA (Akses Tertinggi)
    // =======================================================
    Route::middleware('checkrole:Pengurus')->group(function () {
        // Aksi CRUD Anggota

        Route::get('/anggota/export/excel ', [AnggotaController::class, 'exportExcel'])->name('anggota.export.excel');
        Route::get('/anggota/export/pdf', [AnggotaController::class, 'exportPdf'])->name('anggota.export.pdf');

        // Aksi CRUD Kolat
        Route::get('/kolat/tambah', [KolatController::class, 'create'])->name('kolat.create');
        Route::post('/kolat/simpan', [KolatController::class, 'store'])->name('kolat.store');
        Route::get('/kolat/{id}/edit', [KolatController::class, 'edit'])->name('kolat.edit');
        Route::put('/kolat/{id}', [KolatController::class, 'update'])->name('kolat.update');
        Route::delete('/kolat/{id}', [KolatController::class, 'destroy'])->name('kolat.destroy');

        // Aksi CRUD Pelatih
        Route::get('/manajemen-pelatih/upgrade', [PelatihController::class, 'create'])->name('pelatih.upgrade');
        Route::post('/manajemen-pelatih/upgrade', [PelatihController::class, 'store'])->name('pelatih.store');
        Route::get('/manajemen-pelatih/{id}/edit', [PelatihController::class, 'edit'])->name('pelatih.edit');
        Route::put('/manajemen-pelatih/{id}', [PelatihController::class, 'update'])->name('pelatih.update');

        // Aksi CRUD Jadwal


        // Aksi Transaksi SPP
        Route::prefix('spp')->name('spp.')->group(function () {
            Route::get('/create', [SppController::class, 'create'])->name('create');
            Route::post('/store', [SppController::class, 'store'])->name('store');
            Route::post('/bayar/{id}', [SppController::class, 'bayar'])->name('bayar');
            Route::post('/generate', [SppController::class, 'generateTagihan'])->name('generate');
            Route::get('/export/excel', [SppController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [SppController::class, 'exportPdf'])->name('export.pdf');
        });

        // Kas Cabang (Terkunci Mutlak)
        Route::prefix('kas')->name('kas.')->group(function () {
            Route::get('/', [KasController::class, 'index'])->name('index');
            Route::post('/store', [KasController::class, 'store'])->name('store');
            Route::put('/update/{id}', [KasController::class, 'update'])->name('update');
            Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
            Route::get('/export/excel', [KasController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [KasController::class, 'exportPdf'])->name('export.pdf');
        });

        // Sisa Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/anggota', [LaporanController::class, 'anggota'])->name('anggota');
            Route::get('/kas', [LaporanController::class, 'kas'])->name('kas');
            Route::get('/spp', [LaporanController::class, 'spp'])->name('spp');
        });



    });

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});



