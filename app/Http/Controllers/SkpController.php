<?php

namespace App\Http\Controllers;

use App\Skp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{
 
    public function index()
    {
        $data = Auth::user()->pegawai->jabatan->skp()->paginate(10);
        return view('pegawai.skp.index',compact('data'));
    }
    
    public function add()
    {
        return view('pegawai.skp.create');
    }

    public function store(Request $req)
    {
        $attr = $req->all();
        $attr['jabatan_id'] = Auth::user()->pegawai->jabatan_id;
        Skp::create($attr);
        toastr()->success('SKP Berhasil Di Simpan');
        return redirect('pegawai/skp/rencana-kegiatan');
    }
    
    public function edit($id)
    {
        $data = Skp::find($id);
        return view('pegawai.skp.edit',compact('data'));
    }
    public function update(Request $req, $id)
    {
        Skp::find($id)->update($req->all());
        toastr()->success('SKP Berhasil Di Update');
        return redirect('pegawai/skp/rencana-kegiatan');
    }

    public function delete($id)
    {
        try{
            $del = Skp::find($id)->delete();
            toastr()->success('SKP Berhasil Di Hapus');
            return back();
        }catch(\Exception $e)
        {
            toastr()->error('SKP tidak bisa di hapus, karena terkait dengan aktivitas');
            return back();
        }
        
    }
}
