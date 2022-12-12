<?php

namespace App\Http\Controllers;

use App\Skp2023Jf;
use App\Skp2023Jpt;
use App\Skp2023JfIndikator;
use App\Skp2023JptIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JFController extends Controller
{
    public function jptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['skp2023_id'] = $id;
        $attr['jenis'] = 'utama';
        $skp = Skp2023Jf::create($attr);
        Skp2023JfIndikator::create([
            'skp2023_jf_id' => $skp->id,
            'indikator' => 'indikator',
            'target' => 'target',
            'aspek' => 'aspek',
            'jenis' => 'utama',
        ]);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function updateJptRhk(Request $req, $id)
    {
        Skp2023Jf::find($req->skp2023_id)->update([
            'rhk' => $req->rhk,
            'rhk_intervensi' => $req->rhk_intervensi,
        ]);
        toastr()->success('Berhasil Di Update');
        return back();
    }
    public function deleteJptRhk($id)
    {
        $data = Skp2023Jf::find($id);
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
        Skp2023JfIndikator::create($attr);
        toastr()->success('Berhasil Di simpan');
        return back();
    }

    public function deleteIndikatorJptRhk($id, $indikator_id)
    {
        $jumlah = Skp2023Jf::find($id)->indikator->count();
        if ($jumlah == 1) {
            toastr()->info('Tidak bisa di hapus, indikator harus ada');
        } else {
            Skp2023JfIndikator::find($indikator_id)->delete();
            toastr()->success('Berhasil Di Hapus');
        }
        return back();
    }

    public function updateIndikatorJptRhk(Request $req, $id)
    {
        Skp2023JfIndikator::find($req->skp2023_jf_indikator_id)->update($req->all());
        toastr()->success('Berhasil Di Update');
        return back();
    }


    public function t_jptRhk(Request $req, $id)
    {
        $attr = $req->all();
        $attr['rhk']            = $req->rhk_tambahan;
        $attr['rhk_intervensi'] = $req->rhk_intervensi_tambahan;
        $attr['skp2023_id']     = $id;
        $attr['jenis']          = 'tambahan';
        $skp = Skp2023Jf::create($attr);
        Skp2023JfIndikator::create([
            'skp2023_jf_id' => $skp->id,
            'indikator' => 'indikator',
            'target' => 'target',
            'aspek' => 'aspek',
            'jenis' => 'tambahan',
        ]);
        toastr()->success('Berhasil Di Simpan');
        return back();
    }

    public function t_updateJptRhk(Request $req, $id)
    {
        Skp2023Jf::find($req->skp2023_id_tambahan)->update([
            'rhk' => $req->rhk,
            'rhk_intervensi' => $req->rhk_intervensi,
        ]);
        toastr()->success('Berhasil Di Update');
        return back();
    }
    public function t_deleteJptRhk($id)
    {
        $data = Skp2023Jf::find($id);
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
        $attr['skp2023_jf_id'] = $req->skp2023_jf_id_tambahan;
        $attr['jenis'] = 'tambahan';
        Skp2023JfIndikator::create($attr);
        toastr()->success('Berhasil Di simpan');
        return back();
    }

    public function t_deleteIndikatorJptRhk($id, $indikator_id)
    {
        $jumlah = Skp2023Jf::find($id)->indikator->count();
        if ($jumlah == 1) {
            toastr()->info('Tidak bisa di hapus, indikator harus ada');
        } else {
            Skp2023JfIndikator::find($indikator_id)->delete();
            toastr()->success('Berhasil Di Hapus');
        }
        return back();
    }

    public function t_updateIndikatorJptRhk(Request $req, $id)
    {
        Skp2023JfIndikator::find($req->skp2023_jf_indikator_id_tambahan)->update($req->all());
        toastr()->success('Berhasil Di Update');
        return back();
    }
}
