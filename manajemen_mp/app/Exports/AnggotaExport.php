<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnggotaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Ambil data Anggota dengan relasi kolat dan user (untuk email)
        return Anggota::whereHas('role', function($query) {
            $query->where('nama_role', 'Anggota');
        })->with(['kolat', 'user'])->get();
    }

    public function headings(): array
    {
        return [
            'No Induk',
            'Nama Lengkap',
            'Email',
            'JK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No HP',
            'Kolat',
            'Tingkatan',
            'Jabatan',
            'Tanggal Gabung',
            'Status',
            'Alamat',
            'Catatan Medis'
        ];
    }

    public function map($anggota): array
    {
        return [
            $anggota->no_induk,
            $anggota->nama_lengkap,
            $anggota->user->email ?? '-',
            $anggota->jenis_kelamin,
            $anggota->tempat_lahir,
            $anggota->tgl_lahir,
            $anggota->no_hp,
            $anggota->kolat->nama_kolat ?? '-',
            $anggota->tingkatan,
            $anggota->jabatan,
            $anggota->tgl_gabung,
            $anggota->status,
            $anggota->alamat,
            $anggota->catatan_medis ?? '-'
        ];
    }
}
