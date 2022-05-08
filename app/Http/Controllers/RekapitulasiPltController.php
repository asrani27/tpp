<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Pegawai;
use App\RekapTpp;
use App\RekapTppPlt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekapitulasiPltController extends Controller
{
    public function index()
    {
        return view('admin.rekapitulasi_plt.index');
    }

    public function bulanTahun($bulan, $tahun)
    {
        $data = RekapTppPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        return view('admin.rekapitulasi_plt.bulantahun', compact('data', 'bulan', 'tahun'));
    }

    public function create($bulan, $tahun)
    {
        if (RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->first() == null) {
            toastr()->error('Harap Rekap TPP reguler(Non PLT) terlebih dahulu');
            return back();
        }
        $jabatanPlt = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get();
        return view('admin.rekapitulasi_plt.add_plt', compact('bulan', 'tahun', 'jabatanPlt'));
    }

    public function store(Request $req, $bulan, $tahun)
    {
        $pegawai = Pegawai::where('nip', $req->nip)->first();
        if ($pegawai == null) {
            toastr()->error('NIP Tidak ditemukan');
            $req->flash();
            return back();
        } else {
            if (RekapTpp::where('nip', $req->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first() != null) {
                toastr()->error('Harap Hapus Data NIP ini di TPP reguler(Non PLT) terlebih dahulu, menghindari data double');
                return back();
            }
            if (RekapTppPlt::where('nip', $req->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first() == null) {
                DB::beginTransaction();
                try {
                    //jabatan definitif
                    $param['nip'] = $req->nip;
                    $param['nama'] = $pegawai->nama;
                    $param['pegawai_id'] = $pegawai->id;
                    $param['pangkat_id'] = $pegawai->pangkat_id;
                    $param['pangkat'] = $pegawai->pangkat->nama;
                    $param['golongan'] = $pegawai->pangkat->golongan;
                    $param['jabatan_id'] = $pegawai->jabatan->id;
                    $param['jabatan'] = $pegawai->jabatan->nama;
                    $param['jenis_jabatan'] = $pegawai->jabatan->jenis_jabatan;
                    $param['kelas'] = $pegawai->jabatan->kelas->nama;
                    $param['bulan'] = $bulan;
                    $param['tahun'] = $tahun;
                    $param['skpd_id'] = Auth::user()->skpd->id;
                    $param['jenis_plt'] = $req->jenis_plt;

                    $s = RekapTppPlt::create($param);
                    //jabatan PLT
                    $jab = Jabatan::find($req->jabatan_plt_id);

                    $param2['rekap_tpp_plt_id'] = $s->id;
                    $param2['jabatan_id'] = $jab->id;
                    $param2['jabatan'] = $jab->nama;
                    $param2['jenis_jabatan'] = $jab->jenis_jabatan;
                    $param2['kelas'] = $jab->kelas->nama;
                    $param2['bulan'] = $bulan;
                    $param2['tahun'] = $tahun;

                    $p = RekapTppPlt::create($param2);

                    DB::commit();
                    toastr()->success('Berhasil Disimpan');
                    return redirect('/admin/rekapitulasi/plt/' . $bulan . '/' . $tahun);
                } catch (\Exception $e) {
                    DB::rollback();
                    $req->flash();
                    toastr()->error('Gagal');
                    return back();
                }
            } else {
                toastr()->error('NIP ini sudah direkap pada bulan ini');
                $req->flash();
                return back();
            }
        }
    }

    public function perhitungan($bulan, $tahun)
    {
        toastr()->info('Dalam Tahap Pengembangan Selesai pada pukul 16:00 WITA');
        return back();
    }

    public function pembayaran($bulan, $tahun)
    {
        toastr()->info('Dalam Tahap Pengembangan Selesai pada pukul 16:00 WITA');
        return back();
    }

    public function delete($bulan, $tahun, $id)
    {
        RekapTppPlt::find($id)->delete();
        toastr()->success('Berhasil Dihapus');
        return back();
    }
}
