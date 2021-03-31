<?php

namespace App\Http\Controllers;

use App\R_tpp;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function tpp()
    {
        $data = R_tpp::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.tpp',compact('data'));
    }
    
    public function aktivitas()
    {
        $data = Aktivitas::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.aktivitas',compact('data'));
    }
    
    public function penghasilan()
    {
        $data = R_tpp::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.penghasilan',compact('data'));
    }
}
