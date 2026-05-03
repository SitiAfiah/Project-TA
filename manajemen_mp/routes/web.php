<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KolatController;
use App\Http\Controllers\PelatihController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PresensiController;
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

Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('layout.app');
});

Route::get('/p', function () {
    return view('user.user');
});

Route::get('/anggota', function () {
    return view('anggota.anggota');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']); // Sesuaikan nama method

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']); // Sesuaikan nama method
});

// Route untuk user yang SUDAH login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route dashboard atau menu lainnya masukkan di sini
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

//anggota
Route::get('/anggota', [AnggotaController::class, 'anggota'])->name('anggota.anggota');
Route::get('/anggota/tambah', [AnggotaController::class, 'create'])->name('anggota.create');
Route::post('/anggota/simpan', [AnggotaController::class, 'store'])->name('anggota.store');
Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');


//kolat
    Route::get('/kolat', [KolatController::class, 'kolat'])->name('kolat.index');
    Route::get('/kolat/tambah', [KolatController::class, 'create'])->name('kolat.create');
    Route::post('/kolat/simpan', [KolatController::class, 'store'])->name('kolat.store');
    Route::get('/kolat/{id}/edit', [KolatController::class, 'edit'])->name('kolat.edit');
    Route::put('/kolat/{id}', [KolatController::class, 'update'])->name('kolat.update');
    Route::delete('/kolat/{id}', [KolatController::class, 'destroy'])->name('kolat.destroy');

    //pelatih
    //prefix diguakan untuk mengelompokkan route yang berhubungan dengan pelatih, sehingga URL nya menjadi /manajemen-pelatih/...
    Route::prefix('manajemen-pelatih')->group(function () {
    Route::get('/', [PelatihController::class, 'index'])->name('pelatih.index');
    Route::get('/upgrade', [PelatihController::class, 'create'])->name('pelatih.upgrade');
    Route::post('/upgrade', [PelatihController::class, 'store'])->name('pelatih.store');
    Route::get('/{id}/edit', [PelatihController::class, 'edit'])->name('pelatih.edit');
    Route::put('/{id}', [PelatihController::class, 'update'])->name('pelatih.update');
    });

    //jadwal
    //prefix diguakan untuk mengelompokkan route yang berhubungan dengan jadwal
    Route::prefix('jadwal')->group(function () {
    Route::get('/', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal/{id}/konfirmasi', [JadwalController::class, 'konfirmasiSelesai'])->name('jadwal.konfirmasi');
    Route::get('/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::get('/{id}/detail', [JadwalController::class, 'show'])->name('jadwal.show');
    Route::post('/store', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/{id}/update', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/{id}/delete', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    });

    // --- ROUTE PRESENSI (ABSENSI) ---
Route::prefix('presensi')->name('presensi.')->group(function () {
    Route::get('/', [PresensiController::class, 'index'])->name('index');
    // Halaman daftar kehadiran (View Admin/Pelatih)
    Route::get('/kehadiran/{jadwal_id}', [PresensiController::class, 'kehadiran'])->name('kehadiran');
    // konfirmasi pelatih
    Route::post('/konfirmasi/{presensi_id}', [PresensiController::class, 'konfirmasi'])->name('konfirmasi');
    // Proses tambah presensi manual (Kasus Lupa HP)
    Route::post('/tambah-manual', [PresensiController::class, 'storeManual'])->name('storeManual');
});

//Penialaian

    Route::prefix('penilaian')->name('penilaian.')->group(function () {
    Route::get('/', [PenilaianController::class, 'index'])->name('index');
    // Hilangkan kata 'penilaian' di dalam sini karena sudah ada di prefix
    Route::get('/create/{id}', [PenilaianController::class, 'create'])->name('create');
    Route::post('/store', [PenilaianController::class, 'store'])->name('store');
    Route::get('/penilaian/rekap/{id}', [PenilaianController::class, 'show'])->name('show');
});

//Kas
Route::prefix('kas')->name('kas.')->group(function () {
    Route::get('/', [KasController::class, 'index'])->name('index');
    Route::post('/store', [KasController::class, 'store'])->name('store');
    Route::put('/update/{id}', [KasController::class, 'update'])->name('update');
    Route::delete('/{id}', [KasController::class, 'destroy'])->name('destroy');
});

// Group SPP
Route::prefix('spp')->name('spp.')->group(function () {
    Route::get('/', [SppController::class, 'index'])->name('index');
    Route::get('/create', [SppController::class, 'create'])->name('create');
    Route::post('/store', [SppController::class, 'store'])->name('store');
    Route::post('/bayar/{id}', [SppController::class, 'bayar'])->name('bayar');
    Route::post('/generate', [SppController::class, 'generateTagihan'])->name('generate');
});

