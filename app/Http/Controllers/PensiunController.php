<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\RiwayatPensiun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PensiunController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function admin()
    {        
        $pegawai = Pegawai::where('skpd_id', $this->user()->skpd->id)->get();
        $riwayat = RiwayatPensiun::where('skpd_id', $this->user()->skpd->id)->get();
        return view('admin.pensiun.index',compact('pegawai','riwayat'));
    }

    public function adminStorePensiun(Request $req)
    {
        $data = Pegawai::find($req->pegawai_id);
        $riwayat['nip']             = $data->nip;
        $riwayat['nama']            = $data->nama;
        $riwayat['tanggal_pensiun'] = $req->tgl_pensiun;
        $riwayat['skpd_id']         = $this->user()->skpd->id;
        $riwayat['jabatan']         = $data->jabatan == null ? '':$data->jabatan->nama;

        
        DB::beginTransaction();
        try {
            RiwayatPensiun::create($riwayat);

            //Nonaktifkan user login dan hapus jabatan
            $data->is_aktif = 0;
            $data->jabatan_id = null;
            $data->jabatan_plt = null;
            $data->save();

            DB::commit();
            toastr()->success('berhasil Disimpan');
            return back();
        }catch(\Exception $e){
            DB::rollback();
            $req->flash();
            toastr()->error('Gagal');
            return back();
        }
    }

    public function adminDeletePensiun($id)
    {
        DB::beginTransaction();
        try {
            $riwayat = RiwayatPensiun::find($id);
            
            $u = Pegawai::where('nip',$riwayat->nip)->first();
            $u->is_aktif = 1;
            $u->jabatan_id = null;
            $u->jabatan_plt = null;
            $u->save();

            $riwayat->delete();
            
            DB::commit();
            toastr()->success('Berhasil di Hapus');
            return back();
        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Gagal');
            return back();
        }
    }
}
