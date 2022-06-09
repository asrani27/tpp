<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\RekapTpp;
use App\Rspuskesmas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapitulasiPnsPuskesmas extends Controller
{
    public function index()
    {
        return view('admin.rekap_pns_puskesmas.index');
    }

    public function puskesmas($bulan, $tahun)
    {
        $data = Rspuskesmas::where('id', '!=', 8)->get();

        return view('admin.rekap_pns_puskesmas.puskesmas', compact('data', 'bulan', 'tahun'));
    }

    public function bulanTahun($bulan, $tahun, $puskesmas_id)
    {
        $jabatan = Jabatan::where('skpd_id', Auth::user()->skpd->id)->where('rs_puskesmas_id', $puskesmas_id)->get()->groupBy('nama');

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('status_pns', 'pns')->orderBy('kelas', 'DESC')->get();

        $puskesmas = Rspuskesmas::find($puskesmas_id);
        return view('admin.rekap_pns_puskesmas.bulantahun', compact('data', 'bulan', 'tahun', 'jabatan', 'puskesmas'));
    }
}
