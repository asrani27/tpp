<?php

namespace App\Console\Commands;

use App\Sanksi;
use App\Jabatan;
use App\Aktivitas;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetujuiSistem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setujuisistem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktivitas Yang Di Setujui Sistem';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $tanggal   = Carbon::today()->subDays(6)->format('Y-m-d');
        // $aktivitas = Aktivitas::where('validasi', 0)->where('tanggal', '<=', $tanggal)->get();
        $startDate = Carbon::create(2025, 12, 1)->startOfDay();
        $endDate   = Carbon::create(2025, 12, 15)->endOfDay();

        $aktivitas = Aktivitas::where('validasi', 0)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();
        $aktivitas->map(function ($item) {
            if ($item->pegawai->jabatan == null) {
            } else {
                $item->nip      = $item->pegawai->nip;
                $item->nama     = $item->pegawai->nama;
                $item->jabatan  = $item->pegawai->jabatan->nama;
                $item->skpd     = $item->pegawai->skpd->nama;

                $check = $item->pegawai->jabatan->atasan == null ? Jabatan::where('sekda', 1)->first() : $item->pegawai->jabatan->atasan;
                if ($check->pegawai == null) {
                    //Jika Pegawai kosong, Check Lagi Apakah ada PLT atau Tidak
                    if ($check->pegawaiPlt == null) {
                        $atasan = $check;
                    } else {
                        // Cek Lagi Apakah yang memPLT atasan adalah bawahan langsung, menghindari aktifitas menilai diri sendiri
                        if ($item->pegawai->id == $check->pegawaiPlt->id) {
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
                $item->nip_penilai = $atasan->pegawai == null ? $atasan->pegawaiPlt == null ? null : $atasan->pegawaiPlt->nip : $atasan->pegawai->nip;
                $item->nama_penilai = $atasan->pegawai  == null ? $atasan->pegawaiPlt == null ? null : $atasan->pegawaiPlt->nama : $atasan->pegawai->nama;
                $item->skpd_penilai = $atasan->pegawai == null ? $atasan->pegawaiPlt == null ? null : $atasan->skpd->nama : $atasan->skpd->nama;
                $item->jabatan_penilai = $atasan->nama;
                return $item;
            }
        });

        DB::beginTransaction();
        try {
            foreach ($aktivitas as $item) {
                $u = Aktivitas::find($item->id);
                $u->validasi = 1;
                $u->validator = 999999;
                $u->save();

                $s = new Sanksi;
                $s->tanggal_nilai    = Carbon::parse($item->tanggal)->addDays(6)->format('Y-m-d');
                $s->tanggal_aktivitas = $item->tanggal;
                $s->nip_penilai     = $item->nip_penilai;
                $s->nama_penilai    = $item->nama_penilai;
                $s->jabatan_penilai = $item->jabatan_penilai;
                $s->skpd_penilai    = $item->skpd_penilai;
                $s->aktivitas_id    = $item->id;
                $s->aktivitas       = $item->deskripsi;
                $s->nip             = $item->nip;
                $s->nama            = $item->nama;
                $s->jabatan         = $item->jabatan;
                $s->skpd            = $item->skpd;
                $s->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
