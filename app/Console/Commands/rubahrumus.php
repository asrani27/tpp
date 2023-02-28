<?php

namespace App\Console\Commands;

use App\Jabatan;
use Illuminate\Console\Command;

class rubahrumus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rubahrumus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rumus TPP 2023';

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
        $persen_BK = Jabatan::get();
        $persen_PK = Jabatan::where('rs_puskesmas_id', null)->get();
        $persen_PK_dinkes = Jabatan::where('rs_puskesmas_id', '!=', null)->get();
        //rubah global beban kerja 37,5 % dan prestasi Kerja 52,5%
        foreach ($persen_BK as $key => $item) {
            $item->persen_beban_kerja = 37.5;
            $item->save();
        }
        foreach ($persen_PK as $key => $item) {
            $item->persen_prestasi_kerja = 52.5;
            $item->save();
        }

        //Persen PK RS, Puskesmas Dan UPT Lab
        foreach ($persen_PK_dinkes as $key => $item) {
            $item->persen_prestasi_kerja = 46;
            $item->save();
        }

        //Total Persentase
        foreach ($persen_BK as $key => $item) {
            $item->persentase_tpp = $item->persen_beban_kerja + $item->persen_prestasi_kerja + $item->persen_tambahan_beban_kerja;
            $item->save();
        }
        return 'sukses';
    }
}
