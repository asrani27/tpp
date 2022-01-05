<?php

namespace App\Http\Controllers;

use App\Skp;
use App\Pegawai;
use Carbon\Carbon;
use App\Skp_periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{

    public function index()
    {
        $data = Auth::user()->pegawai->skp_periode()->paginate(10);
        return view('pegawai.skp.index', compact('data'));
    }

    public function editPeriode($id)
    {
        $data = Skp_periode::find($id);
        return view('pegawai.skp.edit_periode', compact('data'));
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
            Skp_periode::find($id)->update($attr);
            toastr()->success('Periode Berhasil Di Simpan');
            return redirect('/pegawai/skp/rencana-kegiatan');
        }
    }

    public function storeSkp(Request $req, $id)
    {
        $attr = $req->all();
        $satuan_ak = str_replace(',', '.', $req->satuan_ak);
        $kuantitas = str_replace(',', '.', $req->satuan_ak);
        $attr['skp_periode_id'] = $id;
        $attr['ak'] = (float)$kuantitas * (float)$satuan_ak;
        Skp::create($attr);
        toastr()->success('SKP Berhasil Di Simpan');
        return back();
    }

    public function viewPeriode($id)
    {
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp_periode::findOrFail($id);
        if ($pegawai_id != $u->pegawai_id) {
            toastr()->error('Terdeteksi Percobaan Tindakan Penyalahgunaan, Akan dimasukkan ke History Keamanan', 'Authorize');
            return back();
        }

        $data = $u->skp()->paginate(10);
        return view('pegawai.skp.detail', compact('data', 'id'));
    }

    public function deletePeriode($id)
    {
        try {
            Skp_periode::findOrFail($id)->delete();
            toastr()->success('Periode Berhasil Di Hapus');
            return back();
        } catch (\Exception $e) {
            toastr()->error('Periode Tidak Bisa Di Hapus Karena ada SKP Di Dalamnya');
            return back();
        }
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
            Skp_periode::create($attr);
            toastr()->success('Periode Berhasil Di Simpan');
        }

        return back();
    }

    public function add()
    {
        return view('pegawai.skp.create');
    }

    public function store(Request $req)
    {
        $attr = $req->all();
        $attr['jabatan_id'] = Auth::user()->pegawai->jabatan_id;
        Skp::create($attr);
        toastr()->success('SKP Berhasil Di Simpan');
        return redirect('pegawai/skp/rencana-kegiatan');
    }

    public function edit($id, $periode_id)
    {
        $data = Skp::find($id);
        return view('pegawai.skp.edit', compact('data', 'periode_id'));
    }
    public function updateSkp(Request $req, $id, $periode_id)
    {

        $attr = $req->all();
        $attr['ak'] = $req->kuantitas * $req->satuan_ak;

        Skp::find($id)->update($attr);
        toastr()->success('SKP Berhasil Di Update');
        return redirect('pegawai/skp/rencana-kegiatan/periode/view/' . $periode_id);
    }

    public function delete($id_kegiatan, $id_skp)
    {
        try {
            $del = Skp::find($id_kegiatan)->delete();
            toastr()->success('SKP Berhasil Di Hapus');
            return back();
        } catch (\Exception $e) {
            toastr()->error('SKP tidak bisa di hapus, karena terkait dengan aktivitas');
            return back();
        }
    }

    public function aktifkan($id)
    {
        $pegawai_id = Auth::user()->pegawai->id;
        $u = Skp_periode::findOrFail($id);
        if ($pegawai_id != $u->pegawai_id) {
            toastr()->error('Terdeteksi percobaan tindakan penyalahgunaan, Akan dimasukkan ke History Keamanan', 'Authorize');
            return back();
        }

        $check = Skp_periode::where('pegawai_id', Auth::user()->pegawai->id)->where('is_aktif', 1)->first();
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

    public function validasiSkp()
    {
        $data = Auth::user()->pegawai->jabatan->bawahan->load('pegawai')->map(function ($item) {
            $item->nama_pegawai = $item->pegawai == null ? '-' : $item->pegawai->nama;
            $item->skp_baru = $item->pegawai == null ? 0 : $item->pegawai->skp_periode->map(function ($item2) {
                return $item2->skp->where('validasi', null);
            })->collapse()->count();
            return $item;
        });

        return view('pegawai.skp.validasi', compact('data'));
    }

    public function viewSkp($id)
    {
        $id_periode = Pegawai::find($id)->skp_periode->pluck('id')->toArray();
        $data = Skp::whereIn('skp_periode_id', $id_periode)->orderBy('validasi', 'ASC')->paginate(10);
        $pegawai = Pegawai::find($id);
        return view('pegawai.skp.detail_validasi', compact('data', 'pegawai', 'id'));
    }

    public function setujuiSkp($id)
    {
        Skp::find($id)->update([
            'validasi' => 1,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Skp Disetujui');
        return back();
    }

    public function tolakSkp($id)
    {
        Skp::find($id)->update([
            'validasi' => 2,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->info('Skp Ditolak');
        return back();
    }

    public function accSemuaSkp($id)
    {
        $id_periode = Pegawai::find($id)->skp_periode->pluck('id')->toArray();
        $data = Skp::whereIn('skp_periode_id', $id_periode)->where('validasi', null)->get();
        if (count($data) == 0) {
            toastr()->info('Tidak ada SKP yang Disetujui');
            return back();
        } else {
            foreach ($data as $key => $item) {
                $item->update([
                    'validasi' => 1,
                    'validator' => Auth::user()->pegawai->id
                ]);
            }

            toastr()->success('Skp Disetujui');
            return back();
        }
    }
}
