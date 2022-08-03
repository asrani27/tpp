<?php

namespace App\Exports;

use App\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PegawaiExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $skpd_id;

    public function __construct(string $skpd_id)
    {
        $this->skpd_id = $skpd_id;
    }
    public function collection()
    {
        $pegawai = Pegawai::where('skpd_id', $this->skpd_id)->get()->map(function ($item, $key) {
            $item->nomor = $key + 1;
            $item->nip = '`' . $item->nip;
            if ($item->jabatan == null) {
                $item->jabatan = null;
                $item->unitkerja = null;
            } else {
                $item->nmjabatan = $item->jabatan->nama;
                $item->unitkerja = $item->jabatan->rs == null ? 'Dinas Kesehatan' : $item->jabatan->rs->nama;
            }
            return $item->only(['nomor', 'nip', 'nama', 'nmjabatan', 'unitkerja', 'status_pns', 'is_aktif']);
        })->values();
        dd($pegawai);
        return $pegawai;
    }
    public function headings(): array
    {
        return [
            'Nomor',
            'NIP',
            'Nama',
            'Jabatan',
            'Unit Kerja',
            'Status',
            'Aktif'
        ];
    }
}
