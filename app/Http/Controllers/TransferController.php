<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\Pegawai;
use App\RiwayatTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function admin()
    {
        $pegawai = Pegawai::where('skpd_id', $this->user()->skpd->id)->get();
        $riwayat = RiwayatTransfer::where('skpd_asal', $this->user()->skpd->id)->get();
        $skpd    = Skpd::get();
        return view('admin.transfer.index',compact('pegawai','riwayat','skpd'));
    }

    public function adminStoreTransfer(Request $req)
    {
        
        $pegawai = Pegawai::find($req->pegawai_id);
        DB::beginTransaction();
        try {
            //Simpan Ke Tabel Riwayat Transfer
            $riwayat['nip']          = $pegawai->nip;
            $riwayat['nama']         = $pegawai->nama;
            $riwayat['skpd_asal']    = $pegawai->skpd_id;
            $riwayat['jabatan_asal'] = $pegawai->jabatan == null ? '': $pegawai->jabatan->nama;
            $riwayat['skpd_baru']    = $req->skpd_id;
            
            RiwayatTransfer::create($riwayat);
            
            //Hapus jabatan dan plt sebelumnya di Pegawai
            $u = $pegawai;
            $u->jabatan_id  = null;
            $u->jabatan_plt = null;
            $u->skpd_id     = $req->skpd_id;
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
