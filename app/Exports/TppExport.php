<?php

namespace App\Exports;

use App\RekapTpp;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TppExport implements WithHeadings, WithEvents
{
    private $year;
    private $month;

    public function __construct(string $month, string $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN TPP ASN'],
            [strtoupper(Auth::user()->skpd->nama)],
            ['BULAN : ', $this->month . ' ' . $this->year],
            ['TGL CETAK : ', Carbon::now()->format('d-m-Y H:i:s')],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // $event->sheet->getStyle('A1:A5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setRGB('dbe715');

                $event->sheet->getStyle('A1:A5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK)
                    ->getStartColor()->setRGB('dbe715');
            }
        ];
    }
}
