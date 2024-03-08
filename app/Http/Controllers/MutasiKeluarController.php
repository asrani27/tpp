<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\MutasiKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MutasiKeluarController extends Controller
{
    public function user()
    {
        return Auth::user();
    }

    public function index()
    {
        $pegawai = Pegawai::where('skpd_id', $this->user()->skpd->id)->get();
        $riwayat = MutasiKeluar::where('skpd_id', $this->user()->skpd->id)->get();
        return view('admin.mutasikeluar.index', compact('pegawai', 'riwayat'));
    }

    public function store(Request $req)
    {
        $data = Pegawai::find($req->pegawai_id);
        $riwayat['nip']             = $data->nip;
        $riwayat['nama']            = $data->nama;
        $riwayat['tanggal'] = $req->tgl;
        $riwayat['skpd_id']         = $this->user()->skpd->id;
        $riwayat['jabatan']         = $data->jabatan == null ? '' : $data->jabatan->nama;


        DB::beginTransaction();
        try {
            MutasiKeluar::create($riwayat);

            //Nonaktifkan user login dan hapus jabatan
            $data->is_aktif = 0;
            $data->jabatan_id = null;
            $data->jabatan_plt = null;
            $data->save();

            DB::commit();
            toastr()->success('berhasil Disimpan');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            $req->flash();
            toastr()->error('Gagal');
            return back();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $riwayat = MutasiKeluar::find($id);

            $u = Pegawai::where('nip', $riwayat->nip)->first();
            $u->is_aktif = 1;
            $u->jabatan_id = null;
            $u->jabatan_plt = null;
            $u->save();

            $riwayat->delete();

            DB::commit();
            toastr()->success('Berhasil di Hapus');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->error('Gagal');
            return back();
        }
    }
}
