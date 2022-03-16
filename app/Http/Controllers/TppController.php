<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TppController extends Controller
{
    public function index()
    {
        return view('pegawai.tpp.index');
    }

    public function grafik()
    {
        return view('pegawai.tpp.grafik');
    }

    public function tppBulanTahun($bulan, $tahun)
    {
        return view('superadmin.tpp.skpd', compact('bulan', 'tahun'));
    }

    public function tppSkpd($bulan, $tahun, $id)
    {
        return view('superadmin.tpp.skpd', compact('bulan', 'tahun'));
    }
}
