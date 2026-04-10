<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KolatController;
use App\Http\Controllers\PelatihController;
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

//anggota
Route::get('/anggota', [AnggotaController::class, 'anggota'])->name('anggota.anggota');
Route::get('/anggota/tambah', [AnggotaController::class, 'create'])->name('anggota.create');
Route::post('/anggota/simpan', [AnggotaController::class, 'store'])->name('anggota.store');
Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');

//pelatih
Route::get('/pelatih', [PelatihController::class, 'pelatih'])->name('pelatih.pelatih');

//kolat
    Route::get('/kolat', [KolatController::class, 'kolat'])->name('kolat.index');
    Route::get('/kolat/tambah', [KolatController::class, 'create'])->name('kolat.create');
    Route::post('/kolat/simpan', [KolatController::class, 'store'])->name('kolat.store');
    Route::get('/kolat/{id}/edit', [KolatController::class, 'edit'])->name('kolat.edit');
    Route::put('/kolat/{id}', [KolatController::class, 'update'])->name('kolat.update');
    Route::delete('/kolat/{id}', [KolatController::class, 'destroy'])->name('kolat.destroy');

