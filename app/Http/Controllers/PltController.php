<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Pegawai;
use App\RiwayatPLT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PltController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function admin()
    {
        $jabatanTersedia = Jabatan::where('skpd_id', $this->user()->skpd->id)->get()->map(function($item){
            $item->pegawai = $item->pegawai;
            $item->pegawaiplt = $item->pegawaiplt;
            return $item;
        })->where('pegawai', null)->where('pegawaiplt', null);
        
        $dataPlt = Pegawai::where('skpd_id', $this->user()->skpd->id)->where('jabatan_plt', '!=', null)->get();

        $riwayat = RiwayatPlt::where('skpd_id', $this->user()->skpd->id)->get();
        return view('admin.plt.index',compact('jabatanTersedia','dataPlt','riwayat'));
    }

    public function adminStorePlt(Request $req)
    {
        $checkNip = Pegawai::where('nip', $req->nip)->first();
        if($checkNip == null){
            $req->flash();
            toastr()->error('NIP Pegawai Salah / Tidak Ditemukan');
            return back();
        }else{
            if($checkNip->jabatan_plt != null){
                $req->flash();
                toastr()->error('Pegawai Ini telah memiliki Jabatan PLT');
                return back();
            }else{
                DB::beginTransaction();
                try {
                    //Simpan Ke Tabel Riwayat PLT
                    $jab                = Jabatan::find($req->jabatan_plt);
                    $riwayat            = $req->all();
                    $riwayat['skpd_id'] = $jab->skpd->id;
                    $riwayat['jabatan'] = $jab->nama;
                    $riwayat['nama']    = $checkNip->nama;
                    
                    RiwayatPLT::create($riwayat);
                    
                    //Update Pegawai Untuk Jadi PLT
                    $u = $checkNip;
                    $u->jabatan_plt = $req->jabatan_plt;
                    $u->save();
                    DB::commit();
                    toastr()->success('Data Berhasil di Simpan');
                    return back();
                } catch (\Exception $e) {
                    DB::rollback();
                    $req->flash();
                    toastr()->error('Gagal');
                    return back();
                }
            }
        }
    }

    public function adminDeletePlt($id)
    {
        $u = Pegawai::find($id);
        $u->jabatan_plt = null;
        $u->save();
        
        toastr()->success('PLt Berhasil di Hapus');
        return back();
    }
}
