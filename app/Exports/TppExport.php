<?php

namespace App\Exports;

use App\RekapTpp;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TppExport implements WithHeadings, WithEvents
{
    // public function view(): View
    // {
    //     return view('admin.rekapitulasi.bulanexcel', [
    //         'data' => RekapTpp::all()
    //     ]);
    // }

    public function headings(): array
    {
        return [
            ['coba'],
            ['nama, email']
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
