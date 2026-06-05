<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
    'user_id', 'no_induk', 'nama_lengkap', 'foto_profil', 'role_id', 'jenis_kelamin',
    'tempat_lahir', 'tgl_lahir', 'no_hp', 'kolat_id',
    'tingkatan', 'tgl_gabung', 'status', 'alamat',
    'catatan_medis', 'jabatan', 'no_sk', 'masa_berlaku', 'foto_sk'
];

    public function kolat()
    {
        // Pastikan file Kolat.php juga ada di folder Models
        return $this->belongsTo(Kolat::class, 'kolat_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function kolatLatihan()
    {
        return $this->belongsToMany(Kolat::class, 'kolat_pelatih', 'anggota_id', 'kolat_id');
    }

    // Scope agar gampang memanggil data yang hanya ber-role Pelatih
    public function scopeIsPelatih($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('nama_role', 'Pelatih');
        });
    }

    // Relasi: Mendapatkan riwayat kehadiran anggota
public function riwayatPresensi()
{
    return $this->hasMany(Presensi::class, 'anggota_id');
}

// Relasi: Jika dia pelatih, mendapatkan semua jadwal tugasnya
public function jadwalMelatih()
{
    return $this->hasMany(Jadwal::class, 'pelatih_id');
}

// Relasi: Jika dia pelatih, mendapatkan semua evaluasi dari murid
public function evaluasiMasuk()
{
    return $this->hasMany(Penilaian::class, 'pelatih_id');
}

// Anggota.php
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function presensi() {
    return $this->hasMany(Presensi::class);
}

public function spp() {
    return $this->hasMany(Spp::class);
}

}
