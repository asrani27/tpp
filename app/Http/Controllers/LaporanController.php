<?php

namespace App\Http\Controllers;

use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function tpp()
    {
        return view('pegawai.laporan.tpp');
    }
    
    public function aktivitas()
    {
        $data = Aktivitas::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.aktivitas',compact('data'));
    }
    
    public function penghasilan()
    {
        return view('pegawai.laporan.penghasilan');
    }
}
