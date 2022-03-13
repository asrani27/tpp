<?php

namespace App\Imports;

use App\Pegawai;
use App\RekapTpp;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BpjsImport implements ToModel, WithCalculatedFormulas
{
    use Importable;
    public function model(array $row)
    {
        //return $row;
        // $bpjs = [];
        // dd($row);
        // foreach ($row as $key => $item) {
        //     dd($item, $key);
        // }
    }
}
