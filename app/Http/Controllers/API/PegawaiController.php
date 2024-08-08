<?php

namespace App\Http\Controllers\API;

use App\Skpd;
use App\Pegawai;
use App\Aktivitas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RekapTpp;
use App\RencanaAksi;
use App\Skp2023;

class PegawaiController extends Controller
{
    public function rhk($nip)
    {
        $pegawai = Pegawai::where('nip', $nip)->first();
        $skp2023_id = Skp2023::where('pegawai_id', $pegawai->id)->where('is_aktif', 1)->first();
        if ($skp2023_id != null) {

            $data['message_code']  = 200;
            $data['message']       = 'data ditemukan';
            $data['data_pegawai']  = json_decode($skp2023_id->pn);
            $data['data_rhk']      = $skp2023_id->jf->map(function ($item) {
                $item['rencana_aksi'] = RencanaAksi::where('rhk_id', $item->id)->get()->map->only('id', 'triwulan', 'tahun', 'keterangan', 'realisasi', 'bukti_dukung', 'masalah');
                return $item->only('id', 'rhk', 'rencana_aksi');
            });
        } else {
            $data['message_code']  = 404;
            $data['message']       = 'data tidak ditemukan';
        }

        return response()->json($data);
    }
    public function allpegawai()
    {
        $pegawai = Pegawai::with('jabatan', 'pangkat')->get();

        $data['message_code']  = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $pegawai;
        $data['jumlah']        = count($pegawai);
        return response()->json($data);
    }
    public function pegawaibanjarmasin()
    {
        $pegawai = Pegawai::get();

        $list = [];
        foreach ($pegawai as $item) {
            $list[] = [
                'nip' => $item->nip,
                'nama' => $item->nama,
                'jabatan' => $item->jabatan == null ? null : $item->jabatan->nama,
                'skpd' => $item->skpd == null ? null : $item->skpd->nama,
                'alamat_kantor' => null,
                'goldarah' => $item->gol_darah,
            ];
        }

        $data['message_code'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $list;
        $data['jumlah']        = count($list);
        return response()->json($data);
    }
    public function pegawai($nip)
    {
        $pegawai = Pegawai::with('jabatan', 'pangkat')->where('nip', $nip)->first();
        if ($pegawai == null) {
            $data['message_code'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            $data['message_code'] = 200;
            $data['message']       = 'data ditemukan';
            $data['data']          = $pegawai;
            return response()->json($data);
        }
    }

    public function pegawaibynip($nip)
    {
        $pegawai = Pegawai::with('jabatan', 'pangkat')->where('nip', $nip)->first();
        if ($pegawai == null) {
            $data['message']       = 'data tidak ditemukan';
            return response()->json($data);
        } else {
            $data['nip'] = $pegawai->nip;
            $data['nama'] = $pegawai->nama;
            $data['jabatan'] = $pegawai->jabatan == null ? null : $pegawai->jabatan->nama;
            $data['skpd'] = $pegawai->skpd == null ? null : $pegawai->skpd->nama;
            return response()->json($data);
        }
    }

    public function aktivitaspegawai($nip, $bulan, $tahun)
    {
        $pegawai = Pegawai::where('nip', $nip)->first();
        if ($pegawai == null) {
            $data['message_code'] = 101;
            $data['message']       = 'username atau password kosong';
            $data['data']          = null;
            return response()->json($data);
        } else {
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
            $kehadiran = RekapTpp::where('nip', $pegawai->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $data['message_code'] = 200;
            $data['message']       = 'data ditemukan';
            $data['ja']          = $aktivitas->count();
            $data['ma']          = $aktivitas->sum('menit');
            $data['k']           = $kehadiran == null ? 0 : $kehadiran->pembayaran_absensi;
            return response()->json($data);
        }
    }

    public function pegawaiSkpd($id)
    {
        $skpd_id = Skpd::where('kode_skpd', $id)->first()->id;

        $pegawai = Pegawai::where('skpd_id', $skpd_id)->get()->map(function ($item) {
            $item->pangkat = $item->pangkat == null ? null : $item->pangkat;
            $item->nama_puskesmas = $item->jabatan == null ? null : $item->jabatan->puskesmas;
            return $item;
        });

        $data['message_code'] = 200;
        $data['message']       = 'data ditemukan';
        $data['data']          = $pegawai;
        $data['jumlah']        = count($pegawai);
        return response()->json($data);
    }
}
