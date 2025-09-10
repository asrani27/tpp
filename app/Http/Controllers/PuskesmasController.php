<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\Kelas;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\KunciTpp;
use App\RekapPlt;
use App\RekapTpp;
use App\Aktivitas;
use App\RekapCpns;
use Carbon\Carbon;
use App\Rspuskesmas;
use App\RekapReguler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    public function rekapitulasi($bulan, $tahun)
    {
        //$data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', Auth::user()->puskesmas->id)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('puskesmas.rekapitulasi.bulantahun', compact('bulan', 'tahun'));
    }
    public function kuncitpp_cpns($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['rs_puskesmas_id'] = Auth::user()->puskesmas->id;
        $param['jenis'] = 'cpns';

        DB::beginTransaction();

        try {
            $data = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $data->map(function ($item) {

                //PBK
                $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
                if ($item->dp_ta >= 6750) {
                    $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                    if ($item->dp_skp == 'kurang') {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                    } else {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                    }
                } else {
                    $item->pbk_aktivitas = 0;
                    $item->pbk_skp = 0;
                }
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (80 / 100) * (87 / 100));

                //PPK
                $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
                if ($item->dp_ta >= 6750) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == 'kurang') {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100) * (87 / 100));

                //PKK
                $item->pkk = $item->basic * ($item->p_kk / 100);
                $item->pkk_jumlah = round($item->pkk * (80 / 100) * (87 / 100));

                //PKP
                $item->pkp = $item->basic * ($item->p_kp / 100);
                $item->pkp_jumlah = round($item->pkp * (80 / 100) * (87 / 100));
                $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;

                $save = $item;
                $save->jumlah_pembayaran = $item->jumlah_pembayaran;
                $save->save();
                return $item;
            });
            $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('*')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->whereIn('nip', $data->pluck('nip'))
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            $pphTerutangData->map(function ($item) use ($data, $bulanTahunId) {
                $matchedItem = $data->firstWhere('nip', $item->nip);

                $tpp = $matchedItem ? $matchedItem->jumlah_pembayaran : 0;

                DB::connection('pajakasn')
                    ->table('pajak')
                    ->where('nip', $item->nip)
                    ->where('bulan_tahun_id', $bulanTahunId->id)
                    ->update(['tpp' => $tpp]);

                return $item;
            });
            KunciTpp::create($param);

            DB::commit();
            toastr()->success('Telah Di Kunci');
            return back();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->success($e);
            return back();
            // something went wrong
        }
    }
    public function kuncitpp($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['rs_puskesmas_id'] = Auth::user()->puskesmas->id;
        $param['jenis'] = 'puskesmas';

        DB::beginTransaction();

        try {
            $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $data->map(function ($item) {
                //PBK
                $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
                if ($item->dp_ta >= 6750) {
                    $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                    if ($item->dp_skp == 'kurang') {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                    } else {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                    }
                } else {
                    $item->pbk_aktivitas = 0;
                    $item->pbk_skp = 0;
                }
                if (Auth::user()->puskesmas->id == 8) {
                    $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (68 / 100));
                } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                    $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));
                } else {
                    $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (85 / 100));
                }

                //PPK
                $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
                if ($item->dp_ta >= 6750) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == 'kurang') {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
                if (Auth::user()->puskesmas->id == 8) {
                    $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (68 / 100));
                } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                    $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));
                } else {
                    $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (85 / 100));
                }

                //PKK
                $item->pkk = $item->basic * ($item->p_kk / 100);
                if (Auth::user()->puskesmas->id == 8) {
                    $item->pkk_jumlah = round($item->pkk * (68 / 100));
                } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                    $item->pkk_jumlah = round($item->pkk);
                } else {
                    $item->pkk_jumlah = round($item->pkk * (85 / 100));
                }

                //PKP
                $item->pkp = $item->basic * ($item->p_kp / 100);
                if (Auth::user()->puskesmas->id == 8) {
                    $item->pkp_jumlah = round($item->pkp * (68 / 100));
                } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                    $item->pkp_jumlah = round($item->pkp);
                } else {
                    $item->pkp_jumlah = round($item->pkp * (85 / 100));
                }

                $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
                $save = $item;
                $save->jumlah_pembayaran = $item->jumlah_pembayaran;
                $save->save();
                return $item;
            });
            $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('*')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->whereIn('nip', $data->pluck('nip'))
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            $pphTerutangData->map(function ($item) use ($data, $bulanTahunId) {
                $matchedItem = $data->firstWhere('nip', $item->nip);

                $tpp = $matchedItem ? $matchedItem->jumlah_pembayaran : 0;

                DB::connection('pajakasn')
                    ->table('pajak')
                    ->where('nip', $item->nip)
                    ->where('bulan_tahun_id', $bulanTahunId->id)
                    ->update(['tpp' => $tpp]);

                return $item;
            });
            KunciTpp::create($param);

            DB::commit();
            toastr()->success('Telah Di Kunci');
            return back();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            toastr()->success($e);
            return back();
            // something went wrong
        }
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
        $data = RekapTpp::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
                $pagu      = $basic_tpp * ($persen->persen_beban_kerja + $persen->persen_prestasi_kerja + $persen->persen_tambahan_beban_kerja) / 100;
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

        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;

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
                $disiplin_bk = (($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * ((40 / 100) * $absensi / 100));
                $bk_disiplin = $disiplin_bk < 0 ? 0 : $disiplin_bk;
                $bk_produktivitas = $menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * 0.6 : 0;
                $disiplin_pk = (($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100));
                $pk_disiplin = $disiplin_pk < 0 ? 0 : $disiplin_pk;
                $pk_produktivitas = $menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0;
                $kondisi_kerja = $item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100;
            }
            // 70 % untuk RS 
            if (Auth::user()->puskesmas->id == 8) {

                $pbk = round(($bk_disiplin + $bk_produktivitas) * (70 / 100));
                $ppk = round(($pk_disiplin + $pk_produktivitas) * (70 / 100));
                $pkk = round(($absensi == 0 ? 0 : $kondisi_kerja) * (70 / 100));
                $pkp = round($item->perhitungan_kelangkaan_profesi * (70 / 100));
            } else {
                $pbk = round(($bk_disiplin + $bk_produktivitas) * (87 / 100));
                $ppk = round(($pk_disiplin + $pk_produktivitas) * (87 / 100));
                $pkk = round(($absensi == 0 ? 0 : $kondisi_kerja) * (87 / 100));
                $pkp = round($item->perhitungan_kelangkaan_profesi * (87 / 100));
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

            $pembayaran = $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->pembayaran_kelangkaan_profesi;

            $item->update([
                //Jika Selisih Rp 1 sesuaikan dengan pagu 
                'pembayaran' => $pembayaran,
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
        RekapReguler::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }


    public function cpns_delete($bulan, $tahun, $id)
    {
        RekapCpns::find($id)->delete();
        toastr()->success('Berhasil Di Hapus');
        return back();
    }
    public function reguler($bulan, $tahun)
    {
        $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            if (Auth::user()->puskesmas->id == 8) {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (68 / 100));
            } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));
            } else {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (85 / 100));
            }

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            if (Auth::user()->puskesmas->id == 8) {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (68 / 100));
            } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));
            } else {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (85 / 100));
            }

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            if (Auth::user()->puskesmas->id == 8) {
                $item->pkk_jumlah = round($item->pkk * (68 / 100));
            } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                $item->pkk_jumlah = round($item->pkk);
            } else {
                $item->pkk_jumlah = round($item->pkk * (85 / 100));
            }

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            if (Auth::user()->puskesmas->id == 8) {
                $item->pkp_jumlah = round($item->pkp * (68 / 100));
            } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
                $item->pkp_jumlah = round($item->pkp);
            } else {
                $item->pkp_jumlah = round($item->pkp * (85 / 100));
            }
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });
        return view('puskesmas.rekapitulasi.reguler', compact('data', 'bulan', 'tahun'));
    }

    public function reguler_bpjs(Request $req)
    {
        $data = RekapReguler::find($req->id_rekap);

        $data->bpjs1 = $req->satu_persen;
        $data->bpjs4 = $req->empat_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
    }
    public function getJabatan(Request $req)
    {
        if ($req->searchTerm == null) {
            $data = null;
        } else {
            $data = Jabatan::where('rs_puskesmas_id', Auth::user()->puskesmas->id)->where('nama', 'LIKE', '%' . $req->searchTerm . '%')->get()->map(function ($item) {
                $item->kelas = $item->kelas->nama;
                return $item;
            })->take(10)->toArray();
            return json_encode($data);
        }
    }
    public function reguler_editjabatan(Request $req)
    {
        $jabatan = Jabatan::find($req->jabatan);
        $data = RekapReguler::find($req->j_rekap);
        $data->jabatan = $jabatan->nama;
        $data->jenis_jabatan = $jabatan->jenis_jabatan;
        $data->kelas = $jabatan->kelas->nama;
        $data->basic = 0;
        $data->p_bk = 0;
        $data->p_tbk = 0;
        $data->p_pk = 0;
        $data->p_kk = 0;
        $data->basic = 0;
        $data->pagu = 0;
        $data->jabatan_id = $jabatan->id;
        $data->save();
        toastr()->success('Berhasil Di Ubah');
        return back();
    }
    public function puskes_reguler_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', 34)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', Auth::user()->puskesmas->id);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = 34;
                $n->puskesmas_id     = $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id;
                $n->sekolah_id       = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
                $n->nip              = $item->nip;
                $n->nama             = $item->nama;
                $n->pangkat          = $item->pangkat == null ? null : $item->pangkat->nama;
                $n->golongan         = $item->pangkat == null ? null : $item->pangkat->golongan;
                $n->jabatan          = $item->jabatan == null ? null : $item->jabatan->nama;
                $n->jabatan_id       = $item->jabatan == null ? null : $item->jabatan->id;
                $n->jenis_jabatan    = $item->jabatan == null ? null : $item->jabatan->jenis_jabatan;
                $n->kelas            = $item->jabatan == null ? null : $item->jabatan->kelas->nama;
                $n->bulan            = $bulan;
                $n->tahun            = $tahun;
                $n->pph21            = $item->pangkat == null ? null : $item->pangkat->pph;
                $n->save();
            } else {
                if ($check->skpd_id == 34 || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => 34,
                        'puskesmas_id'  => $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id,
                        'sekolah_id'    => $item->jabatan == null ? null : $item->jabatan->sekolah_id,
                        'nip'           => $item->nip,
                        'nama'          => $item->nama,
                        'pangkat'       => $item->pangkat == null ? null : $item->pangkat->nama,
                        'golongan'      => $item->pangkat == null ? null : $item->pangkat->golongan,
                        'jabatan'       => $item->jabatan == null ? null : $item->jabatan->nama,
                        'jabatan_id'    => $item->jabatan == null ? null : $item->jabatan->id,
                        'jenis_jabatan' => $item->jabatan == null ? null : $item->jabatan->jenis_jabatan,
                        'kelas'         => $item->jabatan == null ? null : $item->jabatan->kelas->nama,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'pph21' => $item->pangkat == null ? null : $item->pangkat->pph,
                    ]);
                } else {
                }
            }
        }

        toastr()->success('Berhasil Memasukkan Pegawai');
        return back();
    }
    public function puskes_reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
        $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $item->update([
                'dp_aktivitas' => $aktivitas->sum('menit'),
                'dp_ct'        => $dp_ct,
                'dp_tl'        => $dp_tl,
                'dp_covid'     => $dp_co,
                'dp_diklat'    => $dp_di,
                'dp_cb'        => $cuti_bersama,
                'dp_ta'        => $menit_aktivitas,
                'dp_absensi'   => $absensi,
                'dp_skp'       => 'baik'
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }

    public function tarikter_cpns($bulan, $tahun)
    {
        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $data = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', 34)
                ->whereIn('nip', $data->pluck('nip'))
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            //dd($data, $pphTerutangData['198307112009032009']);
            $data->map(function ($item) use ($pphTerutangData) {
                $nip = $item->nip; // Asumsikan kolom NIP ada di `rekap_reguler`
                $item->pph_terutang = $pphTerutangData[$nip]->pph_terutang ?? 0;
                $item->bpjs1 = $pphTerutangData[$nip]->bpjs_satu_persen ?? 0;
                $item->bpjs4 = $pphTerutangData[$nip]->bpjs_empat_persen ?? 0;
                $item->save();
            });

            toastr()->success('berhasil di tarik');
            return back();
        }
    }
    public function tarikter($bulan, $tahun)
    {
        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', 34)
                ->whereIn('nip', $data->pluck('nip'))
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            //dd($data, $pphTerutangData['198307112009032009']);
            $data->map(function ($item) use ($pphTerutangData) {
                $nip = $item->nip; // Asumsikan kolom NIP ada di `rekap_reguler`
                $item->pph_terutang = $pphTerutangData[$nip]->pph_terutang ?? 0;
                $item->bpjs1 = $pphTerutangData[$nip]->bpjs_satu_persen ?? 0;
                $item->bpjs4 = $pphTerutangData[$nip]->bpjs_empat_persen ?? 0;
                $item->save();
            });

            toastr()->success('berhasil di tarik');
            return back();
        }
    }
    public function puskes_reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        foreach ($data as $item) {
            $persen = Jabatan::find($item->jabatan_id);
            if ($persen == null) {
                $basic = 0;
                $p_bk = 0;
                $p_tbk = 0;
                $p_pk = 0;
                $p_kk = 0;
                $p_kp = 0;
                $pagu = 0;
            } else {
                $basic     = Kelas::where('nama', $item->kelas)->first()->nilai;
                $p_bk      = $persen->persen_beban_kerja;
                $p_tbk     = $persen->persen_tambahan_beban_kerja;
                $p_pk      = $persen->persen_prestasi_kerja;
                $p_kk      = $persen->persen_kondisi_kerja;
                $p_kp      = $persen->persen_kelangkaan_profesi;

                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (85 / 100));
            }

            $item->update([
                'basic' => $basic,
                'p_bk'  => $p_bk,
                'p_tbk' => $p_tbk,
                'p_pk'  => $p_pk,
                'p_kk'  => $p_kk,
                'p_kp'  => $p_kp,
                'pagu'  => $pagu,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }
    public function rs_reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        foreach ($data as $item) {
            $persen = Jabatan::find($item->jabatan_id);
            if ($persen == null) {
                $basic = 0;
                $p_bk = 0;
                $p_tbk = 0;
                $p_pk = 0;
                $p_kk = 0;
                $p_kp = 0;
                $pagu = 0;
            } else {
                $basic     = Kelas::where('nama', $item->kelas)->first()->nilai;
                $p_bk      = $persen->persen_beban_kerja;
                $p_tbk     = $persen->persen_tambahan_beban_kerja;
                $p_pk      = $persen->persen_prestasi_kerja;
                $p_kk      = $persen->persen_kondisi_kerja;
                $p_kp      = $persen->persen_kelangkaan_profesi;

                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (68 / 100));
            }

            $item->update([
                'basic' => $basic,
                'p_bk'  => $p_bk,
                'p_tbk' => $p_tbk,
                'p_pk'  => $p_pk,
                'p_kk'  => $p_kk,
                'p_kp'  => $p_kp,
                'pagu'  => $pagu,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }
    public function getPegawai(Request $req)
    {
        if ($req->searchTerm == null) {
            $data = null;
        } else {
            $data = Pegawai::where('nama', 'LIKE', '%' . $req->searchTerm . '%')->orWhere('nip', 'LIKE', '%' . $req->searchTerm . '%')->get()->take(10)->toArray();
            return json_encode($data);
        }
    }
    public function reguler_tambahpegawai(Request $req, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($req->pegawai);
        $jabatan = Jabatan::find($req->jabatan);

        $check = RekapReguler::where('nip', $pegawai->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new RekapReguler;
            $n->skpd_id          = 34;
            $n->puskesmas_id     = $jabatan == null ? null : $jabatan->rs_puskesmas_id;
            $n->sekolah_id       = $jabatan == null ? null : $jabatan->sekolah_id;
            $n->nip              = $pegawai->nip;
            $n->nama             = $pegawai->nama;
            $n->pangkat          = $pegawai->pangkat == null ? null : $pegawai->pangkat->nama;
            $n->golongan         = $pegawai->pangkat == null ? null : $pegawai->pangkat->golongan;
            $n->jabatan          = $jabatan == null ? null : $jabatan->nama;
            $n->jabatan_id       = $jabatan == null ? null : $jabatan->id;
            $n->jenis_jabatan    = $jabatan == null ? null : $jabatan->jenis_jabatan;
            $n->kelas            = $jabatan == null ? null : $jabatan->kelas->nama;
            $n->bulan            = $bulan;
            $n->tahun            = $tahun;
            $n->pph21            = $pegawai->pangkat == null ? null : $pegawai->pangkat->pph;
            $n->save();
            toastr()->success('Berhasil Di Tambahkan');
        } else {

            $skpd = Skpd::find($check->skpd_id);
            $puskesmas = Rspuskesmas::find($check->puskesmas_id);

            toastr()->error('Data Sudah Di Rekap di ' . $skpd->nama . ' - ' . $puskesmas->nama);
        }
        return back();
    }

    public function cpns($bulan, $tahun)
    {
        $data = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (80 / 100) * (87 / 100));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100) * (87 / 100));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (80 / 100) * (87 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * (80 / 100) * (87 / 100));
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //dd($item->jumlah_pembayaran, $item->pbk_jumlah, $item->ppk_jumlah);
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->pph21 - $item->bpjs1;
            return $item;
        });
        return view('puskesmas.rekapitulasi.cpns', compact('data', 'bulan', 'tahun'));
    }
    public function cpns_mp($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', 34)->where('is_aktif', 1)->where('status_pns', 'cpns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', Auth::user()->puskesmas->id)->where('rs_puskesmas_id', '!=', 8)->where('rs_puskesmas_id', '!=', 37)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapCpns::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapCpns;
                $n->skpd_id          = 34;
                $n->puskesmas_id     = $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id;
                $n->sekolah_id       = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
                $n->nip              = $item->nip;
                $n->nama             = $item->nama;
                $n->pangkat          = $item->pangkat == null ? null : $item->pangkat->nama;
                $n->golongan         = $item->pangkat == null ? null : $item->pangkat->golongan;
                $n->jabatan          = $item->jabatan == null ? null : $item->jabatan->nama;
                $n->jabatan_id       = $item->jabatan == null ? null : $item->jabatan->id;
                $n->jenis_jabatan    = $item->jabatan == null ? null : $item->jabatan->jenis_jabatan;
                $n->kelas            = $item->jabatan == null ? null : $item->jabatan->kelas->nama;
                $n->bulan            = $bulan;
                $n->tahun            = $tahun;
                $n->pph21            = $item->pangkat == null ? null : $item->pangkat->pph;
                $n->save();
            } else {
                if ($check->skpd_id == 34 || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => 34,
                        'puskesmas_id'  => $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id,
                        'sekolah_id'    => $item->jabatan == null ? null : $item->jabatan->sekolah_id,
                        'nip'           => $item->nip,
                        'nama'          => $item->nama,
                        'pangkat'       => $item->pangkat == null ? null : $item->pangkat->nama,
                        'golongan'      => $item->pangkat == null ? null : $item->pangkat->golongan,
                        'jabatan'       => $item->jabatan == null ? null : $item->jabatan->nama,
                        'jabatan_id'    => $item->jabatan == null ? null : $item->jabatan->id,
                        'jenis_jabatan' => $item->jabatan == null ? null : $item->jabatan->jenis_jabatan,
                        'kelas'         => $item->jabatan == null ? null : $item->jabatan->kelas->nama,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'pph21' => $item->pangkat == null ? null : $item->pangkat->pph,
                    ]);
                } else {
                }
            }
        }

        toastr()->success('Berhasil Memasukkan Pegawai');
        return back();
    }
    public function cpns_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
        $data = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $item->update([
                'dp_aktivitas' => $aktivitas->sum('menit'),
                'dp_ct'        => $dp_ct,
                'dp_tl'        => $dp_tl,
                'dp_covid'     => $dp_co,
                'dp_diklat'    => $dp_di,
                'dp_cb'        => $cuti_bersama,
                'dp_ta'        => $menit_aktivitas,
                'dp_absensi'   => $absensi,
                'dp_skp'       => 'baik'
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function cpns_perhitungan($bulan, $tahun)
    {
        $data = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        foreach ($data as $item) {
            //$jabatan_id = Pegawai::where('nip', $item->nip)->first()->jabatan_id;
            $persen = Jabatan::find($item->jabatan_id);
            if ($persen == null) {
                $basic = 0;
                $p_bk = 0;
                $p_tbk = 0;
                $p_pk = 0;
                $p_kk = 0;
                $p_kp = 0;
                $pagu = 0;
            } else {
                $basic     = Kelas::where('nama', $item->kelas)->first()->nilai;
                $p_bk      = $persen->persen_beban_kerja;
                $p_tbk     = $persen->persen_tambahan_beban_kerja;
                $p_pk      = $persen->persen_prestasi_kerja;
                $p_kk      = $persen->persen_kondisi_kerja;
                $p_kp      = $persen->persen_kelangkaan_profesi;
                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (80 / 100) * (87 / 100));
            }

            $item->update([
                'basic' => $basic,
                'p_bk'  => $p_bk,
                'p_tbk' => $p_tbk,
                'p_pk'  => $p_pk,
                'p_kk'  => $p_kk,
                'p_kp'  => $p_kp,
                'pagu'  => $pagu,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }
    public function cpns_bpjs(Request $req)
    {
        $data = RekapCpns::find($req->id_rekap);

        $data->bpjs1 = $req->satu_persen;
        $data->bpjs4 = $req->empat_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
    }
    public function puskes_reguler_excel($bulan, $tahun)
    {
        $reguler = RekapReguler::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $cpns = RekapCpns::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //dd($cpns);
        $dataBulan = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun);
        $kinerjaBulan = $dataBulan->translatedFormat('F Y');
        $pembayaranBulan = $dataBulan->addMonth(1)->translatedFormat('F Y');
        //dd($reguler, $cpns);
        $filename = 'TPP_' . $bulan . '-' . $tahun . '-' . Carbon::now()->format('H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        if (Auth::user()->puskesmas->id == 8) {
            $path = public_path('/excel/rumahsakit.xlsx');
        } elseif (Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
            $path = public_path('/excel/ifk.xlsx');
        } else {
            $path = public_path('/excel/perpuskes2.xlsx');
        }
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($path);

        //sheet reguler
        $spreadsheet->getSheetByName('REGULER')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('REGULER')->setCellValue('A3', strtoupper(Auth::user()->puskesmas->nama));
        $contentRow = 8;
        foreach ($reguler as $key => $item) {
            $spreadsheet->getSheetByName('REGULER')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('REGULER')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('AI' . $contentRow, ($item->pph_terutang));
            $spreadsheet->getSheetByName('REGULER')->setCellValue('AJ' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 952 - $rowMulaiHapus;

        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('REGULER')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('REGULER')->setCellValue('AI' . $contentRow, $sumAI);

        if (Auth::user()->puskesmas->id == 8) {
            $spreadsheet->getSheetByName('CPNS')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
            $spreadsheet->getSheetByName('CPNS')->setCellValue('A3', strtoupper(Auth::user()->puskesmas->nama));
            $contentRowCpns = 8;
            foreach ($cpns as $key => $item) {
                $spreadsheet->getSheetByName('CPNS')->setCellValue('B' . $contentRowCpns, $item->nama);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('C' . $contentRowCpns, '\'' . $item->nip);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('D' . $contentRowCpns, $item->pangkat . '/' . $item->golongan);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('E' . $contentRowCpns, $item->jabatan);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('F' . $contentRowCpns, $item->jenis_jabatan);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('G' . $contentRowCpns, $item->kelas);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('I' . $contentRowCpns, $item->basic);

                $spreadsheet->getSheetByName('CPNS')->setCellValue('J' . $contentRowCpns, (($item->p_bk + $item->p_tbk) / 100));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('K' . $contentRowCpns, ($item->p_pk / 100));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('L' . $contentRowCpns, ($item->p_kk / 100));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('M' . $contentRowCpns, ($item->p_kp / 100));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('O' . $contentRowCpns, ($item->dp_absensi / 100));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('P' . $contentRowCpns, $item->dp_ta);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('Q' . $contentRowCpns, $item->dp_skp);
                $spreadsheet->getSheetByName('CPNS')->setCellValue('AI' . $contentRowCpns, ($item->pph_terutang));
                $spreadsheet->getSheetByName('CPNS')->setCellValue('AJ' . $contentRowCpns, $item->bpjs1);
                $contentRowCpns++;
            }
        }
        // else {
        // }
        //sheet CPNS
        // if (Auth::user()->puskesmas->id == 8 || Auth::user()->puskesmas->id == 36 || Auth::user()->puskesmas->id == 37) {
        // } else {
        // }
        if (Auth::user()->puskesmas->id == 8) {
            //sheet PLT
            $dataPlt = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $spreadsheet->getSheetByName('PLT')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
            $spreadsheet->getSheetByName('PLT')->setCellValue('A3', strtoupper(Auth::user()->puskesmas->nama));
            $contentRowPlt = 8;

            //dd($dataPlt);
            foreach ($dataPlt as $key => $item) {
                $spreadsheet->getSheetByName('PLT')->setCellValue('B' . $contentRowPlt, $item->nama);
                $spreadsheet->getSheetByName('PLT')->setCellValue('C' . $contentRowPlt, '\'' . $item->nip);
                $spreadsheet->getSheetByName('PLT')->setCellValue('D' . $contentRowPlt, $item->pangkat . '/' . $item->golongan);
                $spreadsheet->getSheetByName('PLT')->setCellValue('E' . $contentRowPlt, $item->jabatan . '/ Plt. ' . $item->jabatan_plt);
                $spreadsheet->getSheetByName('PLT')->setCellValue('F' . $contentRowPlt, $item->jenis_jabatan);
                $spreadsheet->getSheetByName('PLT')->setCellValue('G' . $contentRowPlt, $item->kelas);
                $spreadsheet->getSheetByName('PLT')->setCellValue('I' . $contentRowPlt, $item->basic);

                $spreadsheet->getSheetByName('PLT')->setCellValue('J' . $contentRowPlt, (($item->p_bk + $item->p_tbk) / 100));
                $spreadsheet->getSheetByName('PLT')->setCellValue('K' . $contentRowPlt, ($item->p_pk / 100));
                $spreadsheet->getSheetByName('PLT')->setCellValue('L' . $contentRowPlt, ($item->p_kk / 100));
                $spreadsheet->getSheetByName('PLT')->setCellValue('M' . $contentRowPlt, ($item->p_kp / 100));
                if ($item->jenis_plt == '2') {
                    //=ROUND(SUM(S9:U9);0)
                    $formulaPagu = '=ROUND(I' . $contentRowPlt . '*(SUM(J' . $contentRowPlt . ':M' . $contentRowPlt . '))*20%,0)';
                    //$formulaBK = '=ROUND(SUM(S9:U9)*20%,0)';
                    $formulaBK = '=ROUND(SUM(S' . $contentRowPlt . ':U' . $contentRowPlt . ')*20%,0)';

                    $formulaPK = '=ROUND(SUM(W' . $contentRowPlt . ':Y' . $contentRowPlt . ')*20%,0)';
                    $formulaKK = '=ROUND(AA' . $contentRowPlt . '*20%,0)';
                    $spreadsheet->getSheetByName('PLT')->setCellValue('N' . $contentRowPlt, $formulaPagu);
                    $spreadsheet->getSheetByName('PLT')->setCellValue('V' . $contentRowPlt, $formulaBK);
                    $spreadsheet->getSheetByName('PLT')->setCellValue('Z' . $contentRowPlt, $formulaPK);
                    $spreadsheet->getSheetByName('PLT')->setCellValue('AB' . $contentRowPlt, $formulaKK);
                }
                $spreadsheet->getSheetByName('PLT')->setCellValue('O' . $contentRowPlt, ($item->dp_absensi / 100));
                $spreadsheet->getSheetByName('PLT')->setCellValue('P' . $contentRowPlt, $item->dp_ta);
                $spreadsheet->getSheetByName('PLT')->setCellValue('Q' . $contentRowPlt, $item->dp_skp);
                $spreadsheet->getSheetByName('PLT')->setCellValue('AI' . $contentRowPlt, $item->pph_terutang);
                $spreadsheet->getSheetByName('PLT')->setCellValue('AJ' . $contentRowPlt, $item->bpjs1);
                $contentRowPlt++;
            }
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function plt_delete($bulan, $tahun, $id)
    {
        RekapPlt::find($id)->delete();
        toastr()->success('Berhasil Dihapus');
        return back();
    }
    public function plt($bulan, $tahun)
    {
        $data = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }

            if ($item->jenis_plt == '2') {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * 20 / 100);
            } else {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));
            }

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }

            if ($item->jenis_plt == '2') {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            } else {

                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));
            }

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));

            if ($item->jenis_plt == '2') {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
            } else {

                $item->pkk_jumlah = $item->pkk;
            }

            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;

            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });
        //dd($data);
        return view('puskesmas.rekapitulasi.plt', compact('data', 'bulan', 'tahun'));
    }
    public function plt_tambahpegawai(Request $req, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($req->pegawai);
        $jabatan_definitif = Jabatan::find($req->jabatan_definitif);
        $jabatan_asli = Jabatan::find($pegawai->pegawai_id);
        $jabatan_plt = Jabatan::find($req->jabatan_plt);

        $check = RekapPlt::where('nip', $pegawai->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new RekapPlt;
            $n->skpd_id          = 34;
            $n->puskesmas_id          = Auth::user()->puskesmas->id;
            // $n->puskesmas_id     = $jabatan_definitif == null ? null : $jabatan_definitif->rs_puskesmas_id;
            $n->sekolah_id       = $jabatan_definitif == null ? null : $jabatan_definitif->sekolah_id;
            $n->nip              = $pegawai->nip;
            $n->nama             = $pegawai->nama;
            $n->pangkat          = $pegawai->pangkat == null ? null : $pegawai->pangkat->nama;
            $n->golongan         = $pegawai->pangkat == null ? null : $pegawai->pangkat->golongan;
            $n->jabatan          = $jabatan_definitif == null ? null : $jabatan_definitif->nama;
            $n->jenis_jabatan    = $jabatan_definitif == null ? null : $jabatan_definitif->jenis_jabatan;
            $n->jabatan_plt      = $jabatan_plt == null ? null : $jabatan_plt->nama;
            $n->jabatan_plt_id    = $jabatan_plt == null ? null : $jabatan_plt->id;
            if ($req->jenis_plt == '1') {
                $n->kelas        = $jabatan_plt == null ? null : $jabatan_plt->kelas->nama;
            }
            if ($req->jenis_plt == '2') {
                $n->kelas        = $jabatan_definitif == null ? null : $jabatan_definitif->kelas->nama;
            }
            $n->bulan            = $bulan;
            $n->tahun            = $tahun;
            $n->pph21            = $pegawai->pangkat == null ? null : $pegawai->pangkat->pph;
            $n->jenis_plt        = $req->jenis_plt;
            $n->save();
            toastr()->success('Berhasil Di Tambahkan');
        } else {
            $skpd = Skpd::find($check->skpd_id);
            toastr()->error('Data Sudah Di Rekap di' . $skpd->nama);
        }
        return back();
    }
    public function plt_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 420;
        $data = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $item->update([
                'dp_aktivitas' => $aktivitas->sum('menit'),
                'dp_ct'        => $dp_ct,
                'dp_tl'        => $dp_tl,
                'dp_covid'     => $dp_co,
                'dp_diklat'    => $dp_di,
                'dp_cb'        => $cuti_bersama,
                'dp_ta'        => $menit_aktivitas,
                'dp_absensi'   => $absensi,
                'dp_skp'       => 'baik'
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function plt_perhitungan($bulan, $tahun)
    {
        $data = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //dd($data);
        foreach ($data as $item) {
            $persen = Jabatan::find($item->jabatan_plt_id);

            if ($persen == null) {
                $basic = 0;
                $p_bk = 0;
                $p_tbk = 0;
                $p_pk = 0;
                $p_kk = 0;
                $p_kp = 0;
                $pagu = 0;
            } else {
                $basic     = Kelas::where('nama', $item->kelas)->first()->nilai;

                $p_bk      = $persen->persen_beban_kerja;
                $p_tbk     = $persen->persen_tambahan_beban_kerja;
                $p_pk      = $persen->persen_prestasi_kerja;
                $p_kk      = $persen->persen_kondisi_kerja;
                $p_kp      = $persen->persen_kelangkaan_profesi;
                if ($item->jenis_plt == '1') {
                    $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100);
                }
                if ($item->jenis_plt == '2') {
                    $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (20 / 100);
                }
            }

            $item->update([
                'basic' => $basic,
                'p_bk'  => $p_bk,
                'p_tbk' => $p_tbk,
                'p_pk'  => $p_pk,
                'p_kk'  => $p_kk,
                'p_kp'  => $p_kp,
                'pagu'  => $pagu,
            ]);
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }

    public function kuncitpp_plt($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['rs_puskesmas_id'] = Auth::user()->puskesmas->id;
        $param['jenis'] = 'plt';
        $data = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();


        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }

            if ($item->jenis_plt == '2') {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * 20 / 100);
            } else {
                $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));
            }

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == 'kurang') {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }

            if ($item->jenis_plt == '2') {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            } else {

                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));
            }

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));

            if ($item->jenis_plt == '2') {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
            } else {

                $item->pkk_jumlah = $item->pkk;
            }
            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;

            //simpan jumlah pembayaran
            $save = $item;
            $save->jumlah_pembayaran = $item->jumlah_pembayaran;
            $save->save();

            return $item;
        });

        KunciTpp::create($param);
        toastr()->success('Telah Di Kunci');
        return back();
    }

    public function tarikter_plt($bulan, $tahun)
    {

        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        //dd($bulanTahunId);
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $data = RekapPlt::where('puskesmas_id', Auth::user()->puskesmas->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('jenis_plt', 1)->orderBy('kelas', 'DESC')->get();

            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', 34)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });

            dd($data);

            $data->map(function ($item) use ($pphTerutangData) {
                $nip = $item->nip; // Asumsikan kolom NIP ada di `rekap_reguler`
                $item->pph_terutang = $pphTerutangData[$nip]->pph_terutang ?? 0;
                $item->bpjs1 = $pphTerutangData[$nip]->bpjs_satu_persen ?? 0;
                $item->bpjs4 = $pphTerutangData[$nip]->bpjs_empat_persen ?? 0;
                $item->save();
            });

            toastr()->success('berhasil di tarik');
            return back();
        }
    }
}
