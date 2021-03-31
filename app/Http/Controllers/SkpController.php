<?php

namespace App\Http\Controllers;

use App\Skp;
use App\Skp_periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{
 
    public function index()
    {
        $data = Auth::user()->pegawai->skp_periode()->paginate(10);
        return view('pegawai.skp.index',compact('data'));
    }
    
    public function editPeriode($id)
    {

    }

    public function storeSkp(Request $req, $id)
    {
        $attr = $req->all();
        $attr['skp_periode_id'] = $id;
        Skp::create($attr);
        toastr()->success('SKP Berhasil Di Simpan');
        return back();
    }
    public function viewPeriode($id)
    { 
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp_periode::findOrFail($id);
        if($pegawai_id != $u->pegawai_id){
            toastr()->error('Terdeteksi Percobaan Tindakan Penyalahgunaan, Akan dimasukkan ke History Keamanan','Authorize');
            return back();
        }

        $data = $u->skp()->paginate(10);
        return view('pegawai.skp.detail',compact('data','id'));
    }

    public function deletePeriode($id)
    {
        try{
            Skp_periode::findOrFail($id)->delete();
            toastr()->success('Periode Berhasil Di Hapus');
            return back();
        }catch(\Exception $e)
        {
            toastr()->error('Periode Tidak Bisa Di Hapus Karena ada SKP Di Dalamnya');
            return back();
        }
    }
    public function storePeriode(Request $req)
    {
        $attr = $req->all();
        $attr['pegawai_id'] = Auth::user()->pegawai->id;
        Skp_periode::create($attr);
        toastr()->success('Periode Berhasil Di Simpan');
        return back();
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

    public function aktifkan($id)
    {   
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp_periode::findOrFail($id);
        if($pegawai_id != $u->pegawai_id){
            toastr()->error('Terdeteksi percobaan tindakan penyalahgunaan, Akan dimasukkan ke History Keamanan','Authorize');
            return back();
        }

        $check = Skp_periode::where('pegawai_id', Auth::user()->pegawai->id)->where('is_aktif',1)->first();
        if($check == null){
            $u->update([
                'is_aktif' => 1
            ]);
        }else{
            $check->update([
                'is_aktif' => null
            ]);
            $u->update([
                'is_aktif' => 1
            ]);
        }
        toastr()->success('periode Berhasil Di Aktifkan');
        return back();
    }
}
