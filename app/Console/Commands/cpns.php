<?php

namespace App\Console\Commands;

use App\Pegawai;
use App\RekapTpp;
use Illuminate\Console\Command;

class cpns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cpns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate data cpns';

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
        $data = Pegawai::get()->map(function ($item) {
            $item->update([
                'tahun' => substr($item->nip, 8, 4),
                'status_pns' => substr($item->nip, 8, 4) == '2022' ? 'cpns' : 'pns',
            ]);
            return $item;
        });

        $rekapTpp = RekapTpp::get()->map(function ($item) {
            $item->update([
                'status_pns' => substr($item->nip, 8, 4) == '2022' ? 'cpns' : 'pns',
            ]);
            return $item;
        });
        return 'sukses';
    }
}
