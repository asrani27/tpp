<?php

namespace App\Http\Controllers;

use App\Skp;

use App\Jabatan;
use App\Skp2023;
use App\Aktivitas;
use App\Skp2023Jf;
use Carbon\Carbon;
use App\Skp2023Jpt;
use App\Skp_periode;
use App\WhitelistNip;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AktivitasController extends Controller
{

    public function user()
    {
        return Auth::user();
    }

    public function index()
    {
        // if ($this->user()->is_reg_bapintar != 1) {
        //     return view('bapintar');
        // }

        if ($this->user()->pegawai->jabatan == null) {
            toastr()->info('Tidak bisa melakukan aktivitas, Karena Tidak memiliki jabatan,untuk melihat riwayat silahkan ke menu laporan aktivitas');
            return back();
        }
        $person = $this->user()->pegawai;

        //cek atasan apakah PLT atau Bukan
        $check = $this->user()->pegawai->jabatan->atasan == null ? Jabatan::where('sekda', 1)->first() : $this->user()->pegawai->jabatan->atasan;
        //dd($person, $check->pegawai);
        if ($check->pegawai == null) {
            //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
            if ($check->pegawaiPlt == null) {
                $atasan = $check;
            } else {
                // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                if ($person->id == $check->pegawaiPlt->id) {
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

        $aktivitasBelumDinilai = Aktivitas::where('pegawai_id', $this->user()->pegawai->id)->where('validasi', 0)->orderBy('id', 'DESC')->paginate(20);
        //$data = $this->user()->pegawai->aktivitas()->orderBy('tanggal','DESC')->orderBy('jam_mulai','DESC')->paginate(10);

        return view('pegawai.aktivitas.index', compact('atasan', 'person', 'aktivitasBelumDinilai'));
    }

    public function detail($bulan, $tahun)
    {
        $data = Aktivitas::where('pegawai_id', $this->user()->pegawai->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'DESC')->orderBy('jam_mulai', 'DESC')->get();
        return view('pegawai.aktivitas.detail', compact('data', 'bulan', 'tahun'));
    }
    public function add()
    {
        $tahun = Carbon::now()->year;

        $es = Auth::user()->pegawai;
        if ($es->eselon_id == null) {
            $eselon = null;
            $rencana_aksi = null;
        } else {
            $eselon = substr_replace($es->eselon->nama, "", -2);
            // $response = Http::get('https://kayuhbaimbai.banjarmasinkota.go.id/api/rencana-aksi/' . $es->nip . '/2024');
            // if ($response->getStatusCode() == 200) {
            //     $rencana_aksi = json_decode($response->getBody()->getContents())->data;
            // } else {
            $rencana_aksi = [];
            // }
        }

        if (Auth::user()->pegawai->skp->count() == 0) {
            toastr()->info('Harap isi SKP dulu');
            return back();
        }

        if (Auth::user()->pegawai->skp->where('is_aktif', 1)->first() == null) {
            toastr()->info('Aktifkan SKP Anda Terlebih dahulu');
            return back();
        }

        $skp = $this->user()->pegawai->skp->where('is_aktif', 1)->first();

        if ($skp->jenis == 'JPT') {
            $skp = Skp2023Jpt::where('skp2023_id', $skp->id)->get();
        } else {
            $skp = Skp2023Jf::where('skp2023_id', $skp->id)->get();
        }

        $data = Aktivitas::where('pegawai_id', $this->user()->pegawai->id)->latest('id')->first();

        if ($data == null) {
            $tanggal   = Carbon::now()->format('Y-m-d');
            $jam_mulai = Carbon::parse('08:01')->format('H:i');
            $jam_selesai = Carbon::parse('09:00')->format('H:i');
        } else {
            $tanggal = $data->tanggal;
            $jam_mulai = Carbon::parse($data->jam_selesai)->addMinute()->format('H:i');
            $jam_selesai = Carbon::parse($data->jam_selesai)->addHour()->format('H:i');
        }

        return view('pegawai.aktivitas.create', compact('skp', 'tanggal', 'jam_mulai', 'jam_selesai', 'eselon', 'rencana_aksi'));
    }

    public function edit($id)
    {
        $aktivitas = Aktivitas::find($id);

        if ($this->user()->pegawai->id != $aktivitas->pegawai_id) {
            toastr()->error('Aktivitas tidak bisa di edit, bukan milik anda', 'Authorize');
            return back();
        } else {

            $es = Auth::user()->pegawai;
            if ($es->eselon_id == null) {
                $eselon = null;
                $rencana_aksi = null;
            } else {
                $eselon = substr_replace($es->eselon->nama, "", -2);
                $response = Http::get('https://kayuhbaimbai.banjarmasinkota.go.id/api/rencana-aksi/' . $es->nip . '/2024');
                if ($response->getStatusCode() == 200) {
                    $rencana_aksi = json_decode($response->getBody()->getContents())->data;
                } else {
                    $rencana_aksi = [];
                }
            }

            $tahun  = Carbon::now()->year;
            $skp = $this->user()->pegawai->skp->where('is_aktif', 1)->first();

            if ($skp->jenis == 'JPT') {
                $skp = Skp2023Jpt::where('skp2023_id', $skp->id)->get();
            } else {
                $skp = Skp2023Jf::where('skp2023_id', $skp->id)->get();
            }
            $data   = $aktivitas;
            return view('pegawai.aktivitas.edit', compact('skp', 'data', 'eselon', 'rencana_aksi'));
        }
    }

    public function delete($id)
    {
        $aktivitas = Aktivitas::find($id);
        if ($this->user()->pegawai->id != $aktivitas->pegawai_id) {
            toastr()->error('Aktivitas tidak bisa di hapus, bukan milik anda', 'Authorize');
            return redirect('/pegawai/aktivitas/harian');
        } else {
            $aktivitas->delete();
            toastr()->success('Aktivitas berhasil Di Hapus');
            return redirect('/pegawai/aktivitas/harian');
        }
    }

    public function checkdate($param)
    {
        $day1 = Carbon::today()->subDays(1)->format('Y-m-d');
        $day2 = Carbon::today()->format('Y-m-d');

        if ($param == $day1) {
            return true;
        } elseif ($param == $day2) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks for time conflicts with existing activities for the same user and date.
     *
     * @param string $tanggal
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int $pegawai_id
     * @return bool True if there is a conflict, false otherwise.
     */
    private function hasTimeConflict(string $tanggal, string $jam_mulai, string $jam_selesai, int $pegawai_id): bool
    {
        $newStart = Carbon::parse($jam_mulai . ':00');
        $newEnd = Carbon::parse($jam_selesai . ':00');

        $existingActivities = Aktivitas::where('tanggal', $tanggal)
            ->where('pegawai_id', $pegawai_id)
            ->get();

        foreach ($existingActivities as $activity) {
            $existingStart = Carbon::parse($activity->jam_mulai);
            $existingEnd = Carbon::parse($activity->jam_selesai);

            // Check for overlap:
            // New activity starts before or at the same time as an existing one ends AND
            // New activity ends after or at the same time as an existing one starts.
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return true; // Conflict found
            }
        }

        return false; // No conflict
    }

    /**
     * Checks for time conflicts with existing activities for the same user and date,
     * excluding a specific activity by its ID.
     *
     * @param string $tanggal
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int $pegawai_id
     * @param int $excludeId
     * @return bool True if there is a conflict, false otherwise.
     */
    private function hasTimeConflictForUpdate(string $tanggal, string $jam_mulai, string $jam_selesai, int $pegawai_id, int $excludeId): bool
    {
        $newStart = Carbon::parse($jam_mulai . ':00');
        $newEnd = Carbon::parse($jam_selesai . ':00');

        $existingActivities = Aktivitas::where('tanggal', $tanggal)
            ->where('pegawai_id', $pegawai_id)
            ->where('id', '!=', $excludeId) // Exclude the current activity being updated
            ->get();

        foreach ($existingActivities as $activity) {
            $existingStart = Carbon::parse($activity->jam_mulai);
            $existingEnd = Carbon::parse($activity->jam_selesai);

            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                return true; // Conflict found
            }
        }

        return false; // No conflict
    }

    /**
     * Validates if the given date is within the active SKP period for the user.
     * Returns the active SKP if valid, null otherwise.
     *
     * @param string $tanggal
     * @param int $pegawai_id
     * @return Skp2023|null
     */
    private function validateSkpPeriod(string $tanggal, int $pegawai_id): ?Skp2023
    {
        $skp = Skp2023::where('pegawai_id', $pegawai_id)->where('is_aktif', 1)->first();
        if (!$skp) {
            return null; // No active SKP
        }

        $tgl = Carbon::parse($tanggal);
        $skpMulai = Carbon::parse($skp->mulai);
        $skpSampai = Carbon::parse($skp->sampai);

        if ($tgl->gte($skpMulai) && $tgl->lte($skpSampai)) {
            return $skp; // Date is within SKP period
        }

        return null; // Date is outside SKP period
    }

    /**
     * Handles the creation of the Aktivitas record.
     *
     * @param Request $req
     * @param Skp2023 $skp
     * @return \Illuminate\Http\RedirectResponse
     */
    private function createAktivitas(Request $req, Skp2023 $skp)
    {
        if (strtotime($req->jam_selesai) > strtotime($req->jam_mulai)) {
            $attr = $req->all();
            $attr['pegawai_id'] = $this->user()->pegawai->id;
            $attr['menit'] = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
            $attr['jenis'] = $skp->jenis;
            $attr['rencana_aksi'] = $req->rencana_aksi;

            Aktivitas::create($attr);
            toastr()->success('Aktivitas berhasil Di Simpan');
            return redirect('pegawai/aktivitas/harian');
        } else {
            toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
            $req->flash();
            return back();
        }
    }

    /**
     * Handles the update of the Aktivitas record.
     *
     * @param Request $req
     * @param int $id
     * @param Skp2023 $skp
     * @return \Illuminate\Http\RedirectResponse
     */
    private function updateAktivitasRecord(Request $req, int $id, Skp2023 $skp)
    {
        if (strtotime($req->jam_selesai) > strtotime($req->jam_mulai)) {
            $attr = $req->all();
            // $attr['pegawai_id'] is already set and should not change on update.
            $attr['menit'] = (strtotime($req->jam_selesai) - strtotime($req->jam_mulai)) / 60;
            $attr['jenis'] = $skp->jenis;
            $attr['rencana_aksi'] = $req->rencana_aksi;

            Aktivitas::find($id)->update($attr);
            toastr()->success('Aktivitas berhasil Di Simpan');
            return redirect('pegawai/aktivitas/harian');
        } else {
            toastr()->error('Jam Selesai Tidak Bisa Kurang Dari Jam Mulai');
            $req->flash();
            return back();
        }
    }

    public function store(Request $req)
    {
        $bulan = Carbon::parse($req->tanggal)->format('m');
        $tahun = Carbon::parse($req->tanggal)->format('Y');
        $pegawai_id = $this->user()->pegawai->id;
        $tanggal = $req->tanggal;
        $jam_mulai = $req->jam_mulai;
        $jam_selesai = $req->jam_selesai;

        // 1. Check if SKPD is locked for the month
        if (lockSkpd(Auth::user()->pegawai->skpd_id, $bulan, $tahun) == 1) {
            toastr()->error('Aktivitas Pada Bulan ' . convertBulan($bulan) . ' telah Di Kunci');
            return back();
        }

        // 2. Date range validation (only if not whitelisted)
        if (whitelist(Auth::user()->username) != true) {
            if (!checkDateInRange($tanggal)) { // Assuming checkDateInRange is a global helper
                $today = Carbon::today()->format('d-m-Y');
                $yesterday = Carbon::yesterday()->format('d-m-Y');
                toastr()->error('Input Aktivitas hanya bisa pada tanggal ' . $today . ' & ' . $yesterday);
                return back();
            }
        }

        // 3. Time conflict validation
        if ($this->hasTimeConflict($tanggal, $jam_mulai, $jam_selesai, $pegawai_id)) {
            toastr()->error('Jam ini telah di gunakan');
            $req->flash();
            return back();
        }

        // 4. SKP period validation
        $skp = $this->validateSkpPeriod($tanggal, $pegawai_id);
        if (!$skp) {
            toastr()->error('Tanggal Berada di luar Periode SKP yang di aktifkan atau SKP tidak ditemukan');
            $req->flash();
            return back();
        }

        // 5. Create Aktivitas
        return $this->createAktivitas($req, $skp);
    }

    public function update(Request $req, $id)
    {
        $pegawai_id = $this->user()->pegawai->id;
        $tanggal = $req->tanggal;
        $jam_mulai = $req->jam_mulai;
        $jam_selesai = $req->jam_selesai;

        // 1. Date range validation (only if not whitelisted)
        if (whitelist(Auth::user()->username) != true) {
            if (!checkDateInRange($tanggal)) { // Assuming checkDateInRange is a global helper
                $today = Carbon::today()->format('d-m-Y');
                $yesterday = Carbon::yesterday()->format('d-m-Y');
                toastr()->error('Input Aktivitas hanya bisa pada tanggal ' . $today . ' & ' . $yesterday);
                return back();
            }
        }

        // 2. Time conflict validation (excluding current activity)
        if ($this->hasTimeConflictForUpdate($tanggal, $jam_mulai, $jam_selesai, $pegawai_id, $id)) {
            toastr()->error('Jam ini telah di gunakan');
            $req->flash();
            return back();
        }

        // 2. SKP period validation
        $skp = $this->validateSkpPeriod($tanggal, $pegawai_id);
        if (!$skp) {
            toastr()->error('Tanggal Berada di luar Periode SKP yang di aktifkan atau SKP tidak ditemukan');
            $req->flash();
            return back();
        }

        // 3. Update Aktivitas
        return $this->updateAktivitasRecord($req, $id, $skp);
    }

    public function keberatan()
    {
        $data = Aktivitas::where('pegawai_id', $this->user()->pegawai->id)->where('validasi', 2)->paginate(10);
        if (Auth::user()->pegawai->jabatan->atasan == null) {
            //berarti kepala dinas
            toastr()->error('Kepala Dinas/Setda/Staff Ahli Tidak Bisa Mengajukan Keberatan');
            return back();
        } else {
            if (Auth::user()->pegawai->jabatan->atasan->atasan == null) {
                //penilainya sekda
                $atasan_penilai = Jabatan::where('sekda', 1)->first();
                $nama_penilai   = $atasan_penilai->pegawai == null ? $atasan_penilai->pegawaiplt : $atasan_penilai->pegawai;
            } else {
                $atasan_penilai = Auth::user()->pegawai->jabatan->atasan->atasan;
                $nama_penilai   = $atasan_penilai->pegawai == null ? $atasan_penilai->pegawaiplt : $atasan_penilai->pegawai;
            }

            $hasilkeberatan = Aktivitas::where('keberatan', 3)->orWhere('keberatan', 2)->paginate(15);
            return view('pegawai.aktivitas.keberatan', compact('data', 'atasan_penilai', 'nama_penilai', 'hasilkeberatan'));
        }
    }

    public function ajukanKeberatan($id, $penilai_id)
    {
        if ($penilai_id == null) {
            toastr()->error('Atasan Penilai Tidak Ada');
            return back();
        } else {
            $data = Aktivitas::find($id);
            $data->keberatan = 1;
            $data->validator_keberatan = $penilai_id;
            $data->save();
            toastr()->success('Berhasil Di Ajukan');
            return back();
        }
    }
}
