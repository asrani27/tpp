<?php

use App\Cuti;
use App\Lock;
use App\Skpd;
use App\Kelas;
use App\Eselon;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\RekapTpp;
use App\Aktivitas;
use App\Parameter;
use App\BulanTahun;
use Illuminate\Support\Facades\Auth;

function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}

function skpd()
{
    return Skpd::get();
}

function convertBulan($bulan)
{
    if ($bulan == '01') {
        $hasil = 'Januari';
    } elseif ($bulan == '02') {
        $hasil = 'Februari';
    } elseif ($bulan == '03') {
        $hasil = 'Maret';
    } elseif ($bulan == '04') {
        $hasil = 'April';
    } elseif ($bulan == '05') {
        $hasil = 'Mei';
    } elseif ($bulan == '06') {
        $hasil = 'Juni';
    } elseif ($bulan == '07') {
        $hasil = 'Juli';
    } elseif ($bulan == '08') {
        $hasil = 'Agustus';
    } elseif ($bulan == '09') {
        $hasil = 'September';
    } elseif ($bulan == '10') {
        $hasil = 'Oktober';
    } elseif ($bulan == '11') {
        $hasil = 'November';
    } elseif ($bulan == '12') {
        $hasil = 'Desember';
    }
    return $hasil;
}


function countSkpd()
{
    return Skpd::get()->count();
}

function countPegawai()
{
    return Pegawai::get()->count();
}

function countPegawaiSkpd($id)
{
    return Pegawai::where('skpd_id', $id)->get()->count();
}

function countJabatanSkpd($id)
{
    return Jabatan::where('skpd_id', $id)->get()->count();
}

function grafikSkpd()
{
    return Skpd::get()->pluck('kode_skpd');
}

function pangkat()
{
    return Pangkat::get();
}

function eselon()
{
    return Eselon::get();
}

function kelas()
{
    return Kelas::orderBy('id', 'DESC')->get();
}
function pegawai()
{
    return Pegawai::orderBy('nama', 'ASC')->paginate(10);
}

function detailSkpd($id)
{
    return Skpd::find($id);
}

function jabatan($skpd_id)
{
    $data = Jabatan::where('skpd_id', $skpd_id)->get();
    return $data;
}

function pegawaiSkpd($id)
{
    return Pegawai::where('skpd_id', $id)->paginate(10);
}

function parameter()
{
    return Parameter::get();
}

function currency($value)
{
    $hasil = number_format($value, 0, ',', '.');
    return $hasil;
}

function persentase_tpp()
{
    return Parameter::first()->persentase_tpp;
}

function bulanTahun()
{
    return BulanTahun::orderBy('id', 'DESC')->get();
}

function totalMenit($bulan, $tahun)
{
    $pegawai_id = Auth::user()->pegawai->id;
    $data = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
    return $data->sum('menit');
}

function totalAktivitas($bulan, $tahun)
{
    $pegawai_id = Auth::user()->pegawai->id;
    $data = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
    return count($data);
}

function totalAbsensi($bulan, $tahun)
{
    $pegawai_id = Auth::user()->pegawai->id;

    $check = RekapTpp::where('pegawai_id', $pegawai_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
    if ($check == null) {
        $hasil = 0;
    } else {
        $hasil = $check->absensi;
    }
    return $hasil;
}


function checkAtasan($atasan, $person)
{
    //check atasan
    if ($atasan == null) {
        $jabatan = '-';
        $nama = '-';
    } else {
        //check Atasan Apakah PLT
        if ($atasan->pegawaiPlt == null) {
            //Check Atasan apakah PLH
            if ($atasan->pegawaiPlh == null) {
                $jabatan = $atasan->nama;
                $nama = $atasan->pegawai == null ? '-' : $atasan->pegawai->nama;
            } else {
                //Jika Atasan == dengan Bawahan
                if ($atasan->pegawaiPlh->id == $person->id) {

                    if ($atasan->atasan == null) {
                        //Penilainya adalah Sekda
                        $sekda = Jabatan::where('sekda', 1)->first();
                        $jabatan = $sekda->nama;
                        $nama = $sekda->pegawai->nama;
                    } else {
                        if ($atasan->atasan->pegawai == null) {
                            //check jika atasan atasannya lagi PLT
                            if ($atasan->atasan->pegawaiPlt == null) {
                            } else {
                                $jabatan = 'Plt. ' . $atasan->atasan->nama;
                                $nama = $atasan->atasan->pegawaiPlt->nama;
                            }
                        } else {
                            $jabatan = $atasan->atasan->nama;
                            $nama = $atasan->atasan->pegawai->nama;
                        }
                    }
                } else {
                    $jabatan = $atasan->nama;
                    $nama = $atasan->pegawai->nama;
                }
            }
        } else {
            $jabatan = 'Plt. ' . $atasan->nama;
            $nama = $atasan->pegawaiPlt->nama;
        }
    }

    $data['nama'] = $nama;
    $data['jabatan'] = $jabatan;
    //dd($data);
    return $data;
}

function checkPlt($atasan)
{
    if ($atasan->pegawaiPlt == null) {
        $hasil = '';
    } else {
        $hasil = 'Plt.';
    }
    return $hasil;
}

function checkPlh($atasan)
{
    if ($atasan->pegawaiPlh == null) {
        $hasil = '';
    } else {
        $hasil = 'Plh.';
    }
    return $hasil;
}

function isKadis()
{
    $check = Auth::user()->pegawai->jabatan;
    if ($check == null) {
        $hasil = false;
    } else {
        if ($check->jabatan_id == null && $check->sekolah_id == null) {
            $hasil = true;
        } else {
            $hasil = false;
        }
    }

    return $hasil;
}

function lockSkpd($skpd_id, $bulan, $tahun)
{
    $check = Lock::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
    if ($check == null) {
        $hasil = null;
    } else {
        $hasil = $check->lock;
    }
    return $hasil;
}

function lockBy($skpd_id, $bulan, $tahun)
{
    $check = Lock::where('skpd_id', $skpd_id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
    if ($check == null) {
        $hasil = null;
    } else {
        $hasil = $check->oleh;
    }
    return $hasil;
}

function pegawaiByNip($param)
{
    return Pegawai::find($param);
}
