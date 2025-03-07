<?php

namespace App\Http\Controllers;

use App\Exports\KonsolidasiTPP;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BPKPADController extends Controller
{
    public function index()
    {
        return view('bpkpad.home');
    }
    public function konsolidasitpp(Request $req)
    {
        Excel::download(new KonsolidasiTPP($req->bulan, $req->tahun), 'tppexport.xlsx');
        return view('bpkpad.home');
    }
}
