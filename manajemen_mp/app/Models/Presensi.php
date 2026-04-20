<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_id',
        'anggota_id',
        'status',
        'keterangan',
        'waktu_presensi'
    ];

    // Relasi: Presensi ini untuk jadwal yang mana?
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    // Relasi: Siapa anggota yang melakukan presensi?
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
