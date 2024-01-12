<?php

namespace App\Console\Commands;

use App\Pegawai;
use App\DataIdCard;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class idcard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idcard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $pegawai = DataIdCard::get()->pluck('nip');
        $np = array();
        foreach ($pegawai as $p) {
            $removestring = str_replace(array("\r", ' '), '', $p);
            array_push($np, $removestring);
        }
        $dp = Pegawai::whereIn('nip', $np)->get()->map(function ($item) {
            $card['nip'] = $item->nip;
            $card['nama_baru'] = $item->nama;
            $card['jabatan_baru'] = $item->jabatan->nama;
            $card['skpd_baru'] = $item->skpd->nama;
            return $card;
        });

        foreach ($dp as $cd) {
            $client = new Client();

            $response = $client->request("POST", "https://idcardpegawai.banjarmasinkota.go.id/api/updatePegawai", [
                'form_params' => $cd,
            ]);
        }
        return 'sukses';
    }
}
