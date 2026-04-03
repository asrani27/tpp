<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PaguExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        return view('superadmin.export.index');
    }

    public function exportPagu(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $bulanNama = $this->getBulanNama($bulan);

        return Excel::download(
            new PaguExport($bulan, $tahun),
            'export_pagu_' . $bulanNama . '_' . $tahun . '.xlsx'
        );
    }

    private function getBulanNama($bulan)
    {
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        return $namaBulan[$bulan] ?? $bulan;
    }
}
