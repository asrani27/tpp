<?php

namespace App\Console\Commands;

use App\Pegawai;
use App\Presensi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TarikPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tarikpresensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tarik Data Rekap Presensi';

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
        $data = DB::connection('presensi')->table('ringkasan')->get();
        $pegawai = Pegawai::where('is_aktif', 1)->get();
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
        foreach ($pegawai as $item) {
            $check = Presensi::where('pegawai_id', $item->id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
            if ($check == null) {
                $p = new Presensi;
                $p->pegawai_id = $item->id;
                $p->bulan = $bulan;
                $p->tahun = $tahun;
                $p->skpd_id = $item->skpd_id;
                $tarik = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();

                $p->persen = $tarik == null ? 0 : $tarik->persen_kehadiran;
                $p->save();
            } else {
                $tarik = DB::connection('presensi')->table('ringkasan')->where('nip', $item->nip)->where('bulan', $bulan)->where('tahun', $tahun)->first();

                $item->update([
                    'persen' => $tarik == null ? 0 : $tarik->persen_kehadiran,
                ]);
            }
        }
    }
}
