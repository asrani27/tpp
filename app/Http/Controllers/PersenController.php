<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\Jabatan;
use App\Rspuskesmas;
use Illuminate\Http\Request;

class PersenController extends Controller
{
    public function index()
    {
        $skpd = Skpd::get();
        $puskesmas = Rspuskesmas::get();
        return view('superadmin.persen.index', compact('skpd', 'puskesmas'));
    }

    public function detailSkpd($id)
    {
        $data = Skpd::find($id)->jabatan->where('rs_puskesmas_id', null);
        return view('superadmin.persen.detail_skpd', compact('data', 'id'));
    }

    public function detailPuskesmas($id)
    {
        $data = Rspuskesmas::find($id)->jabatan;

        return view('superadmin.persen.detail_puskesmas', compact('data', 'id'));
    }

    public function editPersen($skpd_id, $id)
    {
        $data = Jabatan::find($id);
        return view('superadmin.persen.edit', compact('data', 'skpd_id', 'id'));
    }

    public function updatePersen(Request $req, $skpd_id, $id)
    {
        $data = Jabatan::find($id);
        $data->persen_beban_kerja = $req->persen_beban_kerja;
        $data->persen_tambahan_beban_kerja = $req->persen_tambahan_beban_kerja;
        $data->persen_prestasi_kerja = $req->persen_prestasi_kerja;
        $data->persen_kondisi_kerja = $req->persen_kondisi_kerja;
        $data->persen_kelangkaan_profesi = $req->persen_kelangkaan_profesi;
        $data->persentase_tpp = $req->persen_beban_kerja + $req->persen_tambahan_beban_kerja + $req->persen_prestasi_kerja;
        $data->save();
        toastr()->success('Berhasil diupdate');
        return redirect('/superadmin/persentase/skpd/' . $skpd_id);
    }

    public function editPersenPuskesmas($puskesmas_id, $id)
    {
        $data = Jabatan::find($id);
        return view('superadmin.persen.edit_puskesmas', compact('data', 'puskesmas_id', 'id'));
    }

    public function updatePersenPuskesmas(Request $req, $puskesmas_id, $id)
    {
        $data = Jabatan::find($id);
        $data->persen_beban_kerja = $req->persen_beban_kerja;
        $data->persen_tambahan_beban_kerja = $req->persen_tambahan_beban_kerja;
        $data->persen_prestasi_kerja = $req->persen_prestasi_kerja;
        $data->persen_kondisi_kerja = $req->persen_kondisi_kerja;
        $data->persen_kelangkaan_profesi = $req->persen_kelangkaan_profesi;
        $data->persentase_tpp = $req->persen_beban_kerja + $req->persen_tambahan_beban_kerja + $req->persen_prestasi_kerja;
        $data->save();
        toastr()->success('Berhasil diupdate');
        return redirect('/superadmin/persentase/puskesmas/' . $puskesmas_id);
    }
    public function subK($id)
    {
        Jabatan::find($id)->update([
            'subkoordinator' => 1,
            'persen_kondisi_kerja' => 20,
        ]);
        toastr()->success('Berhasil diupdate');
        return back();
    }

    public function nonSubK($id)
    {
        Jabatan::find($id)->update([
            'subkoordinator' => null,
            'persen_kondisi_kerja' => 0,
        ]);
        toastr()->success('Berhasil diupdate');
        return back();
    }
}
