<?php

namespace App\Http\Controllers;

use App\Skp2023;
use App\Skp2023Jf;
use Carbon\Carbon;
use App\Skp2023Jpt;
use App\Skp2023JptIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SKP2023Controller extends Controller
{
    public function index()
    {
        $data = Skp2023::where('pegawai_id', Auth::user()->pegawai->id)->paginate(15);
        return view('pegawai.skp2023.index', compact('data'));
    }

    public function storePeriode(Request $req)
    {
        $attr = $req->all();
        $attr['pegawai_id'] = Auth::user()->pegawai->id;
        $attr['mulai'] = Carbon::createFromFormat('d/m/Y', $req->mulai)->format('Y-m-d');
        $attr['sampai'] = Carbon::createFromFormat('d/m/Y', $req->sampai)->format('Y-m-d');

        if ($attr['sampai'] < $attr['mulai']) {
            toastr()->error('Periode Selesai Tidak Bisa Kurang Dari Periode Mulai');
        } else {
            Skp2023::create($attr);
            toastr()->success('Periode Berhasil Di Simpan');
        }
        return back();
    }

    public function aktifkan($id)
    {
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp2023::findOrFail($id);
        if ($pegawai_id != $u->pegawai_id) {
            toastr()->error('Terdeteksi percobaan tindakan penyalahgunaan, Akan dimasukkan ke History Keamanan', 'Authorize');
            return back();
        }

        $check = Skp2023::where('pegawai_id', Auth::user()->pegawai->id)->where('is_aktif', 1)->first();
        if ($check == null) {
            $u->update([
                'is_aktif' => 1
            ]);
        } else {
            $check->update([
                'is_aktif' => null
            ]);
            $u->update([
                'is_aktif' => 1
            ]);
        }
        toastr()->success('periode Berhasil Di Aktifkan');
        return back();
    }

    public function editPeriode($id)
    {
        $data = Skp2023::find($id);
        return view('pegawai.skp2023.edit_periode', compact('data'));
    }

    public function updatePeriode(Request $req, $id)
    {
        $attr = $req->all();
        $attr['mulai'] = Carbon::createFromFormat('d/m/Y', $req->mulai)->format('Y-m-d');
        $attr['sampai'] = Carbon::createFromFormat('d/m/Y', $req->sampai)->format('Y-m-d');

        if ($attr['sampai'] < $attr['mulai']) {
            toastr()->error('Periode Selesai Tidak Bisa Kurang Dari Periode Mulai');
            return back();
        } else {
            Skp2023::find($id)->update($attr);
            toastr()->success('Periode Berhasil Di Simpan');
            return redirect('/pegawai/new-skp');
        }
    }

    public function viewPeriode($id)
    {
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp2023::findOrFail($id);
        if ($pegawai_id != $u->pegawai_id) {
            toastr()->error('Terdeteksi Percobaan Tindakan Penyalahgunaan, Akan dimasukkan ke History Keamanan', 'Authorize');
            return back();
        }


        if ($u->jenis == 'JPT') {
            $pn = Auth::user()->pegawai;
            $pp = Auth::user()->pegawai->jabatan->atasan->pegawai;

            $skp_utama = Skp2023Jpt::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jpt::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.jpt.index', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
        }

        if ($u->jenis == 'JF') {

            $pn = Auth::user()->pegawai;
            $pp = Auth::user()->pegawai->jabatan->atasan->pegawai;

            $skp_utama = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.jf.index', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
        }

        if ($u->jenis == 'JA') {

            $pn = Auth::user()->pegawai;
            $pp = Auth::user()->pegawai->jabatan->atasan->pegawai;

            $skp_utama = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'utama')->get();
            $skp_tambahan = Skp2023Jf::where('skp2023_id', $u->id)->where('jenis', 'tambahan')->get();

            return view('pegawai.skp2023.ja.index', compact('pn', 'pp', 'u', 'skp_utama', 'skp_tambahan'));
        }
    }

    public function deletePeriode($id)
    {
        try {
            Skp2023::findOrFail($id)->delete();
            toastr()->success('Periode Berhasil Di Hapus');
            return back();
        } catch (\Exception $e) {
            toastr()->error('Periode Tidak Bisa Di Hapus Karena ada SKP Di Dalamnya');
            return back();
        }
    }

    public function jptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['skp2023_id'] = $id;
        $attr['jenis'] = 'utama';
        $skp = Skp2023Jpt::create($attr);
        Skp2023JptIndikator::create([
            'skp2023_jpt_id' => $skp->id,
            'indikator' => 'indikator',
            'target' => 'target',
            'perspektif' => 'perspektif',
            'jenis' => 'utama',
        ]);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function updateJptRhk(Request $req, $id)
    {
        Skp2023Jpt::find($req->skp2023_id)->update([
            'rhk' => $req->rhk,
        ]);
        toastr()->success('Berhasil Di Update');
        return back();
    }
    public function deleteJptRhk($id)
    {
        $data = Skp2023Jpt::find($id);
        if (Auth::user()->pegawai->id != $data->skp->pegawai_id) {
            toastr()->error('SKP ini bukan milik anda');
            return back();
        } else {
            $data->delete();
            toastr()->success('Berhasil Di hapus');
            return back();
        }
    }

    public function indikatorJptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['jenis'] = 'utama';
        Skp2023JptIndikator::create($attr);
        toastr()->success('Berhasil Di simpan');
        return back();
    }

    public function deleteIndikatorJptRhk($id, $indikator_id)
    {
        $jumlah = Skp2023Jpt::find($id)->indikator->count();
        if ($jumlah == 1) {
            toastr()->info('Tidak bisa di hapus, indikator harus ada');
        } else {
            Skp2023JptIndikator::find($indikator_id)->delete();
            toastr()->success('Berhasil Di Hapus');
        }
        return back();
    }

    public function updateIndikatorJptRhk(Request $req, $id)
    {
        Skp2023JptIndikator::find($req->skp2023_jpt_indikator_id)->update($req->all());
        toastr()->success('Berhasil Di Update');
        return back();
    }


    public function t_jptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['rhk'] = $req->rhk_tambahan;
        $attr['skp2023_id'] = $id;
        $attr['jenis'] = 'tambahan';
        $skp = Skp2023Jpt::create($attr);
        Skp2023JptIndikator::create([
            'skp2023_jpt_id' => $skp->id,
            'indikator' => 'indikator',
            'target' => 'target',
            'perspektif' => 'perspektif',
            'jenis' => 'tambahan',
        ]);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function t_updateJptRhk(Request $req, $id)
    {
        Skp2023Jpt::find($req->skp2023_id_tambahan)->update([
            'rhk' => $req->rhk,
        ]);
        toastr()->success('Berhasil Di Update');
        return back();
    }
    public function t_deleteJptRhk($id)
    {
        $data = Skp2023Jpt::find($id);
        if (Auth::user()->pegawai->id != $data->skp->pegawai_id) {
            toastr()->error('SKP ini bukan milik anda');
            return back();
        } else {
            $data->delete();
            toastr()->success('Berhasil Di hapus');
            return back();
        }
    }

    public function t_indikatorJptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['skp2023_jpt_id'] = $req->skp2023_jpt_id_tambahan;
        $attr['jenis'] = 'tambahan';
        Skp2023JptIndikator::create($attr);
        toastr()->success('Berhasil Di simpan');
        return back();
    }

    public function t_deleteIndikatorJptRhk($id, $indikator_id)
    {
        $jumlah = Skp2023Jpt::find($id)->indikator->count();
        if ($jumlah == 1) {
            toastr()->info('Tidak bisa di hapus, indikator harus ada');
        } else {
            Skp2023JptIndikator::find($indikator_id)->delete();
            toastr()->success('Berhasil Di Hapus');
        }
        return back();
    }

    public function t_updateIndikatorJptRhk(Request $req, $id)
    {
        Skp2023JptIndikator::find($req->skp2023_jpt_indikator_id_tambahan)->update($req->all());
        toastr()->success('Berhasil Di Update');
        return back();
    }
}
