<?php

namespace App\Http\Controllers;

use App\Skp2023;
use App\Skp2023Jf;
use App\Skp2023Jpt;
use App\Skp2023Ekspektasi;
use App\Skp2023JfIndikator;
use App\Skp2023JptIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalikotaController extends Controller
{
    public function ekspektasi($id)
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

            return view('walikota.skp2023.ekspektasi.jpt', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
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

            return view('walikota.skp2023.ekspektasi.jf', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
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

            return view('walikota.skp2023.ekspektasi.ja', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
        }
    }

    public function deleteEkspektasi($id)
    {
        Skp2023Ekspektasi::find($id)->delete();
        return back();
    }
    public function simpanEkspektasi(Request $req, $id)
    {
        $new = new Skp2023Ekspektasi;
        $new->skp2023_id = $id;
        $new->ekspektasi = $req->ekspektasi;
        $new->pkid = $req->pkid;
        $new->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
    }

    public function simpanEkspektasiTriwulan(Request $req, $id)
    {
        $new = new Skp2023Ekspektasi;
        $new->skp2023_id = $id;
        $new->ekspektasi = $req->ekspektasi;
        $new->pkid = $req->pkid;
        $new->jenis = 'TW' . $req->triwulan;
        $new->save();
        toastr()->success('Berhasil Di Simpan', 'Success');
        return back();
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
        if ($triwulan == '1') {
            $u['ekspektasi1'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 1)->get();
            $u['ekspektasi2'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 2)->get();
            $u['ekspektasi3'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 3)->get();
            $u['ekspektasi4'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 4)->get();
            $u['ekspektasi5'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 5)->get();
            $u['ekspektasi6'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 6)->get();
            $u['ekspektasi7'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW1')->where('pkid', 7)->get();
        } elseif ($triwulan == '2') {
            $u['ekspektasi1'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 1)->get();
            $u['ekspektasi2'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 2)->get();
            $u['ekspektasi3'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 3)->get();
            $u['ekspektasi4'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 4)->get();
            $u['ekspektasi5'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 5)->get();
            $u['ekspektasi6'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 6)->get();
            $u['ekspektasi7'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW2')->where('pkid', 7)->get();
        } elseif ($triwulan == '3') {
            $u['ekspektasi1'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 1)->get();
            $u['ekspektasi2'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 2)->get();
            $u['ekspektasi3'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 3)->get();
            $u['ekspektasi4'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 4)->get();
            $u['ekspektasi5'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 5)->get();
            $u['ekspektasi6'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 6)->get();
            $u['ekspektasi7'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW3')->where('pkid', 7)->get();
        } elseif ($triwulan == '4') {
            $u['ekspektasi1'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 1)->get();
            $u['ekspektasi2'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 2)->get();
            $u['ekspektasi3'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 3)->get();
            $u['ekspektasi4'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 4)->get();
            $u['ekspektasi5'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 5)->get();
            $u['ekspektasi6'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 6)->get();
            $u['ekspektasi7'] = Skp2023Ekspektasi::where('skp2023_id', $id)->where('jenis', 'TW4')->where('pkid', 7)->get();
        }

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

            return view('walikota.skp2023.nilai.evaluasijpt', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
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

            return view('walikota.skp2023.nilai.evaluasijf', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
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

            return view('walikota.skp2023.nilai.evaluasija', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan', 'triwulan'));
        }
    }
}
