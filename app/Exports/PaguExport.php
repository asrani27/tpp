<?php

namespace App\Exports;

use App\RekapReguler;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaguExport implements FromCollection, WithHeadings, WithColumnFormatting, WithColumnWidths, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $bulan;
    private $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = RekapReguler::leftJoin('skpd', 'rekap_reguler.skpd_id', '=', 'skpd.id')
            ->where('rekap_reguler.bulan', $this->bulan)
            ->where('rekap_reguler.tahun', $this->tahun)
            ->select('rekap_reguler.nip', 'rekap_reguler.nama', 'rekap_reguler.jabatan', 'rekap_reguler.kelas', 'rekap_reguler.pagu', 'rekap_reguler.skpd_id', 'skpd.nama as nama_skpd')
            ->orderBy('skpd.nama', 'asc')
            ->orderBy('rekap_reguler.kelas', 'desc')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'nomor' => $key + 1,
                    'nip' => "'" . $item->nip,
                    'nama' => $item->nama,
                    'jabatan' => $item->jabatan,
                    'kelas' => $item->kelas,
                    'pagu' => $item->pagu,
                    'skpd' => $item->nama_skpd ?? ''
                ];
            });
        return $data;
    }

    public function headings(): array
    {
        return [
            'Nomor',
            'NIP',
            'Nama',
            'Jabatan',
            'Kelas',
            'Pagu',
            'SKPD'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'D' => 200, // Jabatan column width
        ];
    }
}
