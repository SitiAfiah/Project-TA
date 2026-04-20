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
        'kedisiplinan',
        'kejelasan_materi',
        'penguasaan_teknik',
        'sikap_perilaku',
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
}
