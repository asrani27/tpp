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

class RekapitulasiCpnsController extends Controller
{
    public function index()
    {
        return view('admin.rekapitulasi_cpns.index');
    }

    public function cpnsPuskesmas()
    {
        return view('admin.rekapitulasi_cpns.puskesmas.index');
    }

    public function editkelas(Request $req)
    {
        RekapTpp::find($req->rekap_id)->update([
            'kelas' => $req->kelas,
        ]);
        toastr()->success('Berhasil Diubah');
        return back();
    }

    public function masukkanCpnsPuskesmas($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('jabatan_id', '!=', null)->where('status_pns', 'cpns')->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', '!=', null)->where('rs_puskesmas_id', '!=', 8)->where('sekolah_id', null);
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
                $n->skpd_id     = Auth::user()->skpd->id;
                $n->bulan     = $bulan;
                $n->tahun     = $tahun;
                $n->status_pns     = $item->status_pns;
                $n->sekolah_id  = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
                $n->puskesmas_id  = $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id;
                $n->save();
            } else {
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
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
                        'puskesmas_id'  => $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id,
                        'skpd_id' => Auth::user()->skpd->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'status_pns' => $item->status_pns,
                    ]);
                } else {
                }
            }
        }

        toastr()->success('Berhasil Memasukkan Pegawai');
        return back();
    }

    public function cpnsPuskesmasBulanTahun($bulan, $tahun)
    {

        $groupJab = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->groupBy('nama');

        $jabatan = [];

        foreach ($groupJab as $item) {
            $data['id'] = $item->first()->id;
            $data['nama'] = $item->first()->nama;
            $data['kelas'] = $item->first()->kelas->nama;
            array_push($jabatan, $data);
        }


        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('admin.rekapitulasi_cpns.puskesmas.bulantahun', compact('data', 'bulan', 'tahun', 'jabatan'));
    }

    public function perhitunganCpnsPuskesmas($bulan, $tahun)
    {

        // menghitung kolom berwarna orange
        if (Auth::user()->skpd->id == 34) {
            //CPNS PUSKESMAS
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        } else {
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }

        //$data = RekapTpp::where('id', 15744)->get();
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

                $persentase = (($persen->persen_beban_kerja + $persen->persen_prestasi_kerja) + ($persen->persen_tambahan_beban_kerja == null ? 0 : $persen->persen_tambahan_beban_kerja)) / 100;

                $pagu      = round($basic_tpp * $persentase);
                $disiplin  = $pagu * (40 / 100);
                $produktivitas  = round($pagu * 60 / 100);
                $kondisi_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100);
                $tambahan_beban_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100);
                $kelangkaan_profesi  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100);
                $pagu_asn  = ($disiplin + $produktivitas + $kondisi_kerja + $kelangkaan_profesi) * (80 / 100) * (87 / 100);
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

    public function pembayaranCpnsPuskesmas($bulan, $tahun)
    {
        //cuti bersama
        if ($bulan == '04' && $tahun == '2022') {
            $cuti_bersama = 420;
        } elseif ($bulan == '05' && $tahun == '2022') {
            $cuti_bersama = 420 * 3;
        } else {
            $cuti_bersama = 0;
        }

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $absensi = $presensi->persen_kehadiran;
            }

            if ($jabatan == null) {
                $bk_disiplin = 0;
                $bk_produktivitas = 0;
                $pk_disiplin = 0;
                $pk_produktivitas = 0;
                $kondisi_kerja = 0;
            } else {
                $bk_disiplin = round((($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * ((40 / 100) * $absensi / 100)));
                $bk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * 0.6 : 0);
                $pk_disiplin = round((($item->perhitungan_basic_tpp * ($jabatan->persen_prestasi_kerja) / 100) * ((40 / 100) * $absensi / 100)));
                $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * ($jabatan->persen_prestasi_kerja) / 100) * 0.6 : 0);
                $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100);
            }

            $item->update([
                'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                'pembayaran_aktivitas' => $menit_aktivitas,

                'pembayaran_bk_disiplin' => $bk_disiplin,
                'pembayaran_bk_produktivitas' => $bk_produktivitas,
                'pembayaran_beban_kerja' => ($bk_disiplin + $bk_produktivitas) * (80 / 100) * (87 / 100),

                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => ($pk_disiplin + $pk_produktivitas) * (80 / 100) * (87 / 100),

                'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : $kondisi_kerja * (80 / 100) * (87 / 100),

                'pembayaran_cutitahunan' => $pembayaran_ct,
                'pembayaran_cuti_bersama' => $cuti_bersama,
                'pembayaran_tugasluar' => $pembayaran_tl,
                'pembayaran_covid' => $pembayaran_co,
                'pembayaran_diklat' => $pembayaran_di,
                'pembayaran_at' => $aktivitas->sum('menit')
            ]);

            $pph21 = Pangkat::find($item->pangkat_id)->pph;
            $item->update([
                'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->perhitungan_kelangkaan_profesi,
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

    public function bulanTahun($bulan, $tahun)
    {
        $groupJab = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->groupBy('nama');

        $jabatan = [];

        foreach ($groupJab as $item) {
            $data['id'] = $item->first()->id;
            $data['nama'] = $item->first()->nama;
            $data['kelas'] = $item->first()->kelas->nama;
            array_push($jabatan, $data);
        }


        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('admin.rekapitulasi_cpns.bulantahun', compact('data', 'bulan', 'tahun', 'jabatan'));
    }
    public function masukkanPegawai($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('jabatan_id', '!=', null)->where('status_pns', 'cpns')->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', '!=', null)->where('rs_puskesmas_id', '!=', 8)->where('sekolah_id', null);
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
                $n->skpd_id     = Auth::user()->skpd->id;
                $n->bulan     = $bulan;
                $n->tahun     = $tahun;
                $n->status_pns     = $item->status_pns;
                $n->sekolah_id  = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
                $n->puskesmas_id  = $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id;
                $n->save();
            } else {
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
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
                        'puskesmas_id'  => $item->jabatan == null ? null : $item->jabatan->rs_puskesmas_id,
                        'skpd_id' => Auth::user()->skpd->id,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                        'status_pns' => $item->status_pns,
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
        if (Auth::user()->skpd->id == 34) {
            //CPNS PUSKESMAS
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        } else {
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }

        //$data = RekapTpp::where('id', 15744)->get();
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

                $persentase = (($persen->persen_beban_kerja + $persen->persen_prestasi_kerja) + ($persen->persen_tambahan_beban_kerja == null ? 0 : $persen->persen_tambahan_beban_kerja)) / 100;

                $pagu      = round($basic_tpp * $persentase);
                $disiplin  = $pagu * (40 / 100);
                $produktivitas  = round($pagu * 60 / 100);
                $kondisi_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100);
                $tambahan_beban_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100);
                $kelangkaan_profesi  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100);
                $pagu_asn  = $disiplin + $produktivitas + $kondisi_kerja + $kelangkaan_profesi;
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
        //cuti bersama
        if ($bulan == '04' && $tahun == '2022') {
            $cuti_bersama = 420;
        } elseif ($bulan == '05' && $tahun == '2022') {
            $cuti_bersama = 420 * 3;
        } else {
            $cuti_bersama = 0;
        }

        if (Auth::user()->skpd->id == 34) {
            //CPNS PUSKESMAS
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        } else {
            $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }
        //$data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
                $absensi = $presensi->persen_kehadiran;
            }

            if ($jabatan == null) {
                $bk_disiplin = 0;
                $bk_produktivitas = 0;
                $pk_disiplin = 0;
                $pk_produktivitas = 0;
                $kondisi_kerja = 0;
            } else {
                $bk_disiplin = round((($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * ((40 / 100) * $absensi / 100)));
                $bk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * 0.6 : 0);
                $pk_disiplin = round((($item->perhitungan_basic_tpp * ($jabatan->persen_prestasi_kerja) / 100) * ((40 / 100) * $absensi / 100)));
                $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * ($jabatan->persen_prestasi_kerja) / 100) * 0.6 : 0);
                $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100);
            }

            if (Auth::user()->skpd->id == 34) {
                //CPNS PUSKESMAS
                $item->update([
                    'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                    'pembayaran_aktivitas' => $menit_aktivitas,

                    'pembayaran_bk_disiplin' => $bk_disiplin,
                    'pembayaran_bk_produktivitas' => $bk_produktivitas,
                    'pembayaran_beban_kerja' => ($bk_disiplin + $bk_produktivitas) * (80 / 100) * (87 / 100),

                    'pembayaran_pk_disiplin' => $pk_disiplin,
                    'pembayaran_pk_produktivitas' => $pk_produktivitas,
                    'pembayaran_prestasi_kerja' => ($pk_disiplin + $pk_produktivitas) * (80 / 100) * (87 / 100),

                    'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : $kondisi_kerja * (80 / 100) * (87 / 100),

                    'pembayaran_cutitahunan' => $pembayaran_ct,
                    'pembayaran_cuti_bersama' => $cuti_bersama,
                    'pembayaran_tugasluar' => $pembayaran_tl,
                    'pembayaran_covid' => $pembayaran_co,
                    'pembayaran_diklat' => $pembayaran_di,
                    'pembayaran_at' => $aktivitas->sum('menit')
                ]);
            } else {
                //CPNS DINAS
                $item->update([
                    'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                    'pembayaran_aktivitas' => $menit_aktivitas,

                    'pembayaran_bk_disiplin' => $bk_disiplin,
                    'pembayaran_bk_produktivitas' => $bk_produktivitas,
                    'pembayaran_beban_kerja' => ($bk_disiplin + $bk_produktivitas) * (80 / 100),

                    'pembayaran_pk_disiplin' => $pk_disiplin,
                    'pembayaran_pk_produktivitas' => $pk_produktivitas,
                    'pembayaran_prestasi_kerja' => ($pk_disiplin + $pk_produktivitas) * (80 / 100),

                    'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : $kondisi_kerja * (80 / 100),

                    'pembayaran_cutitahunan' => $pembayaran_ct,
                    'pembayaran_cuti_bersama' => $cuti_bersama,
                    'pembayaran_tugasluar' => $pembayaran_tl,
                    'pembayaran_covid' => $pembayaran_co,
                    'pembayaran_diklat' => $pembayaran_di,
                    'pembayaran_at' => $aktivitas->sum('menit')
                ]);
            }

            $pph21 = Pangkat::find($item->pangkat_id)->pph;
            $item->update([
                'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->perhitungan_kelangkaan_profesi,
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
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $skpd = Auth::user()->skpd;
        return view('admin.rekapitulasi_cpns.bulanexcel', compact('data', 'skpd', 'bulan', 'tahun'));
    }

    public function editkelasCpnsPuskesmas()
    {
        RekapTpp::find($req->rekap_id)->update([
            'kelas' => $req->kelas,
        ]);
        toastr()->success('Berhasil Diubah');
        return back();
    }

    public function excelCpnsPuskesmas($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'cpns')->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $skpd = Auth::user()->skpd;
        return view('admin.rekapitulasi_cpns.puskesmas.bulanexcel', compact('data', 'skpd', 'bulan', 'tahun'));
    }
}
