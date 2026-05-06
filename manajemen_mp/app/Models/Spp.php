<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    protected $table = 'spp';
    protected $fillable = [
        'anggota_id',
        'kolat_id',
        'kas_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'jatuh_tempo',
        'keterangan',
        'bukti_pembayaran'
    ];

    // Relasi ke Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // Relasi ke Kolat
    public function kolat()
    {
        return $this->belongsTo(Kolat::class);
    }

    // Relasi ke Kas
    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }
}
