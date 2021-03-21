<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function pegawai()
    { 
        $data = Pegawai::where('skpd_id', Auth::user()->skpd->id)->paginate(10);
        return view('admin.pegawai.index',compact('data'));
    }

    public function addPegawai()
    {
        return view('admin.pegawai.create');
    }

    public function storePegawai(Request $req)
    {
        $messages = [
            'numeric' => 'Inputan Harus Angka',
            'min'     => 'Harus 18 Digit',
            'unique'  => 'NIP sudah Ada',
        ];

        $rules = [
            'nip' =>  'unique:pegawai|min:18|numeric',
            'nama' => 'required'
        ];
        $req->validate($rules, $messages);
        
        $req->flash();
        
        $urutan          = Skpd::find($this->skpd_id())->pegawai->sortBy('urutan')->last()->urutan + 1;
        $attr            = $req->all();
        $attr['urutan']  = $urutan;
        $attr['skpd_id'] = $this->skpd_id();
        $attr['verified'] = 1;

        Pegawai::create($attr);

        toastr()->success('Pegawai Berhasil Di Simpan');
        return redirect('/admin/pegawai');
        
    }

    public function jabatan()
    {
        $skpd_id = Auth::user()->skpd->id;
        return view('admin.jabatan.index',compact('data'));
    }
}
