<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelatih extends Model
{

protected $table = 'anggota';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'anggota_id',
        'no_sk',
        'tgl_sk',
        'masa_berlaku',
        'foto_sk',
        'status_pelatih',
        'catatan'
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    // public function kolat(): BelongsTo
    // {
    //     return $this->belongsTo(Kolat::class, 'kolat_id');
    // }
}
