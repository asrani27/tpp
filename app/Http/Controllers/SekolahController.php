<?php

namespace App\Http\Controllers;

use App\Sekolah;
use App\JabatanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->username != '1.01.01.'){
                toastr()->error('Anda Tidak Punya Akses Ke Halaman ini');
                return back();
            }
            return $next($request);
        });
    }

    public function index()
    {
        $data = Sekolah::paginate(10);
        return view('admin.sekolah.index',compact('data'));
    }
    
    public function create()
    {
        return view('admin.sekolah.create');
    }
    
    public function store(Request $req)
    {
        $attr = $req->all();
        
        Sekolah::create($attr);
        toastr()->success('Berhasil Di Simpan');
        return redirect('admin/sekolah');   
    }
    
    public function edit($id)
    {
        $data = Sekolah::find($id);
        return view('admin.sekolah.edit',compact('data'));
    }
    
    public function update(Request $req, $id)
    {
        $attr = $req->all();
        
        Sekolah::find($id)->update($attr);
        toastr()->success('Berhasil Di Update');
        return redirect('admin/sekolah');   
    }
    
    public function destroy($id)
    {
        Sekolah::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function jabatan($id)
    {
        $data = [];
        $edit = false;
        $sekolah         = Sekolah::find($id);
        $kadis           = Auth::user()->skpd->kadis;
        $jabatan         = JabatanSekolah::where('sekolah_id', $id)->get();
        //$merge           = $kadis->merge($jabatan);
        //$jumlahJabatan   = $jabatan->groupBy('nama')->toArray();
        //dd($sekolah, $jabatan);
        return view('admin.sekolah.jabatan',compact('data','sekolah','edit','id','kadis', 'jabtaan '));
    }

    public function storeJabatan(Request $req, $id)
    {
        $attr = $req->all();
        $attr['sekolah_id'] = $id;
        JabatanSekolah::create($attr);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }
}
