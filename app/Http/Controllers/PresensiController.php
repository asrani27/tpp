<?php

namespace App\Http\Controllers;

use App\BulanTahun;
use App\Pegawai;
use App\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::with('presensi')->where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan', 'ASC')->get()
            ->map(function ($item) use ($month, $year, $skpd_id) {
                $check = $item->presensi->where('bulan', $month)->where('tahun', $year)->where('skpd_id', $skpd_id)->first();
                if ($check == null) {
                    $item->persen = 100;
                    $item->hukuman = 0;
                } else {
                    $item->persen = $check == null ? 100 : $check->persen;
                    $item->hukuman = $check->hukuman;
                }

                return $item;
            });

        $bulanTahun = Carbon::now();
        return view('admin.presensi.index', compact('pegawai', 'bulanTahun'));
    }

    public function list()
    {
        //$presensiSkpd = Presensi::where('skpd_id', Auth::user()->skpd->id)->select("bulan", "tahun")->groupBy(['bulan','tahun'])->get()->sortByDesc('bulan')->sortByDesc('tahun');
        $presensiSkpd = BulanTahun::orderBy('id', 'DESC')->get();
        return view('admin.presensi.list', compact('presensiSkpd'));
    }

    public function edit()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::with('presensi')->where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan', 'ASC')->get()
            ->map(function ($item) use ($month, $year, $skpd_id) {
                $check = $item->presensi->where('bulan', $month)->where('tahun', $year)->where('skpd_id', $skpd_id)->first();
                if ($check == null) {
                    $item->persen = 100;
                    $item->hukuman = 0;
                } else {
                    $item->persen = $check == null ? 100 : $check->persen;
                    $item->hukuman = $check->hukuman;
                }
                return $item;
            });
        $data = Presensi::where('skpd_id', Auth::user()->id)->get();
        $bulanTahun = Carbon::now();
        return view('admin.presensi.edit', compact('data', 'pegawai', 'bulanTahun'));
    }

    public function update(Request $req)
    {
        DB::beginTransaction();
        try {
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $skpd_id = Auth::user()->skpd->id;
            $pegawai = Pegawai::with('presensi')->where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan', 'ASC')->get();
            $update = $pegawai->map(function ($item, $value) use ($month, $year, $skpd_id, $req) {
                $check = $item->presensi->where('bulan', $month)->where('tahun', $year)->where('skpd_id', $skpd_id)->first();
                if ($check == null) {
                    //create baru
                    $attr['pegawai_id'] = $item->id;
                    $attr['bulan'] = $month;
                    $attr['tahun'] = $year;
                    $attr['skpd_id'] = $skpd_id;
                    $attr['persen'] = $req->persen[$value];
                    $attr['hukuman'] = $req->hukuman[$value];
                    Presensi::create($attr);
                } else {
                    //update data
                    $check->update([
                        'persen' => $req->persen[$value],
                        'hukuman' => $req->hukuman[$value]
                    ]);
                }
                return $item;
            });

            DB::commit();
            toastr()->success('Data Berhasil di Update');
        } catch (\Exception $e) {
            DB::rollback();

            toastr()->error('Gagal Update Data');
        }
        return back();
    }

    public function editBulanTahun($bulan, $tahun)
    {
        $month = $bulan;
        $year = $tahun;
        $skpd_id = Auth::user()->skpd->id;
        $pegawai = Pegawai::with('presensi')->where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan', 'ASC')->get()
            ->map(function ($item) use ($month, $year, $skpd_id) {
                $check = $item->presensi->where('bulan', $month)->where('tahun', $year)->where('skpd_id', $skpd_id)->first();
                if ($check == null) {
                    $item->persen = 100;
                    $item->hukuman = 0;
                } else {
                    $item->persen = $check == null ? 100 : $check->persen;
                    $item->hukuman = $check->hukuman;
                }
                return $item;
            });

        $bulanTahun = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun);
        return view('admin.presensi.index', compact('pegawai', 'bulanTahun'));
    }

    public function updateBulanTahun(Request $req, $bulan, $tahun)
    {

        DB::beginTransaction();
        try {
            $month = $bulan;
            $year = $tahun;
            $skpd_id = Auth::user()->skpd->id;
            $pegawai = Pegawai::with('presensi')->where('skpd_id', Auth::user()->skpd->id)->orderBy('urutan', 'ASC')->get();
            $update = $pegawai->map(function ($item, $value) use ($month, $year, $skpd_id, $req) {
                $check = $item->presensi->where('bulan', $month)->where('tahun', $year)->where('skpd_id', $skpd_id)->first();

                if ($check == null) {
                    //create baru
                    $attr['pegawai_id'] = $item->id;
                    $attr['bulan'] = $month;
                    $attr['tahun'] = $year;
                    $attr['skpd_id'] = $skpd_id;
                    $attr['persen'] = $req->persen[$value];
                    $attr['hukuman'] = $req->hukuman[$value];
                    Presensi::create($attr);
                } else {
                    //update data
                    $check->update([
                        'persen' => $req->persen[$value],
                        'hukuman' => $req->hukuman[$value]
                    ]);
                }
                return $item;
            });

            DB::commit();
            toastr()->success('Data Berhasil di Update');
        } catch (\Exception $e) {
            DB::rollback();

            toastr()->error('Gagal Update Data');
        }
        return back();
    }
}
