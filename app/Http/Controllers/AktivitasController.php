<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        return view('pegawai.aktivitas.index');
    }
    
    public function add()
    {
        return view('pegawai.aktivitas.create');
    }
}
