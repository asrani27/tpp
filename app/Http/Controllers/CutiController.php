<?php

namespace App\Http\Controllers;

use App\Cuti;
use App\Pegawai;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function admin()
    {
        $data = Pegawai::where('skpd_id', Auth::user()->skpd->id)->orderBy('pangkat_id', 'DESC')->paginate(20);
        return view('admin.cuti.index', compact('data'));
    }

    public function search()
    {
        $search = request()->get('search');
        $data   = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('nama', 'LIKE', '%' . $search . '%')
            ->orWhere(function ($query) use ($search) {
                $query->where('skpd_id', Auth::user()->skpd->id)->where('nip', 'LIKE', '%' . $search . '%');
            })

            ->orderBy('pangkat_id', 'DESC')->paginate(20);

        $data->appends(['search' => $search])->links();
        request()->flash();
        return view('admin.cuti.index', compact('data'));
    }

    public function detail($nip)
    {
        $data = Cuti::where('nip', $nip)->orderBy('id', 'DESC')->get();
        $pegawai = Pegawai::where('nip', $nip)->first();
        return view('admin.cuti.detail', compact('data', 'pegawai'));
    }

    public function tarik()
    {
        $data = Pegawai::where('skpd_id', Auth::user()->skpd->id)->get();
        foreach ($data as $item) {
            $cuti = DB::connection('presensi')->table('cuti')->where('nip', $item->nip)->get();
            foreach ($cuti as $cutis) {
                $period = CarbonPeriod::create($cutis->tanggal_mulai, $cutis->tanggal_selesai);
                foreach ($period as $date) {
                    $checkJenisPresensi = DB::connection('presensi')->table('pegawai')->where('nip', $cutis->nip)->first()->jenis_presensi;
                    if ($checkJenisPresensi == 1) {
                        if ($date->isWeekend()) {
                            $checkCuti = Cuti::where('nip', $cutis->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                            if ($checkCuti == null) {
                                $n = new Cuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                $n->menit = 0;
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            } else {
                            }
                        } else {
                            $checkCuti = Cuti::where('nip', $cutis->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                            if ($checkCuti == null) {
                                $n = new Cuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                if ($cutis->jenis_keterangan_id == 4 || $cutis->jenis_keterangan_id == 5 || $cutis->jenis_keterangan_id == 7 || $cutis->jenis_keterangan_id == 9) {
                                    $n->menit = 360;
                                } else {
                                    $n->menit = 0;
                                }
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            } else {
                                $n = $checkCuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                if ($cutis->jenis_keterangan_id == 4 || $cutis->jenis_keterangan_id == 5 || $cutis->jenis_keterangan_id == 7 || $cutis->jenis_keterangan_id == 9) {
                                    $n->menit = 360;
                                } else {
                                    $n->menit = 0;
                                }
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            }
                        }
                    } else {
                        if ($date->translatedFormat('l') == 'Minggu') {
                            $checkCuti = Cuti::where('nip', $cutis->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                            if ($checkCuti == null) {
                                $n = new Cuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                $n->menit = 0;
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            } else {
                            }
                        } else {
                            $checkCuti = Cuti::where('nip', $cutis->nip)->where('tanggal', $date->format('Y-m-d'))->first();
                            if ($checkCuti == null) {
                                $n = new Cuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                if ($cutis->jenis_keterangan_id == 4 || $cutis->jenis_keterangan_id == 5 || $cutis->jenis_keterangan_id == 7 || $cutis->jenis_keterangan_id == 9) {
                                    $n->menit = 360;
                                } else {
                                    $n->menit = 0;
                                }
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            } else {
                                $n = $checkCuti;
                                $n->nip = $cutis->nip;
                                $n->tanggal = $date->format('Y-m-d');
                                if ($cutis->jenis_keterangan_id == 4 || $cutis->jenis_keterangan_id == 5 || $cutis->jenis_keterangan_id == 7 || $cutis->jenis_keterangan_id == 9) {
                                    $n->menit = 360;
                                } else {
                                    $n->menit = 0;
                                }
                                $n->skpd_id = Auth::user()->skpd->id;
                                $n->jenis_keterangan_id = $cutis->jenis_keterangan_id;
                                $n->save();
                            }
                        }
                    }
                }
            }
        }

        toastr()->success('Berhasil Di Tarik');
        return back();
    }
}
