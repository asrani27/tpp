<?php

namespace App\Http\Controllers;

use App\Skp2023;
use App\Skp2023Jf;
use App\Skp2023Jpt;
use App\Skp2023JfIndikator;
use App\Skp2023JptIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiSKPController extends Controller
{
    public function index()
    {
        $data = Skp2023::where('penilai', Auth::user()->pegawai->nip)->where('is_aktif', 1)->get();
        return view('pegawai.skp2023.nilai.index', compact('data'));
    }

    public function umpanBalikJPT(Request $req, $triwulan, $id)
    {
        //Store realiasasi JPT
        $data = Skp2023JptIndikator::find($req->umpan_balik_id);
        $data['ub_tw' . $triwulan] = $req->umpan_balik;
        $data->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }

    public function umpanBalikJF(Request $req, $triwulan, $id)
    {
        //Store realiasasi JPT

        $data = Skp2023JfIndikator::find($req->umpan_balik_id);
        $data['ub_tw' . $triwulan] = $req->umpan_balik;
        $data->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }

    public function umpanBalikJA(Request $req, $triwulan, $id)
    {
        //Store realiasasi JPT
        $data = Skp2023JfIndikator::find($req->umpan_balik_id);
        $data['ub_tw' . $triwulan] = $req->umpan_balik;
        $data->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }
    public function nilaiRHK(Request $req, $triwulan, $id)
    {
        //Store realiasasi JPT

        $data = Skp2023::find($id);
        $data['rhk_tw' . $triwulan] = $req->rating;
        $data->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }
    public function nilaiRPK(Request $req, $triwulan, $id)
    {
        //Store realiasasi JPT

        $data = Skp2023::find($id);
        $data['rpk_tw' . $triwulan] = $req->rating;
        $data->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }
    public function evaluasi($triwulan, $id)
    {
        $u = Skp2023::findOrFail($id);
        if ($u->jenis == 'JPT') {
            $pn = json_decode($u->pn);
            if ($u->pp == null) {
                $pp['nama'] = null;
                $pp['nip'] = null;
                $pp['pangkat'] = null;
                $pp['gol'] = null;
                $pp['jabatan'] = null;
                $pp['skpd'] = null;
            } else {
                $pp = json_decode($u->pp);
            }

            $skp_utama = Skp2023Jpt::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jpt::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.nilai.evaluasijpt', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
        }

        if ($u->jenis == 'JF') {

            $pn = json_decode($u->pn);
            if ($u->pp == null) {
                $pp['nama'] = null;
                $pp['nip'] = null;
                $pp['pangkat'] = null;
                $pp['gol'] = null;
                $pp['jabatan'] = null;
                $pp['skpd'] = null;
            } else {
                $pp = json_decode($u->pp);
            }

            $skp_utama = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.nilai.evaluasijf', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
        }

        if ($u->jenis == 'JA') {

            $pn = json_decode($u->pn);
            if ($u->pp == null) {
                $pp['nama'] = null;
                $pp['nip'] = null;
                $pp['pangkat'] = null;
                $pp['gol'] = null;
                $pp['jabatan'] = null;
                $pp['skpd'] = null;
            } else {
                $pp = json_decode($u->pp);
            }

            $skp_utama = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.nilai.evaluasija', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
        }
    }
}
