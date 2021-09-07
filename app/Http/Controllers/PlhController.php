<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Pegawai;
use App\RiwayatPlh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlhController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function admin()
    {
        $jabatan = Jabatan::where('skpd_id', $this->user()->skpd->id)->get()->map(function($item){
            $item->pegawai = $item->pegawai;
            $item->pegawaiplh = $item->pegawaiplh;
            return $item;
        });
        
        $jabatanTersedia = $jabatan->where('pegawaiplh', null);
        
        $dataPlh = $jabatan->where('pegawaiplh', '!=',null);
        
        $riwayat = RiwayatPlh::where('skpd_id', $this->user()->skpd->id)->get();
        return view('admin.plh.index',compact('jabatanTersedia','dataPlh','riwayat'));
    }

    public function adminStorePlh(Request $req)
    {
        $checkNip = Pegawai::where('nip', $req->nip)->first();
        if($checkNip == null){
            $req->flash();
            toastr()->error('NIP Pegawai Salah / Tidak Ditemukan');
            return back();
        }else{
            if($checkNip->jabatan_plh != null){
                $req->flash();
                toastr()->error('Pegawai Ini telah memiliki Jabatan PLH');
                return back();
            }else{
                DB::beginTransaction();
                try {
                    //Simpan Ke Tabel Riwayat PLH
                    $jab                = Jabatan::find($req->jabatan_plh);
                    $riwayat            = $req->all();
                    $riwayat['skpd_id'] = $jab->skpd->id;
                    $riwayat['jabatan'] = $jab->nama;
                    $riwayat['nama']    = $checkNip->nama;
                    
                    RiwayatPLH::create($riwayat);
                    
                    //Update Pegawai Untuk Jadi PLH
                    $u = $checkNip;
                    $u->jabatan_plh = $req->jabatan_plh;
                    $u->jenis_plt   = $req->jenis_plt;
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

    public function adminDeletePlh($id)
    {
        $u = Pegawai::find($id);
        $u->jabatan_plh = null;
        $u->jenis_plt = null;
        $u->save();
        
        toastr()->success('Berhasil di Hapus');
        return back();
    }
}
