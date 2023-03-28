<?php

namespace App\Http\Controllers;

use App\R_tpp;
use App\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function tpp()
    {
        $data = R_tpp::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.tpp', compact('data'));
    }

    public function aktivitas()
    {
        $nip = Auth::user()->username;
        $data = bulanTahun();
        $data->map(function ($item) use ($nip) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $nip)->where('bulan', $item->bulan)->where('tahun', $item->tahun)->first();
            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }
            $item->presensi = $absensi;
            $item->menit = totalMenit($item->bulan, $item->tahun);
            $item->cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $item->bulan)->whereYear('tanggal', $item->tahun)->get()->count() * 420;
            $item->cuti_tahunan = DB::connection('presensi')->table('detail_cuti')->where('nip', $nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $item->bulan)->whereYear('tanggal', $item->tahun)->get()->count() * 420;
            $item->tugas_luar = DB::connection('presensi')->table('detail_cuti')->where('nip', $nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $item->bulan)->whereYear('tanggal', $item->tahun)->get()->count() * 420;
            $item->covid = DB::connection('presensi')->table('detail_cuti')->where('nip', $nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $item->bulan)->whereYear('tanggal', $item->tahun)->get()->count() * 360;
            $item->diklat = DB::connection('presensi')->table('detail_cuti')->where('nip', $nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $item->bulan)->whereYear('tanggal', $item->tahun)->get()->count() * 420;
            return $item;
        });
        //dd($data);
        //$data = Aktivitas::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.aktivitas', compact('data'));
    }

    public function penghasilan()
    {
        $data = R_tpp::where('pegawai_id', Auth::user()->pegawai->id)->paginate(10);
        return view('pegawai.laporan.penghasilan', compact('data'));
    }

    public function detailAktivitas($bulan, $tahun)
    {
        return view('pegawai.laporan.detailaktivitas');
    }
}
