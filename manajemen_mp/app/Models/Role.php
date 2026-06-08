<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role'; // Pastikan nama tabelnya 'roles' di database
    protected $fillable = ['nama_role']; // Sesuaikan kolom di tabel roles kamu

    public function anggotas()
    {
        return $this->belongsToMany(Anggota::class, 'anggota_role', 'role_id', 'anggota_id')->withTimestamps();
    }
}
