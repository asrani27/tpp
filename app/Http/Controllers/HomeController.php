<?php

namespace App\Http\Controllers;

use App\Skpd;
use App\User;
use App\Kelas;
use App\Jabatan;
use App\Pegawai;
use App\Skp2023;
use App\Presensi;
use App\RekapTpp;
use App\Aktivitas;
use App\Exports\ParameterTPP;
use App\Exports\PegawaiAllExport;
use App\Parameter;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Exports\PegawaiExport;
use App\View_aktivitas_pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{

    public function parametertpp()
    {
        return Excel::download(new ParameterTPP, 'parametertpp.xlsx');
    }

    public function parametertpppuskesmas()
    {
        return Excel::download(new ParameterTPPpuskesmas, 'parametertpppuskesmas.xlsx');
    }
    public function allpegawai()
    {
        //return Excel::download(new PegawaiAllExport, 'datapegawai.xlsx');
        $pegawai = Pegawai::where('is_aktif', 1)->whereNotIn('skpd_id', [17, 18, 35])->get()->map(function ($item) {


            //cek atasan apakah PLT atau Bukan
            //$check = $item->jabatan->atasan == null ? Jabatan::where('sekda', 1)->first() : $item->jabatan->atasan;
            if ($item->jabatan === null) {
                $atasan = null;
            } else {
                if ($item->jabatan->atasan == null) {
                    $check = Jabatan::where('sekda', 1)->first();
                } else {
                    $check = $item->jabatan->atasan;
                }

                if ($check->pegawai == null) {
                    //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
                    if ($check->pegawaiPlt == null) {
                        $atasan = $check;
                    } else {
                        // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                        if ($item->id == $check->pegawaiPlt->id) {
                            //cek lagi, jika sekretaris memPLT Kadis, maka pejabat penilai adalah SEKDA
                            if ($check->atasan == null) {
                                $atasan = Jabatan::where('sekda', 1)->first();
                            } else {
                                $atasan = $check->atasan;
                            }
                        } else {
                            $atasan = $check;
                        }
                    }
                } else {
                    //Jika Pegawai Ada berarti atasannya adalan jabatan definitif
                    $atasan = $check;
                }
            }

            $item->namaatasan = $atasan;
            $item->kelas = ($item->jabatan == null ? null : $item->jabatan->kelas) == null ? null : $item->jabatan->kelas->nama;
            return $item;
        });
        $data = $pegawai->sortBy([
            fn($a, $b) => strcmp($a->skpd->nama, $b->skpd->nama), // Urutkan nama SKPD
            fn($a, $b) => $b->kelas <=> $a->kelas, // Lalu urutkan kelas jabatan
        ]);

        $data->values()->all(); // Reset kunci array
        return view('superadmin.export.pegawai', compact('data'));
    }

    public function upload()
    {
        return view('upload');
    }

    public function storeUpload(Request $req)
    {
        Storage::disk('ftp')->put($req->file, 'Contents');
        return back();
    }
    public function checkbapintar(Request $req)
    {
        //check email
        $data = User::where('email_bapintar', $req->email)->first();
        if ($data == null) {
            $response = Http::asForm()->post('https://bapintar.banjarmasinkota.go.id/api/is-register', [
                'email' => $req->email,
            ]);
            if ($response->json()['status'] == true) {
                Auth::user()->update([
                    'is_reg_bapintar' => 1,
                    'email_bapintar' => $req->email
                ]);
                toastr()->success('Terima Kasih Telah Mendaftar Banjarmasin Pintar');
                return redirect('/pegawai/aktivitas/harian');
            } else {
                toastr()->error('Email ini Belum terdaftar di Banjarmasin Pintar, Silahkan Daftar dulu');
                return redirect('/pegawai/aktivitas/harian');
            }
        } else {
            if ($data->id == Auth::user()->id) {
                $data->update(['is_reg_bapintar' => 1]);
                return redirect('/pegawai/aktivitas/harian');
            } else {
                toastr()->error('Email ini sudah ada');
                return redirect('/pegawai/aktivitas/harian');
            }
        }
    }
    public function superadmin()
    {
        $pegawai = Pegawai::with('jabatan.kelas', 'pangkat')->get();
        $persentase_tpp = Parameter::first()->persentase_tpp;
        $data    = $pegawai->map(function ($item) use ($persentase_tpp) {
            if ($item->jabatan == null) {
                $item->nama_jabatan = null;
                $item->nama_kelas = null;
                $item->basic_tpp = 0;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = 0;
                $item->jumlah_persentase = $persentase_tpp;
                $item->total_pagu = 0;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  0;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  0;
                $item->total_tpp =  0;
            } else {
                $item->nama_jabatan = $item->jabatan->nama;
                $item->nama_kelas = $item->jabatan->kelas->nama;
                $item->basic_tpp = $item->jabatan->kelas->nilai;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase = $persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu = ceil($item->basic_tpp * ($persentase_tpp + $item->tambahan_persen_tpp) / 100);
                $item->persen_disiplin = 100;
                $item->total_disiplin =  $item->total_pagu * 40 / 100;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
            }
            return $item;
        });
        $tpp_pemko = $data->sum('total_tpp');
        $asn = $pegawai->count();
        $skpd = Skpd::get()->count();
        $dataskpd = Skpd::get();
        return view('superadmin.home', compact('tpp_pemko', 'asn', 'skpd', 'dataskpd'));
    }

    public function puskesmas()
    {
        return view('puskesmas.home');
    }

    public function export()
    {
        $data = Pegawai::orderBy('skpd_id', 'asc')->get()->map(function ($item) {
            if ($item->jabatan == null) {
                $atasan = null;
            } else {
                if ($item->jabatan->atasan == null) {
                    $atasan = null;
                } else {
                    $atasan = $item->jabatan->atasan->nama;
                }
            }
            $item->nama_jabatan = $item->jabatan == null ? null : $item->jabatan->nama;
            if ($item->jabatan == null) {
                $kelas = null;
            } else {
                if ($item->jabatan->kelas == null) {
                    $kelas = null;
                } else {
                    $kelas = $item->jabatan->kelas->nama;
                }
            }
            $item->kelas = $kelas;
            $item->skpd = $item->skpd == null ? null : $item->skpd->nama;

            $item->atasan = $atasan;
            return $item->only(['nip', 'nama', 'kelas', 'nama_jabatan', 'skpd', 'status_pns', 'is_aktif', 'atasan']);
        });


        return view('export', compact('data'));
    }
    public function exportPegawai(Request $req)
    {
        $skpd_id = $req->skpd_id;
        return Excel::download(new PegawaiExport($skpd_id), 'pegawai.xlsx');
    }

    public function skpd_id()
    {
        return Auth::user()->skpd->id;
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function hitungTppPegawai($item, $view_aktivitas, $capaianMenit)
    {
        if ($item->jabatan == null) {
            $item->nama_jabatan   = null;
            $item->jenis_jabatan  = null;
            $item->nama_kelas     = null;
            $item->nama_pangkat   = $item->pangkat == null ? null : $item->pangkat->nama . ' (' . $item->pangkat->golongan . ')';
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
    }

    public function dataPegawaiDinkes($data, $view_aktivitas, $capaianMenit)
    {
        $data->getCollection()->transform(function ($item, $key) use ($view_aktivitas, $capaianMenit) {
            $this->hitungTppPegawai($item, $view_aktivitas, $capaianMenit);
            return $item;
        });
        return $data;
    }

    public function dataPegawai($data, $view_aktivitas, $capaianMenit)
    {
        $data->map(function ($item, $key) use ($view_aktivitas, $capaianMenit) {
            $this->hitungTppPegawai($item, $view_aktivitas, $capaianMenit);
            return $item;
        });
        return $data;
    }

    public function admin()
    {
        $persentase_tpp = (float) Parameter::where('name', 'persentase_tpp')->first()->value;
        $countJabatan   = DB::table('jabatan')->where('skpd_id', $this->skpd_id())->get()->count();
        $month          = Carbon::now()->month;
        $year           = Carbon::now()->year;
        $view_aktivitas = View_aktivitas_pegawai::where('tahun', $year)->where('bulan', $month)->get();
        $capaianMenit   = Parameter::where('name', 'menit')->first()->value;

        if (Auth::user()->skpd->kode_skpd == '1.02.01.') {
            $pegawai        = Pegawai::with('jabatan.kelas', 'pangkat')->where('skpd_id', $this->skpd_id())->where('is_aktif', 1)->orderBy('urutan', 'ASC')->paginate(10);
            $data           = $this->dataPegawaiDinkes($pegawai, $view_aktivitas, $capaianMenit);
        } else {
            $pegawai        = Pegawai::with('jabatan.kelas', 'pangkat')->where('skpd_id', $this->skpd_id())->where('is_aktif', 1)->orderBy('urutan', 'ASC')->get();
            $data           = $this->dataPegawai($pegawai, $view_aktivitas, $capaianMenit);
        }

        $countPegawai   = $pegawai->count();
        return view('admin.home', compact('data', 'persentase_tpp', 'countPegawai', 'countJabatan', 'month', 'year', 'capaianMenit'));
    }

    public function adminUp($id, $urutan)
    {
        //Pegawai Yang Di Down
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan', $urutan - 1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Up
        Pegawai::find($id)->update([
            'urutan' => $urutan - 1
        ]);

        return redirect('/home/admin');
    }

    public function adminDown($id, $urutan)
    {
        //Pegawai Yang Di Up
        Pegawai::where('skpd_id', $this->skpd_id())->where('urutan', $urutan + 1)->first()->update([
            'urutan' => $urutan
        ]);

        //Pegawai Yang Di Down
        Pegawai::find($id)->update([
            'urutan' => $urutan + 1
        ]);

        return redirect('/home/admin');
    }

    public function pegawai()
    {
        $pegawai = Pegawai::where('user_id', Auth::user()->id)->get();
        $persentase_tpp = (float) Parameter::where('name', 'persentase_tpp')->first()->value;

        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $aktivitas = Aktivitas::where('pegawai_id', $pegawai->first()->id)->whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get();
        $jmlmenit = $aktivitas->where('validasi', 1)->sum('menit');

        $acc     = $aktivitas->where('validasi', 1)->count();
        $tolak   = $aktivitas->where('validasi', 2)->count();
        $pending = $aktivitas->where('validasi', 0)->count();

        $riwayatTpp = RekapTpp::where('nip', Auth::user()->username)->orderBy('bulan', 'DESC')->orderBy('tahun', 'DESC')->get();
        //$data = $pegawai;
        $data = $pegawai->map(function ($item) use ($persentase_tpp, $jmlmenit, $month, $year) {

            if ($item->jabatan == null) {
                $item->nama_jabatan = null;
                $item->nama_kelas = null;
                $item->basic_tpp = 0;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = 0;
                $item->jumlah_persentase = $persentase_tpp;
                $item->total_pagu = 0;
                $item->persen_disiplin = 100;
                $item->total_disiplin =  0;
                $item->persen_produktivitas = 100;
                $item->total_produktivitas =  0;
                $item->total_tpp =  0;
                $item->pph21 =  0;
                $item->bpjs =  0;
            } else {
                $item->nama_jabatan = $item->jabatan->nama;
                $item->nama_kelas = $item->jabatan->kelas->nama;
                $item->basic_tpp = $item->jabatan->kelas->nilai;
                $item->persentase_tpp = $persentase_tpp;
                $item->tambahan_persen_tpp = $item->jabatan->tambahan_persen_tpp;
                $item->jumlah_persentase = $persentase_tpp + $item->jabatan->tambahan_persen_tpp;
                $item->total_pagu = ceil($item->basic_tpp * ($persentase_tpp + $item->tambahan_persen_tpp) / 100);
                $presensi = Presensi::where('pegawai_id', $item->id)->where('bulan', $month)->where('tahun', $year)->first();

                $item->persen_disiplin =  ($presensi == null ? 0 : $presensi->persen) == null ? 0 : $presensi->persen;
                $item->total_disiplin =  $item->total_pagu * (0.4 * $item->persen_disiplin) / 100;
                $item->persen_produktivitas = 100;
                if ($jmlmenit >= 6750) {
                    $item->total_produktivitas =  $item->total_pagu * 60 / 100;
                } else {
                    $item->total_produktivitas =  0;
                }
                $item->total_tpp =  $item->total_disiplin + $item->total_produktivitas;
                if ($item->pangkat == null) {
                    $item->pph   = 0;
                    $item->pph_angka =  0;
                } else {
                    $item->pph   = $item->pangkat->pph;
                    $item->pph_angka =  $item->total_tpp * $item->pph / 100;
                }

                $item->bpjs =  0;
            }
            return $item;
        })->first();

        return view('pegawai.home', compact('data', 'acc', 'tolak', 'pending', 'jmlmenit', 'riwayatTpp'));
    }

    public function walikota()
    {
        $data = Skp2023::where('penilai', 'walikota')->where('is_aktif', 1)->get();
        $data->map(function ($item) {
            $item->nilai_tw1 = nilaiSkp($item->rhk_tw1, $item->rpk_tw1);
            $item->nilai_tw2 = nilaiSkp($item->rhk_tw2, $item->rpk_tw2);
            $item->nilai_tw3 = nilaiSkp($item->rhk_tw3, $item->rpk_tw3);
            $item->nilai_tw4 = nilaiSkp($item->rhk_tw4, $item->rpk_tw4);
            return $item;
        });

        return view('walikota.home', compact('data'));
    }

    public function pegawaiSubMonth()
    {
        return view('pegawai.homesubmonth');
    }
}
