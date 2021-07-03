<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Rspuskesmas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->username != '1.02.01.'){
                toastr()->error('Anda Tidak Punya Akses Ke Halaman ini');
                return back();
            }
            return $next($request);
        });
    }

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
        $jumlahJabatan = $jabatan->groupBy('nama')->toArray();
        
        return view('admin.rs.jabatan',compact('jumlahJabatan','data','namarspuskesmas','edit','merge','id','kadis'));
    }
    
    public function storeJabatan(Request $req, $id)
    {
        $attr = $req->all();
        
        if($req->jabatan_id == null){
            $attr['tingkat']    = 1;
        }else{
            $attr['tingkat']    = Jabatan::find($req->jabatan_id)->tingkat + 1;
        }
        $attr['rs_puskesmas_id'] = $id;
        $attr['is_aktif']        = 1;
        $attr['skpd_id']         = Auth::user()->skpd->id;

        $jumlah = (int)$req->jumlah;
        
        DB::beginTransaction();
        try {
            for($i=0; $i<$jumlah; $i++){
                Jabatan::create($attr);
            }
            DB::commit();
            toastr()->success('Jabatan Berhasil Di Simpan');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            $req->flash();
            toastr()->error('Jabatan Gagal Disimpan');
            return back();
        }
    }

    public function editJabatan($id, $idJab)
    {
        $jabatan = Jabatan::find($idJab);
        
        $edit = true;
        $namarspuskesmas = Rspuskesmas::find($id);
        $kadis = Auth::user()->skpd->kadis;
        //$jabatan = Jabatan::where('rs_puskesmas_id', $id)->get();
        //$merge = $kadis->merge($jabatan);
       
        $jabatanrs = Jabatan::where('rs_puskesmas_id', $id)->get();
        $jumlahJabatan = $jabatanrs->groupBy('nama')->toArray();
        return view('admin.rs.jabatan',compact('namarspuskesmas','jumlahJabatan','edit','jabatan','id','kadis'));
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

    public function updateJabatan(Request $req, $id, $idJab)
    {
        Jabatan::find($idJab)->update([
            'nama' => $req->nama, 
            'kelas_id' => $req->kelas_id,
        ]);
        toastr()->success('Jabatan Berhasil Di Update');
        return redirect('/admin/rspuskesmas/'.$id.'/petajabatan');
    }
}
