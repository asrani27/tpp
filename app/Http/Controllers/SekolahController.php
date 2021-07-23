<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\Jabatan;
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
        try {
            Sekolah::find($id)->delete();
            toastr()->success('Berhasil Di Hapus');
            return back();
        } catch (\Throwable $th) {            
            
            $error['message'] = 'test';
            ErrorLog::create($error);

            toastr()->error('Tidak Bisa Di Hapus');
            return back();
        }
    }

    public function jabatan($id)
    {
        $data = [];
        $edit = false;
        $sekolah         = Sekolah::find($id);
        $kadis           = Auth::user()->skpd->kadis;
        $jabatan         = Jabatan::where('sekolah_id', $id)->get();

        return view('admin.sekolah.jabatan',compact('data','sekolah','edit','id','kadis', 'jabatan'));
    }

    public function storeJabatan(Request $req, $id)
    {
        $attr = $req->all();
        $attr['sekolah_id'] = $id;
        Jabatan::create($attr);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function deleteJabatan($id_sekolah, $id_jabatan)
    {
        try {
            JabatanSekolah::find($id_jabatan)->delete();
            toastr()->success('Jabatan Berhasil Di Hapus');
            return back();
        } catch (\Throwable $th) {            
            toastr()->error('Tidak Bisa Di Hapus');
            return back();
        }
    }

    public function editJabatan($id, $id_jabatan)
    {
        $data            = JabatanSekolah::find($id_jabatan);
        $jabatan         = JabatanSekolah::where('sekolah_id', $id)->get();
        
        $edit = true;
        $sekolah = Sekolah::find($id);
        $kadis = Auth::user()->skpd->kadis;
       
        return view('admin.sekolah.jabatan',compact('data','sekolah','edit','jabatan','id'));
    }

    public function updateJabatan(Request $req, $id, $idJab)
    {
        JabatanSekolah::find($idJab)->update([
            'nama' => $req->nama, 
            'kelas_id' => $req->kelas_id,
        ]);
        toastr()->success('Jabatan Berhasil Di Update');
        return redirect('/admin/sekolah/'.$id.'/petajabatan');
    }
}
