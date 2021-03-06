<?php

namespace App\Http\Controllers;

use App\Cuti;
use App\Skpd;
use App\Kelas;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\RekapTpp;
use App\Aktivitas;
use App\Parameter;
use Carbon\Carbon;
use App\Exports\TppExport;
use App\Imports\BpjsImport;
use Illuminate\Http\Request;
use App\View_aktivitas_pegawai;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapitulasiController extends Controller
{
    public function excelPhpSpreadsheet($bulan, $tahun)
    {
        //dd($bulan, $tahun);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="myfile.xls"');
        header('Cache-Control: max-age=0');

        $sheet->setCellValue('A1', 'LAPORAN TPP ASN')->mergeCells('A1:V1');
        $sheet->setCellValue('A2', strtoupper(Auth::user()->skpd->nama))->mergeCells('A2:V2');
        $sheet->setCellValue('A3', 'BULAN : ' . strtoupper(convertBulan($bulan)) . ' ' . $tahun)->mergeCells('A3:V3');
        $sheet->setCellValue('A4', 'TANGGAL CETAK : ' . Carbon::now()->format('d-m-Y H:i:s'))->mergeCells('A4:V4');

        $styleJudul = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];

        $sheet->getStyle('A1:A4')->applyFromArray($styleJudul);

        $sheet->setCellValue('A6', 'NO')->mergeCells('A6:A9');
        $sheet->setCellValue('B6', 'NAMA')->mergeCells('B6:B9');
        $sheet->setCellValue('C6', 'NIP')->mergeCells('C6:C9');

        $style1 = [
            'font' => [
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'fce4d6',
                ],
                'endColor' => [
                    'argb' => 'fce4d6',
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A6:A9')->applyFromArray($style1);
        $sheet->getStyle('B6:B9')->applyFromArray($style1);
        $sheet->getStyle('C6:C9')->applyFromArray($style1);
        $sheet->getStyle('D6:D9')->applyFromArray($style1);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

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
                    if ($check->skpd_id == null) {
                        $jabatan = Jabatan::find($req->jabatan);

                        $check->update([
                            'skpd_id' => Auth::user()->skpd->id,
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
                        $skpd = $check->skpd->nama;
                        toastr()->error('Tidak Bisa Di tambahkan, TPP an.' . $check->nama . ' telah di rekap di ' . $skpd . ', Hubungi SKPD tersebut agar menghapus di laporan rekap');
                        return back();
                    }
                }
            }
        }
    }
    public function bulanTahun($bulan, $tahun)
    {
        // toastr()->error('Mohon maaf, sedang dalam pembaharuan fitur, akan kembali dalam  jam');
        // return back();
        $groupJab = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->groupBy('nama');

        $jabatan = [];

        foreach ($groupJab as $item) {
            $data['id'] = $item->first()->id;
            $data['nama'] = $item->first()->nama;
            $data['kelas'] = $item->first()->kelas->nama;
            array_push($jabatan, $data);
        }

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('status_pns', 'pns')->orderBy('kelas', 'DESC')->get();
        return view('admin.rekapitulasi.bulantahun', compact('data', 'bulan', 'tahun', 'jabatan'));
    }

    public function bulanTahunTU($bulan, $tahun)
    {
        // toastr()->error('Mohon maaf, ada perubahan format Rekap TPP, akan kembali dalam 24 jam');
        // return back();
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('status_pns', 'pns')->orderBy('kelas', 'DESC')->get();
        return view('admin.rekapitulasi.bulantahuntu', compact('data', 'bulan', 'tahun'));
    }
    public function masukkanPegawai($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', null)->where('sekolah_id', null);
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
                $n->status_pns        = $item->status_pns;
                $n->sekolah_id  = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
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

    public function masukkanPegawaiTU($bulan, $tahun)
    {
        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', null)->where('sekolah_id', '!=', null);
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
                $n->status_pns        = $item->status_pns;
                $n->sekolah_id  = $item->jabatan == null ? null : $item->jabatan->sekolah_id;
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
    public function updateJabatan($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();
        foreach ($data as $item) {
            $pegawai = Pegawai::where('nip', $item->nip)->first();
            if ($pegawai->jabatan == null) {
            } else {
                $item->update([
                    'jabatan_id' => $pegawai->jabatan_id,
                    'jabatan' => $pegawai->jabatan->nama,
                    'kelas' => $pegawai->jabatan->kelas->nama,
                    'basic_tpp' => $pegawai->jabatan->kelas->nilai,
                ]);
            }
        }
        toastr()->success('Berhasil Memasukkan Jabatan');
        return back();
    }

    public function hitungPersen($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->get();

        foreach ($data as $item) {
            $item->update([
                'persen' => $item->persenjabatan == null ? null : $item->persenjabatan->persentase_tpp,
                'tambahan_persen' => $item->persenjabatan == null ? null : $item->persenjabatan->tambahan_persen_tpp,
                'jumlah_persen' => $item->persenjabatan == null ? null : $item->persenjabatan->persentase_tpp + $item->persenjabatan->tambahan_persen_tpp,
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
            $check = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $absensi = 0;
            } else {
                $absensi = $check->persen_kehadiran;
            }
            if ($data->where('nip', $item->nip)->first() == null) {
            } else {
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
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'pns')->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $skpd = Auth::user()->skpd;
        return view('admin.rekapitulasi.bulanexcel', compact('data', 'skpd', 'bulan', 'tahun'));
        //        return Excel::download(new TppExport, 'tppexport.xlsx');
    }

    public function exceltu($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('status_pns', 'pns')->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $skpd = Auth::user()->skpd;
        return view('admin.rekapitulasi.bulanexcel', compact('data', 'skpd', 'bulan', 'tahun'));
        //        return Excel::download(new TppExport, 'tppexport.xlsx');
    }

    public function editJabatan($bulan, $tahun, $id)
    {
        $data = RekapTpp::find($id);
        $jabatan = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get();
        return view('admin.rekapitulasi.editjabatan', compact('bulan', 'tahun', 'id', 'data', 'jabatan'));
    }

    public function editJabatanLaporan(Request $req, $bulan, $tahun, $id)
    {

        $jabatan = Jabatan::find($req->jabatan_id);
        $rekapTpp = RekapTpp::find($id);

        $rekapTpp->update([
            'jabatan' => $jabatan->nama,
            'jabatan_id' => $jabatan->id,
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

        toastr()->success('Jabatan Berhasil Di Ubah');
        return redirect('/admin/rekapitulasi/' . $bulan . '/' . $tahun);
    }

    public function perhitungan($bulan, $tahun)
    {
        // menghitung kolom berwarna orange
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
                $pk_disiplin = round((($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100)));
                $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0);
                $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100);
            }

            $item->update([
                'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                'pembayaran_aktivitas' => $menit_aktivitas,

                'pembayaran_bk_disiplin' => $bk_disiplin,
                'pembayaran_bk_produktivitas' => $bk_produktivitas,

                'pembayaran_beban_kerja' => $bk_disiplin + $bk_produktivitas,
                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => $pk_disiplin + $pk_produktivitas,
                'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : $kondisi_kerja,
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

    public function perhitungantu($bulan, $tahun)
    {
        // menghitung kolom berwarna orange
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('sekolah_id', '!=', null)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $basic_tpp = Kelas::where('nama', $item->kelas)->first()->nilai;
            $pagu      = round($basic_tpp * (Jabatan::find($item->jabatan_id)->persen_beban_kerja + Jabatan::find($item->jabatan_id)->persen_prestasi_kerja) / 100);
            $disiplin  = $pagu * 40 / 100;
            $produktivitas  = $pagu * 60 / 100;
            $kondisi_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100);
            $tambahan_beban_kerja  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100);
            $kelangkaan_profesi  = round($basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100);
            $pagu_asn  = $disiplin + $produktivitas + $kondisi_kerja + $kelangkaan_profesi + $tambahan_beban_kerja;

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

    public function pembayarantu($bulan, $tahun)
    {
        //cuti bersama
        if ($bulan == '04' && $tahun == '2022') {
            $cuti_bersama = 420;
        } elseif ($bulan == '05' && $tahun == '2022') {
            $cuti_bersama = 420 * 3;
        } else {
            $cuti_bersama = 0;
        }

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('sekolah_id', '!=', null)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $pembayaran_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pembayaran_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;
            $pembayaran_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 420;

            $aktivitas = Aktivitas::where('pegawai_id', $item->pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
            $menit_aktivitas = $aktivitas->sum('menit') + $pembayaran_ct + $pembayaran_tl + $pembayaran_co + $pembayaran_di + $cuti_bersama;
            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $jabatan = Jabatan::find($item->jabatan_id);
            if ($jabatan == null) {
                $bk_disiplin = 0;
                $bk_produktivitas = 0;
                $pk_disiplin = 0;
                $pk_produktivitas = 0;
                $kondisi_kerja = 0;
            } else {
                $bk_disiplin = round((($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * ((40 / 100) * $absensi / 100)));
                $bk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_beban_kerja / 100) * 0.6 : 0);
                $pk_disiplin = round((($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100)));
                $pk_produktivitas = round($menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0);
                $kondisi_kerja = round($item->perhitungan_basic_tpp * $jabatan->tambahan_persen_tpp / 100);
            }

            $item->update([
                'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                'pembayaran_aktivitas' => $menit_aktivitas,
                'pembayaran_bk_disiplin' => $bk_disiplin,
                'pembayaran_bk_produktivitas' => $bk_produktivitas,
                'pembayaran_beban_kerja' => $bk_disiplin + $bk_produktivitas,
                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => $pk_disiplin + $pk_produktivitas,
                'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : $kondisi_kerja,
                'pembayaran_cutitahunan' => $pembayaran_ct,
                'pembayaran_cuti_bersama' => $cuti_bersama,
                'pembayaran_tugasluar' => $pembayaran_tl,
                'pembayaran_covid' => $pembayaran_co,
                'pembayaran_diklat' => $pembayaran_di,
                'pembayaran_at' => $aktivitas->sum('menit')
            ]);


            if ($jabatan == null) {
                $item->update([
                    'pembayaran' => 0,
                    'potongan_pph21' => 0,
                    'tpp_diterima' => 0,
                ]);
            } else {
                $pph21 = Pangkat::find($item->pangkat_id)->pph;
                $item->update([
                    'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->perhitungan_tambahan_beban_kerja + $item->perhitungan_kelangkaan_profesi,
                ]);

                $potongan_pph21 = round($item->pembayaran * ($pph21 / 100));

                $item->update([
                    'potongan_pph21' => $potongan_pph21,
                    'tpp_diterima' => $item->pembayaran - $potongan_pph21 - $item->potongan_bpjs_1persen,
                ]);
            }
        }
        toastr()->success('Berhasil di hitung');
        return back();
    }
    public function bpjs($bulan, $tahun)
    {
        return view('admin.rekapitulasi.bpjs', compact('bulan', 'tahun'));
    }

    public function uploadBpjs(Request $req, $bulan, $tahun)
    {
        $validator = Validator::make($req->all(), [
            'file' => 'mimes:xlx,xlsx,xls',
        ]);

        if ($validator->fails()) {
            toastr()->error('File Harus Excel');
            return back();
        }

        $file = $req->file;
        $reader = new Xlsx();
        $spreadsheet = $reader->load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        $data = [];
        foreach ($sheet as $item) {
            $attr['nip'] = $item[1];
            $attr['bpjs_1persen'] = (int)str_replace(',', '', $item[19]);
            $attr['bpjs_4persen'] = (int)str_replace(',', '', $item[20]);
            array_push($data, $attr);
        }

        foreach ($data as $d) {
            if ($d['nip'] == null) {
            } else {
                $check = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('nip', $d['nip'])->orderBy('kelas', 'DESC')->first();
                if ($check == null) {
                } else {
                    $check->update([
                        'potongan_bpjs_1persen' => $d['bpjs_1persen'],
                        'potongan_bpjs_4persen' => $d['bpjs_4persen'],
                    ]);
                }
            }
        }

        $rekaptpp = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($rekaptpp as $item) {
            $item->update([
                'tpp_diterima' => $item->pembayaran - ($item->potongan_pph21 + $item->potongan_bpjs_1persen),
            ]);
        }

        toastr()->success('BPJS Berhasil di upload');
        return redirect('/admin/rekapitulasi/' . $bulan . '/' . $tahun);
    }

    public function paguExcel($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->where('puskesmas_id', null)->get();
        $skpd = Auth::user()->skpd;

        return view('admin.rekapitulasi.paguexcel', compact('data', 'skpd', 'bulan', 'tahun'));
    }

    public function plt($bulan, $tahun, $id)
    {

        toastr()->success('Dalam Pengembangan, akan selesai pada pukul 21:00');
        return back();
        // $skpd = Skpd::get();
        // $data = RekapTpp::find($id);
        // return view('admin.rekapitulasi.plt', compact('skpd', 'data', 'bulan', 'tahun'));
    }

    public function updatebpjs(Request $req)
    {
        $data = RekapTpp::find($req->id_rekap);
        if ($data->skpd_id != Auth::user()->skpd->id) {
            toastr()->error('Bukan Data Milik SKPD Anda');
            return back();
        }
        $data->potongan_bpjs_1persen = $req->satu_persen;
        $data->potongan_bpjs_4persen = $req->empat_persen;
        $data->tpp_diterima = $data->pembayaran - $data->potongan_pph21 - $req->satu_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
    }

    public function puskesmasGabungan()
    {
        return view('admin.rekapitulasi.puskesmasgabungan');
    }

    public function PGbulanTahun($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return view('admin.rekapitulasi.PGbulantahun', compact('data', 'bulan', 'tahun'));
    }

    public function PGexcel($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //dd($data);
        return view('admin.rekapitulasi.PGexcel', compact('data', 'bulan', 'tahun'));
    }
}
