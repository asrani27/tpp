<?php

namespace App\Exports;

use App\RekapReguler;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;

class KonsolidasiTPP implements FromView, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $tahun;
    private $bulan;

    public function __construct(string $bulan, string $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {

        return view('exports.konsolidasitpp', [
            'reguler' => RekapReguler::where('bulan', $this->bulan)->where('tahun', $this->tahun)->orderBy('skpd_id', 'ASC')->get()
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
            },
        ];
    }
}
