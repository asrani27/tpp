<?php

namespace App\Exports;

use App\Jabatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;

class ParameterTPPpuskesmas implements FromView, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('exports.parametertpppuskesmas', [
            'jabatan' => Jabatan::where('rs_puskesmas_id', '!=', null)->get()
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Atur lebar kolom C ke 200px (sekitar 25 dalam Excel width)
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(55);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(25);
                $sheet->getColumnDimension('H')->setWidth(25);
                $sheet->getColumnDimension('I')->setWidth(25);
                $sheet->getColumnDimension('J')->setWidth(25);
                $sheet->getColumnDimension('K')->setWidth(25);
                $sheet->getColumnDimension('L')->setWidth(55);
                $sheet->getColumnDimension('M')->setWidth(55);
                $sheet->getColumnDimension('N')->setWidth(55);
            },
        ];
    }
}
