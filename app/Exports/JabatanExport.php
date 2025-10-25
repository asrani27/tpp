<?php

namespace App\Exports;

use App\Jabatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class JabatanExport implements FromCollection, WithHeadings, WithEvents
{
    private $mergeRanges = [];
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $jabatanData = Jabatan::where('jabatan.skpd_id', 28)
            ->leftJoin('pegawai', 'jabatan.id', '=', 'pegawai.jabatan_id')
            ->leftJoin('skp2023', function ($join) {
                $join->on('pegawai.id', '=', 'skp2023.pegawai_id')
                    ->whereRaw('skp2023.id = (SELECT MAX(s2.id) FROM skp2023 s2 WHERE s2.pegawai_id = pegawai.id AND YEAR(s2.sampai) = 2025)')
                    ->whereYear('skp2023.sampai', 2024);
            })
            ->select('jabatan.nama as nama_jabatan', 'pegawai.nip', 'pegawai.nama', 'skp2023.jenis', 'skp2023.id as skp_id', 'pegawai.id as pegawai_id')
            ->get();

        $result = collect();
        $pegawaiCounter = 1; // Counter for pegawai numbering
        $currentRow = 1; // Counter for actual row positioning
        $this->mergeRanges = []; // Store merge ranges for later processing

        foreach ($jabatanData as $item) {
            // Get all RHK based on jenis
            $rhkList = $this->getAllRHK($item->jenis, $item->skp_id);

            if (empty($rhkList)) {
                // If no RHK found, still add the row with empty RHK
                $result->push([
                    'nomor' => $pegawaiCounter,
                    'nip' => $item->nip ? '`' . $item->nip : '',
                    'nama' => $item->nama ?? '',
                    'nama_jabatan' => $item->nama_jabatan ?? '',
                    'jenis' => $item->jenis ?? '',
                    'rhk' => ''
                ]);
                $pegawaiCounter++;
                $currentRow++;
            } else {
                $startRow = $currentRow;
                $rhkCount = count($rhkList);

                // Add multiple rows for each RHK
                foreach ($rhkList as $index => $rhk) {
                    $result->push([
                        'nomor' => $pegawaiCounter, // Always show the same pegawai number
                        'nip' => $item->nip ? '`' . $item->nip : '',
                        'nama' => $item->nama ?? '',
                        'nama_jabatan' => $item->nama_jabatan ?? '',
                        'jenis' => $item->jenis ?? '',
                        'rhk' => $rhk
                    ]);
                    $currentRow++;
                }

                // Store merge range if multiple RHK
                if ($rhkCount > 1) {
                    $endRow = $startRow + $rhkCount - 1;
                    $this->mergeRanges[] = [
                        'start' => $startRow,
                        'end' => $endRow
                    ];
                }

                $pegawaiCounter++; // Increment pegawai counter by 1, not by RHK count
            }
        }

        return $result;
    }

    /**
     * Get all RHK based on jenis
     */
    private function getAllRHK($jenis, $skpId)
    {
        if (!$skpId || !$jenis) {
            return [];
        }

        try {
            if ($jenis === 'JF' || $jenis === 'JA') {
                // Get all from Skp2023Jf
                $jfList = \App\Skp2023Jf::where('skp2023_id', $skpId)->get();
                return $jfList->pluck('rhk')->filter()->toArray();
            } elseif ($jenis === 'JPT') {
                // Get all from Skp2023Jpt
                $jptList = \App\Skp2023Jpt::where('skp2023_id', $skpId)->get();
                return $jptList->pluck('rhk')->filter()->toArray();
            }
        } catch (\Exception $e) {
            return [];
        }

        return [];
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama',
            'Nama Jabatan',
            'Jenis',
            'RHK'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get the highest row and column
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Apply merge cells for columns A-E based on RHK count
                foreach ($this->mergeRanges as $range) {
                    $startRow = $range['start'] + 1; // +1 because of header row
                    $endRow = $range['end'] + 1; // +1 because of header row

                    // Merge columns A (No), B (NIP), C (Nama), D (Nama Jabatan), E (Jenis)
                    $sheet->mergeCells("A{$startRow}:A{$endRow}");
                    $sheet->mergeCells("B{$startRow}:B{$endRow}");
                    $sheet->mergeCells("C{$startRow}:C{$endRow}");
                    $sheet->mergeCells("D{$startRow}:D{$endRow}");
                    $sheet->mergeCells("E{$startRow}:E{$endRow}");

                    // Center align merged cells vertically
                    $sheet->getStyle("A{$startRow}:E{$endRow}")->getAlignment()->setVertical('center');
                }

                // Set specific widths for better readability
                $sheet->getColumnDimension('A')->setWidth(5);  // No
                $sheet->getColumnDimension('B')->setWidth(25); // NIP
                $sheet->getColumnDimension('C')->setWidth(25); // Nama
                $sheet->getColumnDimension('D')->setWidth(50); // Nama Jabatan
                $sheet->getColumnDimension('E')->setWidth(10); // Jenis
                $sheet->getColumnDimension('F')->setWidth(150); // RHK

                // Alternative: Use autosize but ensure minimum width for RHK
                // foreach (range('A', $highestColumn) as $columnID) {
                //     $sheet->getColumnDimension($columnID)->setAutoSize(true);
                // }
                // $sheet->getColumnDimension('F')->setWidth(50); // Force RHK column width

                // Apply borders to all cells with data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Make header bold
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}
