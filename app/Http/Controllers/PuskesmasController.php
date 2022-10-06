<?php

namespace App\Http\Controllers;

use App\Kelas;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\RekapTpp;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PuskesmasController extends Controller
{
    public function loginDinkes($uuid)
    {
        $session_id = session()->get('uuid');
        if ($uuid == $session_id) {
            if (Auth::loginUsingId(495)) {
                Session::forget('uuid');
                return redirect('/home/admin');
            }
        } else {
            toastr()->error('Kegagalan Sistem, harap hubungi programmer');
            return back();
        }
    }
    public function bulanTahun($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('puskesmas.rekapitulasi.bulantahun', compact('data', 'bulan', 'tahun'));
    }

    public function masukkanPegawai($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', 34)->where('is_aktif', 1)->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', Auth::user()->puskesmas->id)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapTpp::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapTpp;
                $n->nip         = $item->nip;
                $n->nama        = $item->nama;
                $n->pegawai_id  = $item->id;
                $n->pangkat_id  = $item->pangkat == null ? null : $item->pangkat->id;
                $n->pangkat     = $item->pangkat == null ? null : $item->pangkat->nama;
                $n->golongan    = $item->pangkat == null ? null : $item->pangkat->golongan;
                $n->jabatan_id  = $item->jabatan == null ? null : $item->jabatan->id;
                $n->jabatan     = $item->jabatan == null ? null : $item->jabatan->nama;
                $n->jenis_jabatan     = $item->jabatan == null ? null : $item->jabatan->jenis_jabatan;
                $n->kelas       = $item->jabatan == null ? null : $item->jabatan->kelas->nama;
                $n->skpd_id     = 34;
                $n->puskesmas_id     = Auth::user()->puskesmas->id;
                $n->bulan     = $bulan;
                $n->tahun     = $tahun;
                $n->sekolah_id  = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
                $n->save();
            } else {
                if ($check->puskesmas_id == Auth::user()->puskesmas->id || $check->puskesmas_id == null) {
                    $check->update([
                        'nip'           => $item->nip,
                        'nama'          => $item->nama,
                        'pegawai_id'    => $item->id,
                        'pangkat_id'    => $item->pangkat == null ? null : $item->pangkat->id,
                        'pangkat'       => $item->pangkat == null ? null : $item->pangkat->nama,
                        'golongan'      => $item->pangkat == null ? null : $item->pangkat->golongan,
                        'jabatan_id'    => $item->jabatan == null ? null : $item->jabatan->id,
                        'jabatan'       => $item->jabatan == null ? null : $item->jabatan->nama,
                        'jenis_jabatan' => $item->jabatan == null ? null : $item->jabatan->jenis_jabatan,
                        'kelas'         => $item->jabatan == null ? null : $item->jabatan->kelas->nama,
                        'sekolah_id'    => $item->jabatan == null ? null : $item->jabatan->sekolah_id,
                        'skpd_id' => 34,
                        'puskesmas_id' => Auth::user()->puskesmas->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ]);
                } else {
                }
            }
        }

        toastr()->success('Berhasil Memasukkan Pegawai');
        return back();
    }

    public function perhitungan($bulan, $tahun)
    {
        // menghitung kolom berwarna orange
        $data = RekapTpp::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->where('nip', '198401012009032018')->get();
        foreach ($data as $item) {

            $persen = Jabatan::find($item->jabatan_id);

            if ($persen == null) {
                $basic_tpp = 0;
                $pagu = 0;
                $disiplin = 0;
                $produktivitas = 0;
                $kondisi_kerja = 0;
                $tambahan_beban_kerja = 0;
                $kelangkaan_profesi = 0;
                $pagu_asn = 0;
            } else {
                $basic_tpp = Kelas::where('nama', $item->kelas)->first()->nilai;
                $pagu      = round($basic_tpp * ($persen->persen_beban_kerja + $persen->persen_prestasi_kerja + $persen->persen_tambahan_beban_kerja) / 100);
                $disiplin  = $pagu * 40 / 100;
                $produktivitas  = $pagu * 60 / 100;
                $kondisi_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100;
                $tambahan_beban_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100;
                $kelangkaan_profesi  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100;

                // 70 % untuk RS 
                if (Auth::user()->puskesmas->id == 8) {

                    $pagu_asn  = ($disiplin + $produktivitas + $kondisi_kerja + $kelangkaan_profesi) * 0.7;
                    //dd($pagu_asn, $disiplin, $produktivitas, $kondisi_kerja, $tambahan_beban_kerja, $kelangkaan_profesi, $disiplin + $produktivitas + $kondisi_kerja + $kelangkaan_profesi);
                } else {
                    $pagu_asn  = $disiplin + $produktivitas + $kondisi_kerja + $tambahan_beban_kerja + $kelangkaan_profesi;
                }
            }
            $item->update([
                'perhitungan_basic_tpp' => $basic_tpp,
                'perhitungan_pagu' => $pagu,
                'perhitungan_disiplin' => $disiplin,
                'perhitungan_produktivitas' => $produktivitas,
                'perhitungan_kondisi_kerja' => $kondisi_kerja,
                'perhitungan_tambahan_beban_kerja' => $tambahan_beban_kerja,
                'perhitungan_kelangkaan_profesi' => $kelangkaan_profesi,
                'perhitungan_pagu_tpp_asn' => $pagu_asn,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }

    public function pembayaran($bulan, $tahun)
    {
        if ($bulan == '04' && $tahun == '2022') {
            $cuti_bersama = 420;
        } elseif ($bulan == '05' && $tahun == '2022') {
            $cuti_bersama = 420 * 3;
        } else {
            $cuti_bersama = 0;
        }

        $data = RekapTpp::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $pembayaran_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pembayaran_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pembayaran_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;

            $aktivitas = Aktivitas::where('pegawai_id', $item->pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
            $menit_aktivitas = $aktivitas->sum('menit') + $pembayaran_ct + $pembayaran_tl + $pembayaran_co + $pembayaran_di + $cuti_bersama;
            $jabatan = Jabatan::find($item->jabatan_id);
            if ($presensi == null) {
                $absensi = 0;
            } else {
                if ($presensi->persen_kehadiran < 0) {
                    $absensi = 0;
                } else {
                    $absensi = $presensi->persen_kehadiran;
                }
            }

            if ($jabatan == null) {
                $bk_disiplin = 0;
                $bk_produktivitas = 0;
                $pk_disiplin = 0;
                $pk_produktivitas = 0;
                $kondisi_kerja = 0;
            } else {
                $disiplin_bk = round((($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * ((40 / 100) * $absensi / 100)));
                $bk_disiplin = $disiplin_bk < 0 ? 0 : $disiplin_bk;
                $bk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * 0.6 : 0);
                $disiplin_pk = round((($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100)));
                $pk_disiplin = $disiplin_pk < 0 ? 0 : $disiplin_pk;
                $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0);
                $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100);
            }
            // 70 % untuk RS 
            if (Auth::user()->puskesmas->id == 8) {

                $pbk = ($bk_disiplin + $bk_produktivitas) * (70 / 100);
                $ppk = ($pk_disiplin + $pk_produktivitas) * (70 / 100);
                $pkk = ($absensi == 0 ? 0 : $kondisi_kerja) * (70 / 100);
                $pkp = $item->perhitungan_kelangkaan_profesi * (70 / 100);
            } else {
                $pbk = ($bk_disiplin + $bk_produktivitas) * (87 / 100);
                $ppk = ($pk_disiplin + $pk_produktivitas) * (87 / 100);
                $pkk = ($absensi == 0 ? 0 : $kondisi_kerja) * (87 / 100);
                $pkp = $item->perhitungan_kelangkaan_profesi * (87 / 100);
            }
            $item->update([
                'pembayaran_absensi' => $absensi,
                'pembayaran_aktivitas' => $menit_aktivitas,
                'pembayaran_bk_disiplin' => $bk_disiplin,
                'pembayaran_bk_produktivitas' => $bk_produktivitas,
                'pembayaran_beban_kerja' => $pbk,
                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => $ppk,
                'pembayaran_kondisi_kerja' => $pkk,
                'pembayaran_kelangkaan_profesi' => $pkp,
                'pembayaran_cutitahunan' => $pembayaran_ct,
                'pembayaran_cuti_bersama' => $cuti_bersama,
                'pembayaran_tugasluar' => $pembayaran_tl,
                'pembayaran_covid' => $pembayaran_co,
                'pembayaran_diklat' => $pembayaran_di,
                'pembayaran_at' => $aktivitas->sum('menit')
            ]);

            $pph21 = Pangkat::find($item->pangkat_id)->pph;
            $item->update([
                'pembayaran' => $pbk + $ppk + $pkk + $pkp,
                // 'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->pembayaran_kelangkaan_profesi,
            ]);

            $potongan_pph21 = round($item->pembayaran * ($pph21 / 100));

            $item->update([
                'potongan_pph21' => $potongan_pph21,
                'tpp_diterima' => $item->pembayaran - $potongan_pph21 - $item->potongan_bpjs_1persen,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }
    public function excel($bulan, $tahun)
    {
        $data = RekapTpp::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('puskesmas.rekapitulasi.bulanexcel', compact('data', 'bulan', 'tahun'));
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

                $n->nip         = $req->nip;
                $n->nama        = $checkDataPegawai->nama;
                $n->pegawai_id  = $checkDataPegawai->id;
                $n->pangkat_id  = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->id;
                $n->pangkat     = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->nama;
                $n->golongan    = $checkDataPegawai->pangkat == null ? null : $checkDataPegawai->pangkat->golongan;
                $n->jabatan_id  = $jabatan->id;
                $n->jabatan     = $jabatan->nama;
                $n->jenis_jabatan     = $jabatan->jenis_jabatan;
                $n->kelas       = $jabatan->kelas->nama;

                $n->skpd_id = 34;
                $n->puskesmas_id = Auth::user()->puskesmas->id;
                $n->bulan = $req->bulan;
                $n->tahun = $req->tahun;
                $n->save();
                toastr()->success('Berhasil Di Tambahkan');
                return back();
            } else {
                if (Auth::user()->puskesmas->id == $check->puskesmas_id) {
                    toastr()->error('NIP Sudah Ada Di Laporan');
                    return back();
                } else {
                    if ($check->puskesmas_id == null) {
                        $jabatan = Jabatan::find($req->jabatan);

                        $check->update([
                            'skpd_id' => 34,
                            'puskesmas_id' => Auth::user()->puskesmas->id,
                            'jabatan' => $jabatan->nama,
                            'jenis_jabatan' => $jabatan->jenis_jabatan,
                            'kelas' => $jabatan->kelas->nama,

                            'perhitungan_basic_tpp' => 0,
                            'perhitungan_pagu' => 0,
                            'perhitungan_disiplin' => 0,
                            'perhitungan_produktivitas' => 0,
                            'perhitungan_kondisi_kerja' => 0,
                            'perhitungan_pagu_tpp_asn' => 0,
                            'pembayaran_absensi' => 0,
                            'pembayaran_aktivitas' => 0,
                            'pembayaran_bk_disiplin' => 0,
                            'pembayaran_bk_produktivitas' => 0,
                            'pembayaran_beban_kerja' => 0,
                            'pembayaran_pk_disiplin' => 0,
                            'pembayaran_pk_produktivitas' => 0,
                            'pembayaran_prestasi_kerja' => 0,
                            'pembayaran_kondisi_kerja' => 0,
                            'pembayaran' => 0,
                            'potongan_pph21' => 0,
                            'tpp_diterima' => 0,
                        ]);
                        toastr()->success('Berhasil Di Tambahkan');
                        return back();
                    } else {
                        $puskesmas = $check->puskesmas->nama;
                        toastr()->error('Tidak Bisa Di tambahkan, TPP an.' . $check->nama . ' telah di rekap di ' . $puskesmas . ', Hubungi RS/Puskesmas tersebut agar menghapus di laporan rekap');
                        return back();
                    }
                }
            }
        }
    }
    public function delete($bulan, $tahun, $id)
    {
        RekapTpp::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }
}
