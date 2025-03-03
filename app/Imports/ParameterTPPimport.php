<?php

namespace App\Imports;

use App\Jabatan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParameterTPPimport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
            $jabatan = Jabatan::find($item['id']);

            if ($jabatan) {
                $jabatan->update([
                    'persen_beban_kerja' => $item['parameter_beban_kerja'],
                    'persen_tambahan_beban_kerja' => $item['parameter_tambahan_beban_kerja'],
                    'persen_prestasi_kerja' => $item['parameter_prestasi_kerja'],
                    'persen_kondisi_kerja' => $item['parameter_kondisi_kerja'],
                    'persen_kelangkaan_profesi' => $item['parameter_kelangkaan_profesi'],
                ]);
            }
        }
    }
}
