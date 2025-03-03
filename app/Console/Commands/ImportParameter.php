<?php

namespace App\Console\Commands;

use App\Imports\ParameterTPPimport;
use App\Imports\ParameterTppPuskesmasImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportParameter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importparameter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Parameter TPP';

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
        $filepuskes = public_path('persentpp/parametertpppuskesmas.xlsx');
        Excel::import(new ParameterTppPuskesmasImport, $filepuskes);
        $file = public_path('persentpp/parametertpp.xlsx');
        Excel::import(new ParameterTPPimport, $file);
        $this->info('Import selesai!');
    }
}
