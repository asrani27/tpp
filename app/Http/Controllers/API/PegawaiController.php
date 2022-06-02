<?php

namespace App\Http\Controllers\API;

use App\Skpd;
use App\Pegawai;
use App\Aktivitas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RekapTpp;

class PegawaiController extends Controller
{

    public function allpegawai()
    {
        $pegawai = Pegawai::with('jabatan', 'pangkat')->get();

        $data['message_error'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $pegawai;
        $data['jumlah']        = count($pegawai);
        return response()->json($data);
    }
    public function pegawai($nip)
    {
        $pegawai = Pegawai::with('jabatan', 'pangkat')->where('nip', $nip)->first();
        if ($pegawai == null) {
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            $data['message_error'] = 200;
            $data['message']       = 'data ditemukan';
            $data['data']          = $pegawai;
            return response()->json($data);
        }
    }

    public function aktivitaspegawai($nip, $bulan, $tahun)
    {
        $pegawai = Pegawai::where('nip', $nip)->first();
        if ($pegawai == null) {
            $data['message_error'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            $kehadiran = RekapTpp::where('nip', $pegawai->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $data['message_error'] = 200;
            $data['message']       = 'data ditemukan';
            $data['ja']          = $aktivitas->count();
            $data['ma']          = $aktivitas->sum('menit');
            $data['k']           = $kehadiran;
            return response()->json($data);
        }
    }

    public function pegawaiSkpd($id)
    {
        $skpd_id = Skpd::where('kode_skpd', $id)->first()->id;
        $pegawai = Pegawai::with('jabatan', 'pangkat')->where('skpd_id', $skpd_id)->get();
        $data['message_error'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $pegawai;
        $data['jumlah']        = count($pegawai);
        return response()->json($data);
    }
}
