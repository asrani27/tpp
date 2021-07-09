<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\RiwayatPLT;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function plt()
    {
        $data = Pegawai::whereNotNull('jabatan_plt')->paginate(10);
        
        $riwayat = RiwayatPLT::paginate(10);
        return view('superadmin.mutasi.plt',compact('data','riwayat'));
    }

    public function historyPlt()
    {
        $data = RiwayatPLT::paginate(10);
        return view('superadmin.mutasi.historyplt',compact('data'));
    }
}
