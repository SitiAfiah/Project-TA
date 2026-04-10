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
        'no_induk',
        'nama_lengkap',
        'role_id',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'no_hp',
        'kolat_id',
        'tingkatan',
        'tgl_gabung',
        'status',
        'alamat',
        'catatan_medis'
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
}
