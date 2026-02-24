<?php

namespace App\Http\Controllers;

use Log;
use App\Cuti;
use App\Skpd;
use App\Kelas;
use App\Jabatan;
use App\Pangkat;
use App\Pegawai;
use App\Skp2023;
use App\KunciTpp;
use App\RekapPlt;
use App\RekapTpp;
use App\Aktivitas;
use App\Parameter;
use App\RekapCpns;
use Carbon\Carbon;
use App\RekapReguler;
use App\Exports\TppExport;
use App\Imports\BpjsImport;
use Illuminate\Http\Request;
use App\View_aktivitas_pegawai;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
                $n->status_pns = $checkDataPegawai->status_pns;
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

        // $groupJab = Jabatan::where('skpd_id', Auth::user()->skpd->id)->get()->groupBy('nama');

        // $jabatan = [];

        // foreach ($groupJab as $item) {
        //     $data['id'] = $item->first()->id;
        //     $data['nama'] = $item->first()->nama;
        //     $data['kelas'] = $item->first()->kelas->nama;
        //     array_push($jabatan, $data);
        // }

        // $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('status_pns', 'pns')->orderBy('kelas', 'DESC')->get();
        return view('admin.rekap2023.tab', compact('bulan', 'tahun'));
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
                $pagu      = $basic_tpp * ($persen->persen_beban_kerja + $persen->persen_prestasi_kerja + $persen->persen_tambahan_beban_kerja) / 100;
                $disiplin  = $pagu * (40 / 100);
                $produktivitas  = $pagu * 60 / 100;
                $kondisi_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kondisi_kerja / 100;
                $tambahan_beban_kerja  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_tambahan_beban_kerja / 100;
                $kelangkaan_profesi  = $basic_tpp * Jabatan::find($item->jabatan_id)->persen_kelangkaan_profesi / 100;
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
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $pembayaran_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;

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
                $bk_disiplin = (($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * ((40 / 100) * $absensi / 100));
                $bk_produktivitas = $menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * ($jabatan->persen_beban_kerja + $jabatan->persen_tambahan_beban_kerja) / 100) * 0.6 : 0;
                $pk_disiplin = (($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * ((40 / 100) * $absensi / 100));
                $pk_produktivitas = $menit_aktivitas >= 6750 ? ($item->perhitungan_basic_tpp * $jabatan->persen_prestasi_kerja / 100) * 0.6 : 0;
                $kondisi_kerja = $item->perhitungan_basic_tpp * $jabatan->persen_kondisi_kerja / 100;
            }

            $item->update([
                'pembayaran_absensi' => $presensi == null ? null : $presensi->persen_kehadiran,
                'pembayaran_aktivitas' => $menit_aktivitas,

                'pembayaran_bk_disiplin' => $bk_disiplin,
                'pembayaran_bk_produktivitas' => $bk_produktivitas,

                'pembayaran_beban_kerja' => round($bk_disiplin + $bk_produktivitas),
                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => round($pk_disiplin + $pk_produktivitas),
                'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : round($kondisi_kerja),
                'pembayaran_cutitahunan' => $pembayaran_ct,
                'pembayaran_cuti_bersama' => $cuti_bersama,
                'pembayaran_tugasluar' => $pembayaran_tl,
                'pembayaran_covid' => $pembayaran_co,
                'pembayaran_diklat' => $pembayaran_di,
                'pembayaran_at' => $aktivitas->sum('menit')
            ]);

            $pph21 = Pangkat::find($item->pangkat_id)->pph;
            $item->update([
                'pembayaran' => $item->pembayaran_beban_kerja + $item->pembayaran_prestasi_kerja + $item->pembayaran_kondisi_kerja + $item->pembayaran_kelangkaan_profesi,
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
            $cuti_bersama = 360;
        } elseif ($bulan == '05' && $tahun == '2022') {
            $cuti_bersama = 360 * 3;
        } else {
            $cuti_bersama = 0;
        }

        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('sekolah_id', '!=', null)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $pembayaran_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pembayaran_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;

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
                'pembayaran_beban_kerja' => round($bk_disiplin + $bk_produktivitas),
                'pembayaran_pk_disiplin' => $pk_disiplin,
                'pembayaran_pk_produktivitas' => $pk_produktivitas,
                'pembayaran_prestasi_kerja' => round($pk_disiplin + $pk_produktivitas),
                'pembayaran_kondisi_kerja' => $absensi == 0 ? 0 : round($kondisi_kerja),
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

    // public function uploadBpjs(Request $req, $bulan, $tahun)
    // {
    //     $validator = Validator::make($req->all(), [
    //         'file' => 'mimes:xlx,xlsx,xls',
    //     ]);

    //     if ($validator->fails()) {
    //         toastr()->error('File Harus Excel');
    //         return back();
    //     }

    //     $file = $req->file;
    //     $reader = new Xlsx();
    //     $spreadsheet = $reader->load($file);
    //     $sheet = $spreadsheet->getActiveSheet()->toArray();

    //     $data = [];
    //     foreach ($sheet as $item) {
    //         $attr['nip'] = $item[1];
    //         $attr['bpjs_1persen'] = (int)str_replace(',', '', $item[19]);
    //         $attr['bpjs_4persen'] = (int)str_replace(',', '', $item[20]);
    //         array_push($data, $attr);
    //     }

    //     foreach ($data as $d) {
    //         if ($d['nip'] == null) {
    //         } else {
    //             $check = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->where('nip', $d['nip'])->orderBy('kelas', 'DESC')->first();
    //             if ($check == null) {
    //             } else {
    //                 $check->update([
    //                     'potongan_bpjs_1persen' => $d['bpjs_1persen'],
    //                     'potongan_bpjs_4persen' => $d['bpjs_4persen'],
    //                 ]);
    //             }
    //         }
    //     }

    //     $rekaptpp = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
    //     foreach ($rekaptpp as $item) {
    //         $item->update([
    //             'tpp_diterima' => $item->pembayaran - ($item->potongan_pph21 + $item->potongan_bpjs_1persen),
    //         ]);
    //     }

    //     toastr()->success('BPJS Berhasil di upload');
    //     return redirect('/admin/rekapitulasi/' . $bulan . '/' . $tahun);
    // }

    public function paguExcel($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->where('puskesmas_id', null)->get();
        $skpd = Auth::user()->skpd;

        return view('admin.rekapitulasi.paguexcel', compact('data', 'skpd', 'bulan', 'tahun'));
    }

    // public function plt($bulan, $tahun, $id)
    // {

    //     toastr()->success('Dalam Pengembangan, akan selesai pada pukul 21:00');
    //     return back();
    //     // $skpd = Skpd::get();
    //     // $data = RekapTpp::find($id);
    //     // return view('admin.rekapitulasi.plt', compact('skpd', 'data', 'bulan', 'tahun'));
    // }

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
        // $data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        // return view('admin.rekapitulasi.PGbulantahun', compact('data', 'bulan', 'tahun'));
        return view('admin.rekap2023.puskesmas.tab', compact('tahun', 'bulan'));
    }

    public function PGexcel($bulan, $tahun)
    {
        $data = RekapTpp::where('skpd_id', 34)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get()->take(100);

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
        $sheet->setCellValue('D6', 'PANGKAT/GOLONGAN')->mergeCells('D6:D9');
        $sheet->setCellValue('E6', 'JABATAN')->mergeCells('E6:E9');
        $sheet->setCellValue('F6', 'JENIS JABATAN')->mergeCells('F6:F9');
        $sheet->setCellValue('G6', 'KELAS')->mergeCells('G6:G9');

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

        $style2 = [
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

        $style3 = [
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
        $sheet->getStyle('E6:E9')->applyFromArray($style1);
        $sheet->getStyle('F6:F9')->applyFromArray($style1);
        $sheet->getStyle('G6:G9')->applyFromArray($style1);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $rows = 10;
        $no = 1;
        $countData = $rows + $data->count();

        foreach ($data as $item) {
            $sheet->setCellValue('A' . $rows, $no++);
            $sheet->setCellValue('B' . $rows, strtoupper($item->nama));
            $sheet->setCellValue('C' . $rows, 'NIP. ' . $item->nip);
            $sheet->setCellValue('D' . $rows, $item->pangkat . ' (' . $item->golongan . ')');
            $sheet->setCellValue('E' . $rows, $item->jabatan);
            $sheet->setCellValue('F' . $rows, $item->jenis_jabatan);
            $sheet->setCellValue('G' . $rows, $item->kelas);
            $rows++;
        }

        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleCenter = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A10:A' . $countData)->applyFromArray($styleBorder);
        $sheet->getStyle('B10:B' . $countData)->applyFromArray($styleBorder);
        $sheet->getStyle('C10:C' . $countData)->applyFromArray($styleBorder);
        $sheet->getStyle('D10:D' . $countData)->applyFromArray($styleBorder)->applyFromArray($styleCenter);
        $sheet->getStyle('E10:E' . $countData)->applyFromArray($styleBorder);
        $sheet->getStyle('F10:F' . $countData)->applyFromArray($styleBorder);
        $sheet->getStyle('G10:G' . $countData)->applyFromArray($styleBorder);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    //new function rekap 2023 
    public function kuncitpptu($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'tu';

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp);

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp);

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

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

    public function kuncitpppuskes($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'puskesdinkes';

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 37)->where('puskesmas_id', '!=', 36)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (85 / 100));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (85 / 100));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (85 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * (85 / 100));
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //simpan jumlah pembayaran
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
            ->where('skpd_id', Auth::user()->skpd->id)
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
        toastr()->success('Telah Di Kunci');
        return back();
    }
    public function kuncitppifk($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'ifk';

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk);

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp);
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //simpan jumlah pembayaran
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
            ->where('skpd_id', Auth::user()->skpd->id)
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
        toastr()->success('Telah Di Kunci');
        return back();
    }
    public function kuncitpplabkes($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'labkes';

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk);

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp);
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //simpan jumlah pembayaran
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
            ->where('skpd_id', Auth::user()->skpd->id)
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
        toastr()->success('Telah Di Kunci');
        return back();
    }
    public function kuncitppregulerrs($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'reguler_rs';

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * 68 / 100);

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 68 / 100);

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (68 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * 68 / 100);
            $item->jumlah_pembayaran = round($item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah);
            //simpan jumlah pembayaran
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
            ->where('skpd_id', Auth::user()->skpd->id)
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
        toastr()->success('Telah Di Kunci');
        return back();
    }
    public function kuncitpp($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = null;
        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }


        $data->map(function ($item) use ($bulan) {

            //PBK (beban kerja)
            $pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $pbk;

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            } else {
                if ($item->dp_ta >= 6750) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            }
            $item->ppk_jumlah = round($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp);

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

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

    public function kuncitpp_cpns($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'cpns';
        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }


        $data->map(function ($item) use ($bulan) {

            //PBK (beban kerja)
            $pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $pbk * 80 / 100;

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
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
            } else {

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
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100));

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //dd($item->jumlah_pembayaran, $item->pbk_jumlah, $item->ppk_jumlah);
            //PPH 21
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
    public function kuncitpp_plt($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['skpd_id'] = Auth::user()->skpd->id;
        $param['jenis'] = 'plt';
        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }


        $data->map(function ($item) use ($bulan) {

            //PBK (beban kerja)

            $pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);
            $item->pbk_jumlah = $pbk * 20 / 100;

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
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
            } else {

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
            }

            if ($item->jenis_plt == '2') {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            } else {

                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            }

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));

            if ($item->jenis_plt == '2') {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
            } else {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
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
    public function tarikterlabkes($bulan, $tahun)
    {
        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {

            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('*')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
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
    public function tarikterifk($bulan, $tahun)
    {

        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
    public function tarikterregulerrs($bulan, $tahun)
    {
        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
        //dd($bulanTahunId);
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });

            if (Auth::user()->skpd->id == 34) {
                $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
                $dataIFK = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

                $data = $dataDinas->merge($dataIFK);
            } else {
                $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            }

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
    public function tarikter_plt($bulan, $tahun)
    {

        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        //dd($bulanTahunId);
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });

            if (Auth::user()->skpd->id == 34) {
                $dataDinas = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('jenis_plt', 1)->orderBy('kelas', 'DESC')->get();
                $dataIFK = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('jenis_plt', 1)->orderBy('kelas', 'DESC')->get();

                $data = $dataDinas->merge($dataIFK);
            } else {
                $data = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->where('jenis_plt', 1)->orderBy('kelas', 'DESC')->get();
            }

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
    public function tarikter_cpns($bulan, $tahun)
    {

        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        //dd($bulanTahunId);
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });

            if (Auth::user()->skpd->id == 34) {
                $dataDinas = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
                $dataIFK = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

                $data = $dataDinas->merge($dataIFK);
            } else {
                $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            }

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
    public function tariktertu($bulan, $tahun)
    {
        $bulanTahunId = DB::connection('pajakasn')->table('bulan_tahun')->where('bulan', convertBulan($bulan))->where('tahun', $tahun)->first();
        if ($bulanTahunId == null) {
            toastr()->error('Gaji Belum Di Upload Oleh BPKPAD');
            return back();
        } else {
            $pphTerutangData = DB::connection('pajakasn')
                ->table('pajak')
                ->select('nip', 'pph_terutang', 'bpjs_satu_persen', 'bpjs_empat_persen')
                ->where('bulan_tahun_id', $bulanTahunId->id)
                ->where('skpd_id', Auth::user()->skpd->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(string) $item->nip => $item]; // Pastikan key adalah string
                });

            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            //dd($data);
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
    public function reguler($bulan, $tahun)
    {
        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }


        $data->map(function ($item) use ($bulan) {
            //PBK (beban kerja)
            $item->pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $item->pbk;

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            } else {
                if ($item->dp_ta >= 6750) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            }
            $item->ppk_jumlah = round($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp);

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });

        return view('admin.rekap2023.reguler', compact('data', 'bulan', 'tahun'));
    }
    public function puskes_reguler($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 36)->where('puskesmas_id', '!=', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (85 / 100));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (85 / 100));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (85 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * (85 / 100));
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.puskesmas.reguler', compact('data', 'bulan', 'tahun'));
    }
    public function tu($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data->map(function ($item) use ($bulan) {
            //PBK
            //PBK (beban kerja)
            $item->pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $item->pbk;

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            } else {
                if ($item->dp_ta >= 6750) {
                    $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                    } else {
                        $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                    }
                } else {
                    $item->ppk_aktivitas = 0;
                    $item->ppk_skp = 0;
                }
            }
            $item->ppk_jumlah = round($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp);

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->pph21 - $item->bpjs1;
            return $item;
        });
        //dd($data->take(2));
        return view('admin.rekap2023.tu', compact('data', 'bulan', 'tahun'));
    }

    public function rs_reguler($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data->map(function ($item) use ($bulan) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
                    $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                    } else {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                    }
                } else {
                    $item->pbk_aktivitas = 0;
                    $item->pbk_skp = 0;
                }
            } else {

                if ($item->dp_ta >= 6750) {
                    $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                    if ($item->dp_skp == null) {
                        $item->pbk_skp = 0;
                    } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                    } else {
                        $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                    }
                } else {
                    $item->pbk_aktivitas = 0;
                    $item->pbk_skp = 0;
                }
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * 68 / 100);

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 68 / 100);

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (68 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * 68 / 100);
            $item->jumlah_pembayaran = round($item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah);
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->pph21 - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.puskesmas.regulerrs', compact('data', 'bulan', 'tahun'));
    }

    public function reguler_mp($bulan, $tahun)
    {
        if (Auth::user()->skpd->id == 34) {
            $pegawaiDinas = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
                return $query->where('rs_puskesmas_id', null)->where('sekolah_id', null);
            })->get();
            $pegawaiIFK = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
                return $query->where('rs_puskesmas_id', 37)->where('sekolah_id', null);
            })->get();

            $pegawai = $pegawaiDinas->merge($pegawaiIFK);
        } else {
            $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
                return $query->where('rs_puskesmas_id', null)->where('sekolah_id', null);
            })->get();
        }

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function tu_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('sekolah_id', '!=', null);
        })->get();


        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function puskes_reguler_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', '!=', null)->where('rs_puskesmas_id', '!=', 8)->where('rs_puskesmas_id', '!=', 36)->where('rs_puskesmas_id', '!=', 37)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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

    public function rs_reguler_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', 8)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;

        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapReguler::where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }

        foreach ($data as $item) {
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->where('rhk_tw4', '!=', null)->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '12') {
                $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereDay('tanggal', '>=', 1)
                    ->whereDay('tanggal', '<=', 15)
                    ->where('validasi', 1)
                    ->get();
            } else {
                $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();
            }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }

        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function tu_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;

        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapReguler::where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }
        //dd($data);
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->orderBy('id', 'DESC')->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function puskes_reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->orderBy('id', 'DESC')->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function rs_reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->orderBy('id', 'DESC')->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //dd($data);

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
                $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100);
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
    public function tu_perhitungan($bulan, $tahun)
    {
        //$data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
                $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100);
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
    public function puskes_reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * 68 / 100);
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
    public function reguler_delete($bulan, $tahun, $id)
    {
        RekapReguler::find($id)->delete();
        toastr()->success('Berhasil Dihapus');
        return back();
    }
    public function reguler_bpjs(Request $req)
    {
        $data = RekapReguler::find($req->id_rekap);
        if ($data->skpd_id != Auth::user()->skpd->id) {
            toastr()->error('Bukan Data Milik SKPD Anda');
            return back();
        }
        $data->bpjs1 = $req->satu_persen;
        $data->bpjs4 = $req->empat_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
    }


    public function plt_bpjs(Request $req)
    {
        $data = RekapPlt::find($req->id_rekap);
        if ($data->skpd_id != Auth::user()->skpd->id) {
            toastr()->error('Bukan Data Milik SKPD Anda');
            return back();
        }
        $data->bpjs1 = $req->satu_persen;
        $data->bpjs4 = $req->empat_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
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

    public function reguler_tambahpegawai(Request $req, $bulan, $tahun)
    {
        $pegawai = Pegawai::find($req->pegawai);
        $jabatan = Jabatan::find($req->jabatan);

        $check = RekapReguler::where('nip', $pegawai->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        if ($check == null) {
            $n = new RekapReguler;
            $n->skpd_id          = Auth::user()->skpd->id;
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
            toastr()->error('Data Sudah Di Rekap di' . $skpd->nama);
        }
        return back();
    }

    public function setda_excel($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        return $data;
    }
    public function reguler_excel($bulan, $tahun)
    {
        // dd($this->setda_excel($bulan, $tahun));

        if (Auth::user()->skpd->id == 34) {
            $dataDinas = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $dataIFK = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            $data = $dataDinas->merge($dataIFK);
        } else {
            $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        }

        $dataCpns = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $dataPlt = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $dataBulan = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun);
        $kinerjaBulan = $dataBulan->translatedFormat('F Y');
        $pembayaranBulan = $dataBulan->addMonth(1)->translatedFormat('F Y');

        $filename = 'TPP_' . $bulan . '-' . $tahun . '-' . Carbon::now()->format('H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');

        if (Auth::user()->skpd->id == 1) {
            if ($bulan == '12') {
                $path = public_path('/excel/disdik_50.xlsx');
            } else {
                $path = public_path('/excel/disdik.xlsx');
            }
        } else {
            if ($bulan == '12') {
                $path = public_path('/excel/testing_50.xlsx');
            } else {
                $path = public_path('/excel/testing.xlsx');
            }
        }
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($path);


        if (Auth::user()->skpd->id == 1) {

            $dataTU = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', '!=', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
            //sheet TU disdik
            $spreadsheet->getSheetByName('TU')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
            $spreadsheet->getSheetByName('TU')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
            $contentRow = 8;
            foreach ($dataTU as $key => $item) {
                $spreadsheet->getSheetByName('TU')->setCellValue('B' . $contentRow, $item->nama);
                $spreadsheet->getSheetByName('TU')->setCellValue('C' . $contentRow, '\'' . $item->nip);
                $spreadsheet->getSheetByName('TU')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
                $spreadsheet->getSheetByName('TU')->setCellValue('E' . $contentRow, $item->jabatan);
                $spreadsheet->getSheetByName('TU')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
                $spreadsheet->getSheetByName('TU')->setCellValue('G' . $contentRow, $item->kelas);
                $spreadsheet->getSheetByName('TU')->setCellValue('I' . $contentRow, $item->basic);

                $spreadsheet->getSheetByName('TU')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
                $spreadsheet->getSheetByName('TU')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
                $spreadsheet->getSheetByName('TU')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
                $spreadsheet->getSheetByName('TU')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
                $spreadsheet->getSheetByName('TU')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
                $spreadsheet->getSheetByName('TU')->setCellValue('P' . $contentRow, $item->dp_ta);
                $spreadsheet->getSheetByName('TU')->setCellValue('Q' . $contentRow, $item->dp_skp);
                $spreadsheet->getSheetByName('TU')->setCellValue('AI' . $contentRow, ($item->pph_terutang));
                $spreadsheet->getSheetByName('TU')->setCellValue('AJ' . $contentRow, $item->bpjs1);
                $contentRow++;
            }
            //remove row
            $rowMulaiHapus = $contentRow;
            $jumlahDihapus = 156 - $rowMulaiHapus;
            //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
            $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
            $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
            $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
            $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
            $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
            $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
            $spreadsheet->getSheetByName('TU')->removeRow($rowMulaiHapus, $jumlahDihapus);
            $spreadsheet->getSheetByName('TU')->setCellValue('V' . $contentRow, $sumV);
            $spreadsheet->getSheetByName('TU')->setCellValue('Z' . $contentRow, $sumZ);
            $spreadsheet->getSheetByName('TU')->setCellValue('AB' . $contentRow, $sumAB);
            $spreadsheet->getSheetByName('TU')->setCellValue('AE' . $contentRow, $sumAE);
            $spreadsheet->getSheetByName('TU')->setCellValue('AF' . $contentRow, $sumAF);
            $spreadsheet->getSheetByName('TU')->setCellValue('AI' . $contentRow, $sumAI);
        }

        //sheet reguler
        $spreadsheet->getSheetByName('REGULER')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('REGULER')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($data as $key => $item) {
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
            $spreadsheet->getSheetByName('REGULER')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('REGULER')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 156 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
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

        //sheet CPNS
        $spreadsheet->getSheetByName('CPNS')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('CPNS')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRowCpns = 8;
        foreach ($dataCpns as $key => $item) {
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
            $spreadsheet->getSheetByName('CPNS')->setCellValue('AG' . $contentRowCpns, $item->pph_terutang);
            $spreadsheet->getSheetByName('CPNS')->setCellValue('AH' . $contentRowCpns, $item->bpjs1);
            $contentRowCpns++;
        }

        //sheet PLT
        $spreadsheet->getSheetByName('PLT')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('PLT')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRowPlt = 8;
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
            // if ($item->jenis_plt == '2' || $item->jenis_plt == '1') {
            //     //=ROUND(SUM(S9:U9);0)
            //     $formulaPagu = '=ROUND(I' . $contentRowPlt . '*(SUM(J' . $contentRowPlt . ':M' . $contentRowPlt . '))*20%,0)';
            //     //$formulaBK = '=ROUND(SUM(S9:U9)*20%,0)';
            //     $formulaBK = '=ROUND(SUM(S' . $contentRowPlt . ':U' . $contentRowPlt . ')*20%,0)';

            //     $formulaPK = '=ROUND(SUM(W' . $contentRowPlt . ':Y' . $contentRowPlt . ')*20%,0)';
            //     $formulaKK = '=ROUND(AA' . $contentRowPlt . '*20%,0)';
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('N' . $contentRowPlt, $formulaPagu);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('V' . $contentRowPlt, $formulaBK);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('Z' . $contentRowPlt, $formulaPK);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('AB' . $contentRowPlt, $formulaKK);
            // }
            $spreadsheet->getSheetByName('PLT')->setCellValue('O' . $contentRowPlt, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('PLT')->setCellValue('P' . $contentRowPlt, $item->dp_ta);
            $spreadsheet->getSheetByName('PLT')->setCellValue('Q' . $contentRowPlt, $item->dp_skp);
            $spreadsheet->getSheetByName('PLT')->setCellValue('AG' . $contentRowPlt, 0);
            $spreadsheet->getSheetByName('PLT')->setCellValue('AH' . $contentRowPlt, 0);
            $contentRowPlt++;
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function reguler_excel_setda($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            $item->bagian = Pegawai::where('nip', $item->nip)->first()->bagian == null ? null : Pegawai::where('nip', $item->nip)->first()->bagian->nama;
            return $item;
        });

        $pejabat        = $data->where('bagian', 'pejabat');
        $umum           = $data->where('bagian', 'umum');
        $kesra          = $data->where('bagian', 'kesra');
        $organisasi     = $data->where('bagian', 'organisasi');
        $perekonomian   = $data->where('bagian', 'perekonomian');
        $prokom         = $data->where('bagian', 'prokom');
        $pbj            = $data->where('bagian', 'pbj');
        $adpem          = $data->where('bagian', 'adpem');
        $hukum          = $data->where('bagian', 'hukum');
        $pemerintahan   = $data->where('bagian', 'pemerintahan');

        $dataCpns = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $dataPlt = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $dataBulan = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun);
        $kinerjaBulan = $dataBulan->translatedFormat('F Y');
        $pembayaranBulan = $dataBulan->addMonth(1)->translatedFormat('F Y');

        $filename = 'TPP_' . $bulan . '-' . $tahun . '-' . Carbon::now()->format('H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');

        $path = public_path('/excel/setda.xlsx');
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($path);


        //sheet Pejabat
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($pejabat as $key => $item) {
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Pejabat')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Pejabat')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 22 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Pejabat')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Pejabat')->setCellValue('AI' . $contentRow, $sumAI);


        //sheet umum
        $spreadsheet->getSheetByName('Umum')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Umum')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($umum as $key => $item) {
            $spreadsheet->getSheetByName('Umum')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Umum')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Umum')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Umum')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Umum')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Umum')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Umum')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Umum')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Umum')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Umum')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Umum')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Umum')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Umum')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Umum')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Umum')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Umum')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 36 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Umum')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Umum')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Umum')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Umum')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Umum')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Umum')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Umum')->setCellValue('AI' . $contentRow, $sumAI);


        //sheet kesra
        $spreadsheet->getSheetByName('Kesra')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Kesra')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($kesra as $key => $item) {
            $spreadsheet->getSheetByName('Kesra')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Kesra')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Kesra')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Kesra')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Kesra')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Kesra')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Kesra')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Kesra')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 25 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Kesra')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Kesra')->setCellValue('AI' . $contentRow, $sumAI);


        //sheet organisasi
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($organisasi as $key => $item) {
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Organisasi')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Organisasi')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 32 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Organisasi')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Organisasi')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet perekonomian
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($perekonomian as $key => $item) {
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 21 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Perekonomian')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Perekonomian')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet prokom
        $spreadsheet->getSheetByName('Prokom')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Prokom')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($prokom as $key => $item) {
            $spreadsheet->getSheetByName('Prokom')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Prokom')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Prokom')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Prokom')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Prokom')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Prokom')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Prokom')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Prokom')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 36 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Prokom')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Prokom')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet PBJ
        $spreadsheet->getSheetByName('PBJ')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('PBJ')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($pbj as $key => $item) {
            $spreadsheet->getSheetByName('PBJ')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('PBJ')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('PBJ')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('PBJ')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('PBJ')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('PBJ')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('PBJ')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('PBJ')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 38 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('PBJ')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('PBJ')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet Adpem
        $spreadsheet->getSheetByName('Adpem')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Adpem')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($adpem as $key => $item) {
            $spreadsheet->getSheetByName('Adpem')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Adpem')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Adpem')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Adpem')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Adpem')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Adpem')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Adpem')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Adpem')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 21 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Adpem')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Adpem')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet Hukum
        $spreadsheet->getSheetByName('Hukum')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Hukum')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($hukum as $key => $item) {
            $spreadsheet->getSheetByName('Hukum')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Hukum')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Hukum')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Hukum')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Hukum')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Hukum')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Hukum')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Hukum')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 20 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Hukum')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Hukum')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet Pemerintahan
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($pemerintahan as $key => $item) {
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 28 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('Pemerintahan')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('Pemerintahan')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet CPNS
        $spreadsheet->getSheetByName('CPNS')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('CPNS')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRowCpns = 8;
        foreach ($dataCpns as $key => $item) {
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
            $spreadsheet->getSheetByName('CPNS')->setCellValue('AG' . $contentRow, $item->pph_terutang);
            $spreadsheet->getSheetByName('CPNS')->setCellValue('AH' . $contentRow, $item->bpjs1);
            $contentRowCpns++;
        }

        //sheet PLT
        $spreadsheet->getSheetByName('PLT')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('PLT')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRowPlt = 8;
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
            // if ($item->jenis_plt == '2') {
            //     //=ROUND(SUM(S9:U9);0)
            //     $formulaPagu = '=ROUND(I' . $contentRowPlt . '*(SUM(J' . $contentRowPlt . ':M' . $contentRowPlt . '))*20%,0)';
            //     //$formulaBK = '=ROUND(SUM(S9:U9)*20%,0)';
            //     $formulaBK = '=ROUND(SUM(S' . $contentRowPlt . ':U' . $contentRowPlt . ')*20%,0)';

            //     $formulaPK = '=ROUND(SUM(W' . $contentRowPlt . ':Y' . $contentRowPlt . ')*20%,0)';
            //     $formulaKK = '=ROUND(AA' . $contentRowPlt . '*20%,0)';
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('N' . $contentRowPlt, $formulaPagu);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('V' . $contentRowPlt, $formulaBK);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('Z' . $contentRowPlt, $formulaPK);
            //     $spreadsheet->getSheetByName('PLT')->setCellValue('AB' . $contentRowPlt, $formulaKK);
            // }
            $spreadsheet->getSheetByName('PLT')->setCellValue('O' . $contentRowPlt, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('PLT')->setCellValue('P' . $contentRowPlt, $item->dp_ta);
            $spreadsheet->getSheetByName('PLT')->setCellValue('Q' . $contentRowPlt, $item->dp_skp);
            $contentRowPlt++;
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    public function puskes_reguler_excel($bulan, $tahun)
    {

        $reguler_rs = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //$cpns_rs = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $reguler_puskes = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //$cpns_puskes = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $IFK = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $LABKES = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        //dd($reguler_puskes)
        $dataBulan = Carbon::createFromFormat('m/Y', $bulan . '/' . $tahun);
        $kinerjaBulan = $dataBulan->translatedFormat('F Y');
        $pembayaranBulan = $dataBulan->addMonth(1)->translatedFormat('F Y');

        $filename = 'TPP_' . $bulan . '-' . $tahun . '-' . Carbon::now()->format('H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');

        $path = public_path('/excel/rspuskes.xlsx');
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($path);

        //sheet reguler
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($reguler_rs as $key => $item) {
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AI' . $contentRow, ($item->pph_terutang));
            $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AJ' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 952 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('REGULER_RS')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('REGULER_RS')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet CPNS
        // $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        // $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        // $contentRowCpns = 8;
        // foreach ($cpns_rs as $key => $item) {
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('B' . $contentRowCpns, $item->nama);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('C' . $contentRowCpns, '\'' . $item->nip);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('D' . $contentRowCpns, $item->pangkat . '/' . $item->golongan);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('E' . $contentRowCpns, $item->jabatan);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('F' . $contentRowCpns, $item->jenis_jabatan);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('G' . $contentRowCpns, $item->kelas);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('I' . $contentRowCpns, $item->basic);

        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('J' . $contentRowCpns, (($item->p_bk + $item->p_tbk) / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('K' . $contentRowCpns, ($item->p_pk / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('L' . $contentRowCpns, ($item->p_kk / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('M' . $contentRowCpns, ($item->p_kp / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('O' . $contentRowCpns, ($item->dp_absensi / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('P' . $contentRowCpns, $item->dp_ta);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('Q' . $contentRowCpns, $item->dp_skp);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('R' . $contentRowCpns, ($item->pph21 / 100));
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('AG' . $contentRowCpns, $item->bpjs1);
        //     $spreadsheet->getSheetByName('CPNS_RS')->setCellValue('AH' . $contentRowCpns, $item->bpjs4);
        //     $contentRowCpns++;
        // }

        // //sheet REGULER PUSKES
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRowPuskes = 8;

        foreach ($reguler_puskes as $key => $item) {
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('B' . $contentRowPuskes, $item->nama);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('C' . $contentRowPuskes, '\'' . $item->nip);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('D' . $contentRowPuskes, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('E' . $contentRowPuskes, $item->jabatan);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('F' . $contentRowPuskes, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('G' . $contentRowPuskes, $item->kelas);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('I' . $contentRowPuskes, $item->basic);

            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('J' . $contentRowPuskes, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('K' . $contentRowPuskes, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('L' . $contentRowPuskes, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('M' . $contentRowPuskes, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('O' . $contentRowPuskes, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('P' . $contentRowPuskes, $item->dp_ta);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('Q' . $contentRowPuskes, $item->dp_skp);
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AI' . $contentRowPuskes, ($item->pph_terutang));
            $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AJ' . $contentRowPuskes, $item->bpjs1);
            $contentRowPuskes++;
        }
        //remove row
        $rowMulaiHapusPuskes = $contentRowPuskes;
        $jumlahDihapusPuskes = 952 - $rowMulaiHapusPuskes;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRowPuskes);
        $sumV = '=SUM(V8:V' . ($contentRowPuskes - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRowPuskes - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRowPuskes - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRowPuskes - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRowPuskes - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRowPuskes - 1) . ')';
        $spreadsheet->getSheetByName('REGULER_PUSKES')->removeRow($rowMulaiHapusPuskes, $jumlahDihapusPuskes);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('V' . $contentRowPuskes, $sumV);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('Z' . $contentRowPuskes, $sumZ);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AB' . $contentRowPuskes, $sumAB);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AE' . $contentRowPuskes, $sumAE);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AF' . $contentRowPuskes, $sumAF);
        $spreadsheet->getSheetByName('REGULER_PUSKES')->setCellValue('AI' . $contentRowPuskes, $sumAI);

        //sheet CPNS Puskes
        // $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        // $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        // $contentRowCpnsPuskes = 8;
        // foreach ($cpns_puskes as $key => $item) {
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('B' . $contentRowCpnsPuskes, $item->nama);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('C' . $contentRowCpnsPuskes, '\'' . $item->nip);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('D' . $contentRowCpnsPuskes, $item->pangkat . '/' . $item->golongan);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('E' . $contentRowCpnsPuskes, $item->jabatan);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('F' . $contentRowCpnsPuskes, $item->jenis_jabatan);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('G' . $contentRowCpnsPuskes, $item->kelas);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('I' . $contentRowCpnsPuskes, $item->basic);

        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('J' . $contentRowCpnsPuskes, (($item->p_bk + $item->p_tbk) / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('K' . $contentRowCpnsPuskes, ($item->p_pk / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('L' . $contentRowCpnsPuskes, ($item->p_kk / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('M' . $contentRowCpnsPuskes, ($item->p_kp / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('O' . $contentRowCpnsPuskes, ($item->dp_absensi / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('P' . $contentRowCpnsPuskes, $item->dp_ta);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('Q' . $contentRowCpnsPuskes, $item->dp_skp);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('R' . $contentRowCpnsPuskes, ($item->pph21 / 100));
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('AG' . $contentRowCpnsPuskes, $item->bpjs1);
        //     $spreadsheet->getSheetByName('CPNS_PUSKES_LAB')->setCellValue('AH' . $contentRowCpnsPuskes, $item->bpjs4);
        //     $contentRowCpnsPuskes++;
        // }
        //sheet IFK
        $spreadsheet->getSheetByName('IFK')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('IFK')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($IFK as $key => $item) {
            $spreadsheet->getSheetByName('IFK')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('IFK')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('IFK')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('IFK')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('IFK')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('IFK')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('IFK')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('IFK')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('IFK')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('IFK')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('IFK')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('IFK')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('IFK')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('IFK')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('IFK')->setCellValue('AI' . $contentRow, ($item->pph_terutang));
            $spreadsheet->getSheetByName('IFK')->setCellValue('AJ' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 18 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('IFK')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('IFK')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('IFK')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('IFK')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('IFK')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('IFK')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('IFK')->setCellValue('AI' . $contentRow, $sumAI);

        //sheet LABKES
        $spreadsheet->getSheetByName('LABKES')->setCellValue('A2', 'BULAN ' . strtoupper($pembayaranBulan) . ' UNTUK KINERJA ' . strtoupper($kinerjaBulan));
        $spreadsheet->getSheetByName('LABKES')->setCellValue('A3', strtoupper(Auth::user()->skpd->nama));
        $contentRow = 8;
        foreach ($LABKES as $key => $item) {
            $spreadsheet->getSheetByName('LABKES')->setCellValue('B' . $contentRow, $item->nama);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('C' . $contentRow, '\'' . $item->nip);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('D' . $contentRow, $item->pangkat . '/' . $item->golongan);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('E' . $contentRow, $item->jabatan);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('F' . $contentRow, $item->jenis_jabatan);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('G' . $contentRow, $item->kelas);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('I' . $contentRow, $item->basic);

            $spreadsheet->getSheetByName('LABKES')->setCellValue('J' . $contentRow, (($item->p_bk + $item->p_tbk) / 100));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('K' . $contentRow, ($item->p_pk / 100));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('L' . $contentRow, ($item->p_kk / 100));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('M' . $contentRow, ($item->p_kp / 100));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('O' . $contentRow, ($item->dp_absensi / 100));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('P' . $contentRow, $item->dp_ta);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('Q' . $contentRow, $item->dp_skp);
            $spreadsheet->getSheetByName('LABKES')->setCellValue('AI' . $contentRow, ($item->pph_terutang));
            $spreadsheet->getSheetByName('LABKES')->setCellValue('AJ' . $contentRow, $item->bpjs1);
            $contentRow++;
        }
        //remove row
        $rowMulaiHapus = $contentRow;
        $jumlahDihapus = 18 - $rowMulaiHapus;
        //dd($rowMulaiHapus, $jumlahDihapus, $contentRow);
        $sumV = '=SUM(V8:V' . ($contentRow - 1) . ')';
        $sumZ = '=SUM(Z8:Z' . ($contentRow - 1) . ')';
        $sumAB = '=SUM(AB8:AB' . ($contentRow - 1) . ')';
        $sumAE = '=SUM(AE8:AE' . ($contentRow - 1) . ')';
        $sumAF = '=SUM(AF8:AF' . ($contentRow - 1) . ')';
        $sumAI = '=SUM(AI8:AI' . ($contentRow - 1) . ')';
        $spreadsheet->getSheetByName('LABKES')->removeRow($rowMulaiHapus, $jumlahDihapus);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('V' . $contentRow, $sumV);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('Z' . $contentRow, $sumZ);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('AB' . $contentRow, $sumAB);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('AE' . $contentRow, $sumAE);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('AF' . $contentRow, $sumAF);
        $spreadsheet->getSheetByName('LABKES')->setCellValue('AI' . $contentRow, $sumAI);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    public function cpns($bulan, $tahun)
    {
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) use ($bulan) {
            //PBK
            $item->pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $item->pbk * (80 / 100);

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
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
            } else {
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
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100));

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));
            $item->pkk_jumlah = $item->pkk;

            //PKP
            $item->pkp = round($item->basic * ($item->p_kp / 100));
            $item->pkp_jumlah = $item->pkp;
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //dd($item->jumlah_pembayaran, $item->pbk_jumlah, $item->ppk_jumlah);
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->pph21 - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.cpns', compact('data', 'bulan', 'tahun'));
    }
    public function puskes_cpns($bulan, $tahun)
    {
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 37)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
        return view('admin.rekap2023.puskesmas.cpns', compact('data', 'bulan', 'tahun'));
    }
    public function rs_cpns_kuncitpp($bulan, $tahun)
    {
        $param['bulan'] = $bulan;
        $param['tahun'] = $tahun;
        $param['rs_puskesmas_id'] = Auth::user()->puskesmas->id;
        $param['jenis'] = 'cpnsrs';

        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

        $data->map(function ($item) {
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
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (80 / 100) * (70 / 100));

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
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100) * (70 / 100));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (80 / 100) * (70 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * (80 / 100) * (70 / 100));
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //simpan jumlah pembayaran
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
            ->where('skpd_id', Auth::user()->skpd->id)
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
        toastr()->success('Telah Di Kunci');
        return back();
    }

    public function rs_cpns($bulan, $tahun)
    {
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp) * (80 / 100) * (70 / 100));

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
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * (80 / 100) * (70 / 100));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk * (80 / 100) * (70 / 100));

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp * (80 / 100) * (70 / 100));
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //dd($item->jumlah_pembayaran, $item->pbk_jumlah, $item->ppk_jumlah);
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->pph21 - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.puskesmas.cpnsrs', compact('data', 'bulan', 'tahun'));
    }
    public function cpns_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'cpns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', null)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapCpns::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapCpns;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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

    public function puskes_cpns_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'cpns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', '!=', null)->where('rs_puskesmas_id', '!=', 8)->where('rs_puskesmas_id', '!=', 37)->where('sekolah_id', null);
        })->get();
        //dd($pegawai);
        foreach ($pegawai as $item) {
            $check = RekapCpns::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapCpns;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function rs_cpns_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'cpns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', 8)->where('sekolah_id', null);
        })->get();
        //dd($pegawai);
        foreach ($pegawai as $item) {
            $check = RekapCpns::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapCpns;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
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
    public function puskes_cpns_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
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
    public function rs_cpns_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
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
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (80 / 100);
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
    public function puskes_cpns_perhitungan($bulan, $tahun)
    {
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', '!=', null)->where('puskesmas_id', '!=', 8)->where('puskesmas_id', '!=', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
    public function rs_cpns_perhitungan($bulan, $tahun)
    {
        $data = RekapCpns::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 8)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (80 / 100) * (70 / 100);
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
        if ($data->skpd_id != Auth::user()->skpd->id) {
            toastr()->error('Bukan Data Milik SKPD Anda');
            return back();
        }
        $data->bpjs1 = $req->satu_persen;
        $data->bpjs4 = $req->empat_persen;
        $data->save();
        toastr()->success('Berhasil Di Input');
        return back();
    }

    public function cpns_delete($bulan, $tahun, $id)
    {
        RekapCpns::find($id)->delete();
        toastr()->success('Berhasil Dihapus');
        return back();
    }

    public function plt_delete($bulan, $tahun, $id)
    {
        RekapPlt::find($id)->delete();
        toastr()->success('Berhasil Dihapus');
        return back();
    }
    public function plt($bulan, $tahun)
    {
        $data = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', null)->where('sekolah_id', null)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) use ($bulan) {
            //PBK
            $item->pbk = $item->basic * (($item->p_bk + $item->p_tbk) / 100);

            $item->pbk_jumlah = $item->pbk  * 20 / 100;



            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($bulan == '12') {
                if ($item->dp_ta >= 3375) {
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
            } else {
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
            }

            if ($item->jenis_plt == '2') {
                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            } else {

                $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp) * 20 / 100);
            }

            //PKK
            $item->pkk = round($item->basic * ($item->p_kk / 100));

            if ($item->jenis_plt == '2') {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
            } else {

                $item->pkk_jumlah = round($item->pkk * 20 / 100);
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
        return view('admin.rekap2023.plt', compact('data', 'bulan', 'tahun'));
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
            $n->skpd_id          = Auth::user()->skpd->id;
            $n->puskesmas_id     = $jabatan_definitif == null ? null : $jabatan_definitif->rs_puskesmas_id;
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
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
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
        $data = RekapPlt::where('skpd_id', Auth::user()->skpd->id)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
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
                    $pagu      = $basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100) * (20 / 100);
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

    public function getJabatan(Request $req)
    {
        if ($req->searchTerm == null) {
            $data = null;
        } else {
            $data = Jabatan::where('skpd_id', Auth::user()->skpd->id)->where('nama', 'LIKE', '%' . $req->searchTerm . '%')->get()->map(function ($item) {
                $item->persen_kondisi_kerja = $item->persen_kondisi_kerja == null ? 0 : $item->persen_kondisi_kerja;
                $item->kelas = $item->kelas->nama;
                return $item;
            })->take(10)->toArray();
            return json_encode($data);
        }
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

    //IFK
    public function ifk_reguler($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk);

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp);
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.ifk.reguler', compact('data', 'bulan', 'tahun'));
    }
    public function ifk_reguler_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', 37)->where('sekolah_id', null);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function ifk_reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->where('rhk_tw4', '!=', null)->orderBy('id', 'DESC')->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function ifk_reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 37)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100));
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

    //LABKES
    public function labkes_reguler($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        $data->map(function ($item) {
            //PBK
            $item->pbk_absensi = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->pbk_aktivitas = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (10 / 100);
                } else {
                    $item->pbk_skp = $item->basic * (($item->p_bk + $item->p_tbk) / 100) * (20 / 100);
                }
            } else {
                $item->pbk_aktivitas = 0;
                $item->pbk_skp = 0;
            }
            $item->pbk_jumlah = round(($item->pbk_absensi + $item->pbk_aktivitas + $item->pbk_skp));

            //PPK
            $item->ppk_absensi = $item->basic * ($item->p_pk / 100) * (40 / 100) * ($item->dp_absensi / 100);
            if ($item->dp_ta >= 6750) {
                $item->ppk_aktivitas = $item->basic * ($item->p_pk / 100) * (40 / 100);
                if ($item->dp_skp == null) {
                    $item->pbk_skp = 0;
                } else if ($item->dp_skp == 'KURANG' || $item->dp_skp == "SANGAT KURANG") {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (10 / 100);
                } else {
                    $item->ppk_skp = $item->basic * ($item->p_pk / 100) * (20 / 100);
                }
            } else {
                $item->ppk_aktivitas = 0;
                $item->ppk_skp = 0;
            }
            $item->ppk_jumlah = round(($item->ppk_absensi + $item->ppk_aktivitas + $item->ppk_skp));

            //PKK
            $item->pkk = $item->basic * ($item->p_kk / 100);
            $item->pkk_jumlah = round($item->pkk);

            //PKP
            $item->pkp = $item->basic * ($item->p_kp / 100);
            $item->pkp_jumlah = round($item->pkp);
            $item->jumlah_pembayaran = $item->pbk_jumlah + $item->ppk_jumlah + $item->pkk_jumlah + $item->pkp_jumlah;
            //PPH 21
            $item->pph21 = round($item->jumlah_pembayaran * ($item->pph21 / 100));
            $item->tpp_diterima = $item->jumlah_pembayaran - $item->bpjs1;
            return $item;
        });
        return view('admin.rekap2023.labkes.reguler', compact('data', 'bulan', 'tahun'));
    }
    public function labkes_reguler_mp($bulan, $tahun)
    {

        $pegawai = Pegawai::where('skpd_id', Auth::user()->skpd->id)->where('is_aktif', 1)->where('status_pns', 'pns')->where('jabatan_id', '!=', null)->whereHas('jabatan', function ($query) {
            return $query->where('rs_puskesmas_id', 36);
        })->get();

        foreach ($pegawai as $item) {
            $check = RekapReguler::where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $n = new RekapReguler;
                $n->skpd_id          = Auth::user()->skpd->id;
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
                if ($check->skpd_id == Auth::user()->skpd->id || $check->skpd_id == null) {
                    $check->update([
                        'skpd_id'       => Auth::user()->skpd->id,
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
    public function labkes_reguler_psa($bulan, $tahun)
    {
        //cuti bersama
        $cuti_bersama = DB::connection('presensi')->table('libur_nasional')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('deskripsi', 'cuti bersama')->get()->count() * 360;
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();
        foreach ($data as $item) {
            $presensi = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            $dp_ct = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 7)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_tl = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 5)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_co = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 9)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $dp_di = DB::connection('presensi')->table('detail_cuti')->where('nip', $item->nip)->where('jenis_keterangan_id', 4)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()->count() * 360;
            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;
            $aktivitas = Aktivitas::where('pegawai_id', $pegawai_id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('validasi', 1)->get();

            $menit_aktivitas = $aktivitas->sum('menit') + $dp_ct + $dp_tl + $dp_co + $dp_di + $cuti_bersama;

            if ($presensi == null) {
                $absensi = 0;
            } else {
                $absensi = $presensi->persen_kehadiran;
            }

            $pegawai_id = Pegawai::where('nip', $item->nip)->first()->id;

            if ($bulan == '01' || $bulan == '02' || $bulan == '03') {
                //ambil penilaian TW 4 tahun sebelumnya
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun - 1)->where('rhk_tw4', '!=', null)->orderBy('id', 'DESC')->first();

                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '04' || $bulan == '05' || $bulan == '06') {
                //ambil penilaian TW 1 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '07' || $bulan == '08' || $bulan == '09') {
                //ambil penilaian TW 2 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
            }

            if ($bulan == '10' || $bulan == '11' || $bulan == '12') {
                //ambil penilaian TW 3 tahun berjalan
                $skp = Skp2023::where('pegawai_id', $pegawai_id)->whereYear('sampai', $tahun)->where('is_aktif', 1)->first();
                if ($skp == null) {
                    $nilaiSKP = null;
                } else {
                    $rhk = 'rhk_' . nilaiTW($bulan);
                    $rpk = 'rpk_' . nilaiTW($bulan);
                    $nilai_rhk = $skp[$rhk];
                    $nilai_rpk = $skp[$rpk];
                    $nilaiSKP = nilaiSkp($nilai_rhk, $nilai_rpk);
                }
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
                'dp_skp'       => $nilaiSKP,
            ]);
        }
        toastr()->success('Berhasil di tarik');
        return back();
    }
    public function labkes_reguler_perhitungan($bulan, $tahun)
    {
        $data = RekapReguler::where('skpd_id', Auth::user()->skpd->id)->where('puskesmas_id', 36)->where('bulan', $bulan)->where('tahun', $tahun)->orderBy('kelas', 'DESC')->get();

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
                $pagu      = round($basic * (($p_bk + $p_tbk + $p_pk + $p_kk + $p_kp) / 100));
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
}
