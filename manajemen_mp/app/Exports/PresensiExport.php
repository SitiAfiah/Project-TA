<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PresensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'Kolat',
            'Tanggal Latihan',
            'Jam',
            'Pelatih (Jadwal)',
            'Status Kehadiran',
            'Waktu Absen',
            'Verifikator',
            'Keterangan'
        ];
    }

    public function map($p): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $p->anggota->nama_lengkap ?? '-',
            $p->jadwal->kolat->nama_kolat ?? '-',
            \Carbon\Carbon::parse($p->jadwal->tanggal)->format('d/m/Y'),
            ($p->jadwal->jam_mulai ?? '-') . ' - ' . ($p->jadwal->jam_selesai ?? '-'),
            $p->jadwal->pelatih->nama_lengkap ?? '-',
            $p->status,
            $p->waktu_presensi ? \Carbon\Carbon::parse($p->waktu_presensi)->format('H:i') . ' WIB' : '-',
            $p->pelatihVerifikator->nama_lengkap ?? 'Otomatis/Sistem',
            $p->keterangan ?? '-'
        ];
    }
}
