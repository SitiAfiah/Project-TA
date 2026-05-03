<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SppExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            'Nama Anggota',
            'Kolat',
            'Bulan',
            'Tahun',
            'Nominal (Rp)',
            'Jatuh Tempo',
            'Status',
            'Keterangan'
        ];
    }

    public function map($spp): array
    {
        return [
            $spp->anggota->nama_lengkap ?? '-', // Menggunakan nama_lengkap sesuai model Anggota
            $spp->kolat->nama_kolat ?? '-',
            $spp->bulan,
            $spp->tahun,
            $spp->nominal,
            \Carbon\Carbon::parse($spp->jatuh_tempo)->format('d/m/Y'),
            strtoupper(str_replace('_', ' ', $spp->status)), // Misal: "belum_bayar" jadi "BELUM BAYAR"
            $spp->keterangan ?? '-'
        ];
    }
}
