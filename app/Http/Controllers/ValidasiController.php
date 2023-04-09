<?php

namespace App\Http\Controllers;

use App\Skp;
use App\Jabatan;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        if ($this->user()->pegawai->jabatan == null) {
            toastr()->info('Tidak bisa melakukan validasi karena anda tidak memiliki jabatan, hub admin SKPD');
            return back();
        }

        $data1 = $this->user()->pegawai->jabatan->bawahan
            ->map(function ($item) {
                if ($item->pegawai == null) {
                    if ($item->pegawaiplt == null) {
                        $item->nama_pegawai = null;
                        $item->aktivitas_baru = 0;
                    } else {
                        $item->nama_pegawai   = $item->pegawaiplt->nama;
                        $item->aktivitas_baru = Aktivitas::where('pegawai_id', $item->pegawaiplt->id)->where('validasi', 0)->count();
                    }
                } else {
                    $item->nama_pegawai   = $item->pegawai->nama;
                    $item->aktivitas_baru = Aktivitas::where('pegawai_id', $item->pegawai->id)->where('validasi', 0)->count();
                }
                return $item;
            })->where('nama_pegawai', '!=', null);

        if ($this->user()->pegawai->jabatan->sekda == 1) {
            //dd('d');
            $data2 = Jabatan::where('jabatan_id', null)->where('sekda', null)->where('sekolah_id', null)->get()->map(function ($item) {
                $item->nama = $item->skpd->nama;
                if ($item->pegawai == null) {
                    if ($item->pegawaiplt == null) {
                        $item->nama_pegawai = null;
                        $item->aktivitas_baru = 0;
                    } else {
                        $item->nama_pegawai   = $item->pegawaiplt->nama;
                        $item->aktivitas_baru = Aktivitas::where('pegawai_id', $item->pegawaiplt->id)->where('validasi', 0)->count();
                    }
                } else {
                    $item->nama_pegawai   = $item->pegawai->nama;
                    $item->aktivitas_baru = Aktivitas::where('pegawai_id', $item->pegawai->id)->where('validasi', 0)->count();
                }
                return $item;
            });
            //dd($data2);
        } else {
            $data2 = collect([]);
        }

        $data = $data1->merge($data2);

        return view('pegawai.validasi.index', compact('data'));
    }

    public function accSemua($id)
    {
        $jabatan_saya = $this->user()->pegawai->jabatan;
        $jabatan = Jabatan::with('pegawai.aktivitas')->findOrFail($id);

        if ($jabatan->atasan == null) {
            //maka penilainya adalah sekda
            if ($jabatan->pegawai == null) {
                $pegawai_id = $jabatan->pegawaiplt->id;
            } else {
                $pegawai_id = $jabatan->pegawai->id;
            }

            $data = Aktivitas::where('pegawai_id', $pegawai_id)->where('validasi', 0)->get();

            $data->map(function ($item) {
                $item->update([
                    'validasi' => 1,
                    'validator' => Auth::user()->pegawai->id,
                ]);
                return $item;
            });
            toastr()->success('Semua Aktivitas Di Setujui');
            return back();
        } elseif ($jabatan_saya->id != $jabatan->atasan->id) {
            toastr()->error('Tidak Bisa Validasi , bukan bawahan anda', 'Authorize');
            return back();
        } else {
            //Cari Pegawai ID
            if ($jabatan->pegawai == null) {
                $pegawai_id = $jabatan->pegawaiplt->id;
            } else {
                $pegawai_id = $jabatan->pegawai->id;
            }

            $data = Aktivitas::where('pegawai_id', $pegawai_id)->where('validasi', 0)->get();

            $data->map(function ($item) {
                $item->update([
                    'validasi' => 1,
                    'validator' => Auth::user()->pegawai->id,
                ]);
                return $item;
            });
            toastr()->success('Semua Aktivitas Di Setujui');
            return back();
        }
    }

    public function view($id)
    {
        $check = Jabatan::find($id);
        if ($check->pegawai == null) {
            $data    = $check->pegawaiplt->aktivitas()->where('validasi', 0)->paginate(10);
            $pegawai = $check->pegawaiplt;
        } else {
            $data    = $check->pegawai->aktivitas()->where('validasi', 0)->paginate(10);
            $pegawai = $check->pegawai;
        }

        return view('pegawai.validasi.detail', compact('data', 'pegawai', 'id'));
    }

    public function keberatan()
    {
        $tingkat = Auth::user()->pegawai->jabatan->tingkat + 2;

        $jabatan = Jabatan::where('skpd_id', Auth::user()->pegawai->skpd_id)->where('tingkat', $tingkat)->get();

        $data = Aktivitas::where('keberatan', 1)->where('validator_keberatan', Auth::user()->pegawai->id)->get();


        return view('pegawai.validasi.keberatan', compact('data'));
    }
    public function setujuiKeberatan($id)
    {
        Aktivitas::find($id)->update([
            'validasi' => 1,
            'keberatan' => 3,
        ]);
        toastr()->success('Keberatan Di Setujui');
        return back();
    }
    public function tolakKeberatan($id)
    {
        Aktivitas::find($id)->update([
            'validasi' => 2,
            'keberatan' => 2,
        ]);
        toastr()->error('Keberatan Di Tolak');
        return back();
    }
    public function accAktivitas($id)
    {
        //check apakah aktivitas bawahan ini adalah bawahan ku
        // $jabatan_id = Aktivitas::find($id)->pegawai->jabatan->id;
        // $bawahan_ku = Auth::user()->pegawai->jabatan->bawahan;
        // $check = $bawahan_ku->where('id', $jabatan_id)->first();
        // if ($check == null) {
        //     toastr()->error('Pegawai Ini Bukan Bawahan Anda');
        //     return back();
        // }
        Aktivitas::findOrFail($id)->update([
            'validasi' => 1,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Setujui');
        return back();
    }

    public function tolakAktivitas($id)
    {
        Aktivitas::findOrFail($id)->update([
            'validasi' => 2,
            'validator' => Auth::user()->pegawai->id,
        ]);
        toastr()->success('Aktivitas Di Tolak');
        return back();
    }

    public function riwayat()
    {
        $data = Aktivitas::with('pegawai')->where('validator', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.validasi.riwayat', compact('data'));
    }
}
