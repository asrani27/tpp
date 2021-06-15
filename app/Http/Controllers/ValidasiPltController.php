<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiPltController extends Controller
{
    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        if($this->user()->pegawai->jabatan == null){
            toastr()->info('Tidak bisa melakukan validasi karena anda tidak memiliki jabatan, hub admin SKPD');
            return back();
        }

        
        $data1 = $this->user()->pegawai->jabatanPlt->bawahan->load('pegawai')->map(function($item){
            $item->nama_pegawai   = $item->pegawai == null ? '-':$item->pegawai->nama;
            $item->aktivitas_baru = $item->pegawai == null ?  0:$item->pegawai->aktivitas->where('validasi', 0)->count();
            return $item;
        });
        if($this->user()->pegawai->jabatanPlt->sekda == 1){
            $data2 = Jabatan::where('jabatan_id', null)->where('sekda', null)->get()->map(function($item){
                $item->nama = $item->nama.', SKPD : '. $item->skpd->nama;
                $item->nama_pegawai   = $item->pegawai == null ? '-':$item->pegawai->nama;
                $item->aktivitas_baru = $item->pegawai == null ?  0:$item->pegawai->aktivitas->where('validasi', 0)->count();
                return $item;
            });
        }else{
            $data2 = collect([]);
        }

        $data = $data1->merge($data2);
        
        return view('pegawai.validasiplt.index',compact('data'));
    } 
    
    public function view($id)
    {
        $pegawai = Jabatan::find($id)->pegawai;
        $data = $pegawai->aktivitas()->where('validasi',0)->paginate(10);
        
        return view('pegawai.validasiplt.detail',compact('data','pegawai','id'));
    }

    public function accAktivitas($id)
    {
        Aktivitas::findOrFail($id)->update([
            'validasi' => 1,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Setujui');
        return back();
    }

    public function tolakAktivitas($id)
    {
        Aktivitas::findOrFail($id)->update([
            'validasi' => 2,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Tolak');
        return back();
    }

    public function accSemua($id)
    {
        $jabatan_saya = $this->user()->pegawai->jabatan;
        $jabatan = Jabatan::with('pegawai.aktivitas')->findOrFail($id);
        dd($jabatan_saya, $jabatan->atasan);
        if($jabatan_saya->id != $jabatan->atasan->id){
            toastr()->error('Tidak Bisa Validasi , bukan bawahan anda','Authorize');
            return back();
        }else{
            $data = $jabatan->pegawai->aktivitas;
            $data->map(function($item){
                $item->update([
                    'validasi' => 1,
                    'validator' => Auth::user()->pegawai->id,
                ]);
                return $item;
            });
            toastr()->success('Semua Aktivitas Di Setujui');
            return back();
        }
    }
}
