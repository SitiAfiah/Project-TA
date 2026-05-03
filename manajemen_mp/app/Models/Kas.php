<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'tanggal',
        'jenis',
        'kategori',
        'nominal',
        'keterangan',
        'saldo_akhir'
    ];

    /**
     * Scope untuk mendapatkan saldo terakhir.
     * Ini akan memudahkan kamu saat ingin menghitung saldo baru.
     */
    public static function saldoTerakhir()
    {
        $last = self::orderBy('id', 'desc')->first();
        return $last ? $last->saldo_akhir : 0;
    }
}
