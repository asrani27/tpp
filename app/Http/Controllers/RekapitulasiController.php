<?php

namespace App\Http\Controllers;

use App\Cuti;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\RekapTpp;
use App\Aktivitas;
use App\Parameter;
use Carbon\Carbon;
use App\Exports\TppExport;
use Illuminate\Http\Request;
use App\View_aktivitas_pegawai;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    public function index()
    {
        $tampil = false;
        return view('admin.rekapitulasi.index', compact('tampil'));
    }

    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function tambahPegawai(Request $req)
    {
        $checkDataPegawai = Pegawai::where('nip', $req->nip)->first();
        if ($checkDataPegawai == null) {
            toastr()->error('Tidak Ada data Di TPP');
            return back();
        } else {
            $check = RekapTpp::where('nip', $req->nip)->where('bulan', $req->bulan)->where('tahun', $req->tahun)->first();
            //dd($check);
            if ($check == null) {

                $jabatan = Jabatan::find($req->jabatan);

                $n = new RekapTpp;
                $n->nip = $req->nip;
                $n->nama = $checkDataPegawai->nama;
                $n->pangkat_id  = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->id;
                $n->pangkat     = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->nama;
                $n->golongan    = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->golongan;

                $n->jabatan     = $jabatan->nama;
                $n->kelas       = $jabatan->kelas->nama;
                $n->basic_tpp   = $jabatan->kelas->nilai;


                $n->skpd_id = Auth::user()->skpd->id;
                $n->bulan = $req->bulan;
                $n->tahun = $req->tahun;
                $n->save();
                toastr()->success('Berhasil Di Tambahkan');
                return back();
            } else {
                if (Auth::user()->skpd->id == $check->skpd_id) {
                    toastr()->error('NIP Sudah Ada Di Laporan');
                    return back();
                } else {
                    $jabatan = Jabatan::find($req->jabatan);
                    $check->update([
                        'skpd_id' => Auth::user()->skpd->id,
                        'jabatan' => $jabatan->nama,
                        'kelas' => $jabatan->kelas->nama,
                        'basic_tpp' => $jabatan->kelas->nilai,
                    ]);
                    toastr()->success('Berhasil Di Tambahkan');
                    return back();
                }
            }
        }
    }
    public function bulanTahun($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('pangkat_id', 'DESC')->get();
        return view('admin.rekapitulasi.bulantahun', compact('data', 'bulan', 'tahun'));
    }

    public function masukkanPegawai($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->get();
        foreach ($pegawai as $item) {
            $check = RekapTpp::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapTpp;
                $n->pegawai_id  = $item->id;
                $n->nip         = $item->nip;
                $n->nama        = $item->nama;
                $n->pangkat_id  = $item->pangkat == null ? null : $item->pangkat->id;
                $n->pangkat     = $item->pangkat == null ? null : $item->pangkat->nama;
                $n->golongan    = $item->pangkat == null ? null : $item->pangkat->golongan;
                $n->jabatan_id  = $item->jabatan == null ? null : $item->jabatan->id;
                $n->jabatan     = $item->jabatan == null ? null : $item->jabatan->nama;
                $n->kelas       = $item->jabatan == null ? null : $item->jabatan->kelas->nama;
                $n->basic_tpp   = $item->jabatan == null ? null : $item->jabatan->kelas->nilai;
                $n->skpd_id     = Auth::user()->skpd->id;
                $n->bulan     = $bulan;
                $n->tahun     = $tahun;
                $n->save();
            } else {
                $check->update([
                    'nip' => $item->nip,
                    'nama' => $item->nama,
                    'pangkat' => $item->pangkat == null ? null : $item->pangkat->nama,
                    'golongan' => $item->pangkat == null ? null : $item->pangkat->golongan,
                    'kelas' => $item->jabatan == null ? null : $item->jabatan->kelas->nama,
                    'basic_tpp' => $item->jabatan == null ? null : $item->jabatan->kelas->nilai,
                    'skpd_id' => Auth::user()->skpd->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ]);
            }
        }

        toastr()->success('Berhasil Memasukkan Pegawai');
        return back();
    }

    public function hitungPersen($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($data as $item) {
            $pegawai = Pegawai::where('nip', $item->nip)->first();
            $item->update([
                'persen' => $pegawai->jabatan == null ? null : $pegawai->jabatan->persentase_tpp,
                'tambahan_persen' => $pegawai->jabatan == null ? null : $pegawai->jabatan->tambahan_persen_tpp,
                'jumlah_persen' => $pegawai->jabatan == null ? null : $pegawai->jabatan->persentase_tpp + $pegawai->jabatan->tambahan_persen_tpp,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }

    public function totalPagu($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($data as $item) {
            $totalPagu = $item->basic_tpp * ($item->jumlah_persen / 100);
            $item->update([
                'total_pagu' => $totalPagu,
            ]);
        }
        toastr()->success('Total Pagu Di hitung');
        return back();
    }

    public function tarikPresensi($bulan, $tahun)
    {
        $data = DB::connection('presensi')->table('ringkasan')->where('bulan', $bulan)->where('tahun', $tahun)->where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->get();

        $data2 = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($data2 as $item) {
            if ($data->where('nip', $item->nip)->first() == null) {
                $absensi = 0;
            } else {
                $absensi = $data->where('nip', $item->nip)->first()->persen_kehadiran;
            }
            $item->update([
                'absensi' => $absensi,
                'total_absensi' => $item->total_pagu * ((40 / 100) * $absensi / 100),
            ]);
        }
        toastr()->success('Presensi Di hitung');
        return back();
    }

    public function aktivitas($bulan, $tahun)
    {
        $pegawai = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($pegawai as $item) {
            $aktivitas = Aktivitas::where('pegawai_id', $item->pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
            $cutiDiakui = Cuti::where('nip', $item->nip)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->sum('menit');
            if ($aktivitas->sum('menit') + $cutiDiakui >= 6750) {
                $total_aktivitas = $item->total_pagu * (60 / 100);
            } else {
                $total_aktivitas = 0;
            }
            $item->update([
                'aktivitas' => $aktivitas->sum('menit') + $cutiDiakui,
                'total_aktivitas' => $total_aktivitas,
            ]);
        }
        toastr()->success('Aktivitas Di hitung');
        return back();
    }

    public function pph21($bulan, $tahun)
    {
        $pegawai = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($pegawai as $item) {
            $pph21 = Pangkat::find($item->pangkat_id)->pph;
            $total_tpp = $item->total_absensi + $item->total_aktivitas;
            //dd($pph21, $total_tpp, $total_tpp * ($pph21 / 100));
            $item->update([
                'pph21' => $pph21,
                'total_pph21' => $total_tpp * ($pph21 / 100),
            ]);
        }
        toastr()->success('PPh 21 Di hitung');
        return back();
    }
    public function cetaktpp()
    {
        $month = request()->get('bulan');
        $year = request()->get('tahun');
        $button = request()->get('button');
        $bulantahun = Carbon::createFromFormat('m/Y', $month . '/' . $year)->isoformat('MMMM Y');

        //tampilkan
        $pegawai        = Pegawai::with('jabatan.kelas', 'pangkat')->where('skpd_id', $this->skpd_id())->orderBy('urutan', 'ASC')->get();
        $countPegawai   = $pegawai->count();
        $persentase_tpp = (float) Parameter::where('name', 'persentase_tpp')->first()->value;

        // $month = Carbon::now()->month;
        // $year  = Carbon::now()->year;
        $view_aktivitas = View_aktivitas_pegawai::where('tahun', $year)->where('bulan', $month)->get();
        if (count($view_aktivitas) == 0) {
            $tpp = false;
        } else {
            $tpp = true;
        }
        $capaianMenit = Parameter::where('name', 'menit')->first()->value;
        $data = $pegawai->map(function ($item) use ($view_aktivitas, $capaianMenit) {
            if ($item->jabatan == null) {
                $item->nama_jabatan   = null;
                $item->jenis_jabatan  = null;
                $item->nama_kelas     = null;
                $item->nama_pangkat   = null;
                $item->basic_tpp      = 0;
                $item->persentase_tpp = 0;
                $item->tambahan_persen_tpp  =  0;
                $item->jumlah_persentase    =  $item->persentase_tpp + $item->tambahan_persen_tpp;
                $item->total_pagu           =  0;
                $item->persen_disiplin      =  0;
                $item->total_disiplin       =  0;
                $item->persen_produktivitas =  0;
                $item->total_produktivitas  =  0;
                $item->total_tpp            =  0;
                $item->pph                  =  0;
                $item->pph_angka            =  0;
                $item->hukuman              =  0;
                $item->hukuman_angka        =  0;
                $item->tpp_diterima         =  0;
            } else {
                $item->nama_jabatan     = $item->jabatan->nama;
                $item->jenis_jabatan    = $item->jabatan->jenis_jabatan;
                $item->nama_pangkat     = $item->pangkat == null ? null : $item->pangkat->nama . ' (' . $item->pangkat->golongan . ')';
                $item->nama_kelas       = $item->jabatan->kelas->nama;
                $item->basic_tpp        = $item->jabatan->kelas->nilai;
                $item->persentase_tpp   = $item->jabatan->persentase_tpp == null ? 0 : $item->jabatan->persentase_tpp;
                $item->tambahan_persen_tpp  = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase    = $item->persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu           = ceil($item->basic_tpp * ($item->persentase_tpp + $item->tambahan_persen_tpp) / 100);
                $item->persen_disiplin      = $item->presensiMonth->first() == null ? 0 : $item->presensiMonth->first()->persen;
                $item->total_disiplin       =  $item->total_pagu * ((40 / 100) * $item->persen_disiplin / 100);
                $item->persen_produktivitas = $view_aktivitas->where('pegawai_id', $item->id)->first() == null ? 0 : (int) $view_aktivitas->where('pegawai_id', $item->id)->first()->jumlah_menit;
                if ($item->persen_produktivitas < $capaianMenit) {
                    $item->total_produktivitas =  0;
                } else {
                    $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                }
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;

                if ($item->pangkat == null) {
                    $item->pph   = 0;
                    $item->pph_angka =  0;
                } else {
                    $item->pph   = $item->pangkat->pph;
                    $item->pph_angka =  $item->total_tpp * $item->pph / 100;
                }

                $item->hukuman              =  $item->presensiMonth->first() == null ? 0 : $item->presensiMonth->first()->hukuman;
                $item->hukuman_angka        =  $item->hukuman * $item->total_tpp / 100;
                $item->tpp_diterima         =  $item->total_tpp - $item->pph_angka - $item->hukuman_angka;
            }
            return $item;
        });

        $tampil = true;
        if ($button == 1) {
            request()->flash();
            return view('admin.rekapitulasi.index', compact('data', 'persentase_tpp', 'month', 'year', 'capaianMenit', 'tampil', 'bulantahun', 'tpp'));
        } else {

            $pdf = PDF::loadView('admin.rekapitulasi.cetak', compact('data', 'persentase_tpp', 'month', 'year', 'capaianMenit', 'tampil', 'bulantahun', 'tpp'))->setPaper('legal', 'landscape');
            return $pdf->stream();
        }
    }

    public function delete($bulan, $tahun, $id)
    {
        RekapTpp::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }

    public function pdf($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('pangkat_id', 'DESC')->get();
        $skpd = Auth::user()->skpd;

        $pdf = PDF::loadView('admin.rekapitulasi.bulanpdf', compact('data', 'skpd', 'bulan', 'tahun'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function excel($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('pangkat_id', 'DESC')->get();
        $skpd = Auth::user()->skpd;
        return view('admin.rekapitulasi.bulanexcel', compact('data', 'skpd', 'bulan', 'tahun'));
        //        return Excel::download(new TppExport, 'tppexport.xlsx');
    }
}
