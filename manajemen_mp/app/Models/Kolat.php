<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kolat extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak otomatis jamak
    protected $table = 'kolat';

    // Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_kolat',
        'alamat_kolat',
    ];

    // Relasi: Satu Kolat memiliki banyak Anggota
    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'kolat_id');
    }

    public function pelatihs()
    {
        // Panggil relasi pivot, lalu sambungkan dengan scope isPelatih()
        return $this->belongsToMany(Anggota::class, 'kolat_pelatih', 'kolat_id', 'anggota_id')
                    ->isPelatih();
    }
}
