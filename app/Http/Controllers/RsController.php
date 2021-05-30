<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Rspuskesmas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RsController extends Controller
{
    public function index()
    {
        $data = Rspuskesmas::paginate(10);
        return view('admin.rs.index',compact('data'));
    }
    
    public function create()
    {
        return view('admin.rs.create');
    }
    
    public function edit($id)
    {
        $data = Rspuskesmas::find($id);
        return view('admin.rs.edit',compact('data'));
    }

    public function store(Request $req)
    {
        Rspuskesmas::create($req->all());
        toastr()->success('Data Berhasil Di Simpan');
        return redirect('/admin/rspuskesmas');
    }
    
    public function update(Request $req, $id)
    {
        Rspuskesmas::find($id)->update([
            'nama' => $req->nama,
        ]);
        toastr()->success('Data Berhasil Di Update');
        return redirect('/admin/rspuskesmas');
    }

    public function destroy($id)
    {
        try{
            Rspuskesmas::find($id)->delete();
            toastr()->success('Data Berhasil Di Hapus');
            return back();
        }
        catch(\Exception $e)
        {
            toastr()->error('Gagal Di hapus');
            return back();
        }
    }

    public function jabatan($id)
    {
        $data = [];
        $edit = false;
        $namarspuskesmas = Rspuskesmas::find($id);
        $kadis = Auth::user()->skpd->kadis;
        $jabatan = Jabatan::where('rs_puskesmas_id', $id)->get();
        $merge = $kadis->merge($jabatan);
        
        return view('admin.rs.jabatan',compact('data','namarspuskesmas','edit','merge','id','kadis'));
    }
    
    public function storeJabatan(Request $req, $id)
    {
        $attr = $req->all();
        $attr['tingkat']         = 9;
        $attr['rs_puskesmas_id'] = $id;
        $attr['is_aktif']        = 1;
        $attr['skpd_id']         = Auth::user()->skpd->id;

        Jabatan::create($attr);
        
        toastr()->success('Jabatan Berhasil Di Simpan');
        return back();
    }

    public function editJabatan($id, $idJab)
    {
        $jabatan = Jabatan::find($idJab);
        
        $edit = true;
        $namarspuskesmas = Rspuskesmas::find($id);
        $kadis = Auth::user()->skpd->kadis;
        //$jabatan = Jabatan::where('rs_puskesmas_id', $id)->get();
        //$merge = $kadis->merge($jabatan);
       
        return view('admin.rs.jabatan',compact('namarspuskesmas','edit','jabatan','id','kadis'));
    }

    public function deleteJabatan($id, $idJab)
    {
        try {
            Jabatan::find($idJab)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
            return back();
        } catch (\Throwable $th) {
            toastr()->error('Tidak Bisa Di Hapus');
            return back();
        }
    }
}
