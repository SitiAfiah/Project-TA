<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    // Kita terima data dari Controller lewat constructor
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
            'Tanggal',
            'Jenis',
            'Kategori',
            'Nominal',
            'Keterangan',
            'Saldo Akhir'
        ];
    }

    public function map($kas): array
    {
        return [
            \Carbon\Carbon::parse($kas->tanggal)->format('d/m/Y'),
            ucfirst($kas->jenis),
            $kas->kategori,
            $kas->nominal,
            $kas->keterangan ?? '-',
            $kas->saldo_akhir
        ];
    }
}
