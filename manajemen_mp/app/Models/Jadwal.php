<?php

namespace App\Models;

use App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $fillable = [
        'kolat_id',
        'pelatih_id',
        'judul_kegiatan',
        'jenis',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'deskripsi'
    ];

    // Relasi: Jadwal ini milik Kolat mana?
    public function kolat()
    {
        return $this->belongsTo(Kolat::class, 'kolat_id');
    }

    // Relasi: Siapa pelatih yang bertugas di jadwal ini?
    public function pelatih()
    {
        return $this->belongsTo(Anggota::class, 'pelatih_id');
    }

    // Relasi: Jadwal ini punya banyak data presensi anggota
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'jadwal_id');
    }
}
