<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class HandoverCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HandoverCuti:handovercuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handover cuti2 ke cuti setiap desember 31';

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
     * @return mixed
     */
    public function handle()
    {
        $totalcuti = User::select('cuti2','nik','name')->where('status_karyawan','cuti')->get(); 

        foreach ($totalcuti as $data) {
            // print_r($data->name . $data->nik . "\n");
            $update = User::where('nik',$data->nik)->first();
            $data->cuti = $data->cuti2;
            $data->save();
        }
        //

        syslog(LOG_ERR, "sukses");
    }
}
