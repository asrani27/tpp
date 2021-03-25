<?php

namespace App\Http\Controllers;

use App\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        $data = $this->user()->pegawai->jabatan->bawahan->load('pegawai')->map(function($item){
            $item->nama_pegawai = $item->pegawai == null ? '-':$item->pegawai->nama;
            $item->aktivitas_baru = $item->pegawai == null ? 0:$item->pegawai->aktivitas->where('validasi', 0)->count();
            return $item;
        });
        
        return view('pegawai.validasi.index',compact('data'));
    }

    public function accSemua($id)
    {
        $jabatan_saya = $this->user()->pegawai->jabatan;
        $jabatan = Jabatan::with('pegawai.aktivitas')->findOrFail($id);
        if($jabatan_saya->id != $jabatan->atasan->id){
            toastr()->error('Tidak Bisa Validasi , bukan bawahan anda','Authorize');
            return back();
        }else{
            $data = $jabatan->pegawai->aktivitas;
            $data->map(function($item){
                $item->update([
                    'validasi' => 1,
                ]);
                return $item;
            });
            toastr()->success('Aktivitas Di ACC');
            return back();
        }
    }

    public function view($id)
    {
        $pegawai = Jabatan::find($id)->pegawai;
        $data = $pegawai->aktivitas()->where('validasi',0)->paginate(10);
        
        return view('pegawai.validasi.detail',compact('data','pegawai'));
    }
}
