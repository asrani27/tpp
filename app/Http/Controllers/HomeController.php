<?php

namespace App\Http\Controllers;

use App\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function superadmin()
    {
        return view('superadmin.home');
    }
    
    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }
    public function admin()
    {
        $data = Pegawai::where('skpd_id', $this->skpd_id())->orderBy('urutan','ASC')->get();
        return view('admin.home',compact('data'));
    }
    
    public function adminUp($id, $urutan)
    {
        //Pegawai Yang Di Down
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan',$urutan-1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Up
        Pegawai::find($id)->update([
            'urutan' => $urutan - 1
        ]);

        return redirect('/home/admin');
    }
    
    public function adminDown($id, $urutan)
    {
        //Pegawai Yang Di Up
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan',$urutan+1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Down
        Pegawai::find($id)->update([
            'urutan' => $urutan + 1
        ]);

        return redirect('/home/admin');
    }

    public function pegawai()
    {
        return view('pegawai.home');    
    }

    public function walikota()
    {
        return view('walikota.home');
    }
}
