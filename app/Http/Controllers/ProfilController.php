<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function superadmin()
    {
        return view('superadmin.profil');
    }

    public function changeSuperadmin(Request $req)
    {
        if($req->password != $req->password2){
            toastr()->error('Password Tidak Sama');
        }else{
            $p = Auth::user();
            $p->password = bcrypt($req->password);
            $p->save();
            toastr()->success('Password Berhasil Di Ubah');
        }
        return back();
    }
    
    public function admin()
    {
        return view('admin.profil');
    }

    public function pegawai()
    {
        return view('pegawai.profil');
    }

    public function walikota()
    {
        return view('walikota.profil');
    }
}
