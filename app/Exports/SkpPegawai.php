<?php

namespace App\Exports;

use App\Pegawai;
use App\Skp2023;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SkpPegawai implements FromCollection, WithHeadings, WithEvents
{
    private $mergeRanges = [];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $nip = [
            '198204272001121001',
            '199601062018081002',
            '199503242017081004',
            '198806042007011001',
            '199605142018082001',
            '199504072018081001',
            '197309252000032003',
            '198504072010012016',
            '198703262010012006',
            '197305182007012008',
            '198105132008012022',
            '200205122024092001',
            '200209292024092001',
            '198410192010011012',
            '197601032008012025',
            '198704302011011003',
            '198704232010011002',
            '199502152022032008',
            '198312112010012014',
            '198712202019032009',
            '198304262006041002',
            '197810202008012025',
            '196901191990101001',
            '198106102010012023',
            '198910302015021002',
            '198809152011012005',
            '198007072010012017',
            '198207092015022002',
            '199412222019032022',
            '199612132019031004',
            '199402092019032009',
            '197101041996032008',
            '197608202005011000',
            '198001092006041003',
            '198002172006042010',
            '197712292009012002',
            '197611122010011004',
            '198304202005011004',
            '197604041998031009',
            '198612132010011003',
            '198907062011011001',
            '199209122015022001',
            '198002152009011002',
            '197411122006041005',
            '198008302010012008',
            '198310022010011012',
            '197912042009031002',
            '198905262011011004',
            '197008272005011008',
            '197802282009012001',
            '197602072010011009',
            '198209202010011015',
            '198711082009032002',
            '198701292010011002',
            '199312272022032007',
            '199907182021081001',
            '199912132022081001',
            '199909132022081001',
            '200103102023081002',
            '200108082023081002',
            '199212232016091001',
            '198505292012121003',
            '198110142007011003',
            '197211292007012006',
            '196807232007012020',
            '197404182000032007',
            '197508231995031001',
            '198008172009031007',
            '198008032005012017',
            '199208052022032007',
            '198211272010011014',
            '198104262010011007',
            '199803032021081002',
            '198309062010012015',
            '197703202006042019',
            '198607152009032009',
            '198612102010011006',
            '198309292001122001',
            '198805272007011001',
            '197506262007012017',
            '198811142015022002',
            '199305052019032024',
            '199106032020122015',
            '199203042020122018',
            '199507272020121020',
            '199511222020122025',
            '198608152019032010',
            '199012032019031008',
            '196711272006041007',
            '198207312006041012',
            '197705102010012012',
            '197501032006041012',
            '198609112010012014',
            '197206242006042020',
            '198612302010012014',
            '198112092003121006',
            '198108162010011012',
            '197910162010011010',
            '197612132009041002',
            '199507042017082001',
            '198901172010012001',
            '198807242010012007',
            '199603282018081003',
            '200006202022081001',
            '198710302010012007',
            '198212092008032001',
            '198705092010012018',
            '198105012008012036',
            '198712192011012005',
            '198305082010012012',
            '198708242009032002',
            '197201312006041012',
            '196809152007011030',
            '198005182009011002',
            '196902172010011001',
            '197003042007011031',
            '197210102006041022',
            '197006102007011040',
            '197409152007011016',
            '198409292010011016',
            '197612052006041016',
            '197011241991011004',
            '196810261994031007',
            '196503281988031009',
            '196511291992031006',
            '196812251998031004',
            '196502071992031006',
        ];

        // Get pegawai data using whereIn
        $pegawaiData = Pegawai::whereIn('nip', $nip)
            ->leftJoin('skp2023', function ($join) {
                $join->on('pegawai.id', '=', 'skp2023.pegawai_id')
                    ->whereRaw('skp2023.id = (SELECT MAX(s2.id) FROM skp2023 s2 WHERE s2.pegawai_id = pegawai.id)');
            })
            ->select('pegawai.nip', 'pegawai.nama', 'skp2023.jenis', 'skp2023.id as skp_id')
            ->orderBy('pegawai.nama')
            ->get();

        $result = collect();
        $pegawaiCounter = 1;
        $currentRow = 1;
        $this->mergeRanges = [];

        foreach ($pegawaiData as $item) {
            // Get all RHK based on jenis
            $rhkList = $this->getAllRHK($item->jenis, $item->skp_id);

            if (empty($rhkList)) {
                // If no RHK found, still add the row with empty RHK
                $result->push([
                    'NO' => $pegawaiCounter,
                    'NIP' => '`' . $item->nip,
                    'NAMA' => $item->nama ?? '',
                    'JENIS' => $item->jenis ?? '',
                    'RHK' => ''
                ]);
                $pegawaiCounter++;
                $currentRow++;
            } else {
                $startRow = $currentRow;
                $rhkCount = count($rhkList);

                // Add multiple rows for each RHK
                foreach ($rhkList as $index => $rhk) {
                    $result->push([
                        'NO' => $pegawaiCounter, // Always show the same pegawai number
                        'NIP' => '`' . $item->nip,
                        'NAMA' => $item->nama ?? '',
                        'JENIS' => $item->jenis ?? '',
                        'RHK' => $rhk
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

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'NIP',
            'NAMA',
            'JENIS',
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

                // Apply merge cells for columns A-D based on RHK count
                foreach ($this->mergeRanges as $range) {
                    $startRow = $range['start'] + 1; // +1 because of header row
                    $endRow = $range['end'] + 1; // +1 because of header row

                    // Merge columns A (No), B (NIP), C (Nama), D (Jenis)
                    $sheet->mergeCells("A{$startRow}:A{$endRow}");
                    $sheet->mergeCells("B{$startRow}:B{$endRow}");
                    $sheet->mergeCells("C{$startRow}:C{$endRow}");
                    $sheet->mergeCells("D{$startRow}:D{$endRow}");

                    // Center align merged cells vertically
                    $sheet->getStyle("A{$startRow}:D{$endRow}")->getAlignment()->setVertical('center');
                }

                // Set specific widths for better readability
                $sheet->getColumnDimension('A')->setWidth(5);   // NO
                $sheet->getColumnDimension('B')->setWidth(25);  // NIP
                $sheet->getColumnDimension('C')->setWidth(30);  // NAMA
                $sheet->getColumnDimension('D')->setWidth(15);  // JENIS
                $sheet->getColumnDimension('E')->setWidth(150); // RHK

                // Apply borders to all cells with data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Make header bold and center aligned
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Center align NO column
                $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
