<?php

namespace App\Http\Controllers;
use App\Jabatan;

use App\Aktivitas;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{

    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        $person = $this->user()->pegawai->with('jabatan');
        $atasan = $this->user()->pegawai->jabatan->atasan == null ? Jabatan::where('sekda',1)->first():$this->user()->pegawai->jabatan->atasan;
        
        $data = $this->user()->pegawai->aktivitas()->paginate(10);

        return view('pegawai.aktivitas.index',compact('data','atasan'));
    }
    
    public function add()
    {
        $tahun = Carbon::now()->year;
        
        $skp = Auth::user()->pegawai->skp_periode->where('is_aktif',1)->first()->skp;
        
        return view('pegawai.aktivitas.create',compact('skp'));
    }
    
    public function edit($id)
    {  
        $aktivitas = Aktivitas::find($id);
        if($this->user()->pegawai->id != $aktivitas->pegawai_id){
            toastr()->error('Aktivitas tidak bisa di edit, bukan milik anda','Authorize');
            return back();
        }else{
            $tahun = Carbon::now()->year;
            $skp = Auth::user()->pegawai->jabatan->skp->where('tahun', $tahun);
            $data = $aktivitas;
            return view('pegawai.aktivitas.edit',compact('skp','data'));
        }
    }
    
    public function delete($id)
    {
        $aktivitas =Aktivitas::find($id);
        if($this->user()->pegawai->id != $aktivitas->pegawai_id){
            toastr()->error('Aktivitas tidak bisa di hapus, bukan milik anda','Authorize');
            return back();
        }else{
            $aktivitas->delete();
            toastr()->success('Aktivitas berhasil Di Hapus');
            return back();
        }
        
    }

    public function store(Request $req)
    {
        $attr = $req->all();
        $attr['pegawai_id'] = Auth::user()->pegawai->id;

        if(strtotime($req->jam_selesai) > strtotime($req->jam_mulai)){        
            $menit = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
            $attr['menit'] = $menit;
            Aktivitas::create($attr);
            toastr()->success('Aktivitas berhasil Di Simpan');
            return redirect('pegawai/aktivitas/harian');
        }else{
            toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
            $req->flash();
            return back();
        }
    }
    public function update(Request $req, $id)
    {
        $attr = $req->all();
        $attr['pegawai_id'] = Auth::user()->pegawai->id;

        if(strtotime($req->jam_selesai) > strtotime($req->jam_mulai)){        
            $menit = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
            $attr['menit'] = $menit;
            Aktivitas::find($id)->update($attr);
            toastr()->success('Aktivitas berhasil Di Update');
            return redirect('pegawai/aktivitas/harian');
        }else{
            toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
            $req->flash();
            return back();
        }
    }

    public function keberatan()
    {
        
        $data = Aktivitas::where('pegawai_id',$this->user()->pegawai->id)->where('validasi',2)->paginate(10);
        return view('pegawai.aktivitas.keberatan',compact('data'));
    }
}
