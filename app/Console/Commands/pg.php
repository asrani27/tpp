<?php

namespace App\Console\Commands;

use App\RekapTpp;
use App\G_puskesmas;
use Illuminate\Console\Command;

class pg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbandingan data puskesmas yang berbeda';

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
        $data = G_puskesmas::get();
        foreach ($data as $d) {
            $check = RekapTpp::where('nip', $d->nip)->where('bulan', '05')->where('tahun', 'tahun')->first();
            if ($check == null) {
                $pembayaran = 0;
            } else {
                $pembayaran = $check->pembayaran;
            }
            $d->update([
                'pembayaran_rekap' => $pembayaran,
            ]);
        }
    }
}
