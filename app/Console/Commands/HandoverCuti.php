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
        syslog(LOG_ERR, "Handover Cuti Start");
        syslog(LOG_ERR, "-------------------------");

        foreach ($totalcuti as $data) {
            syslog(LOG_ERR, "Handover Cuti for " . $data->name);
            // print_r($data->name . $data->nik . "\n");
            $update = User::where('nik',$data->nik)->first();
            syslog(LOG_ERR, "before cuti : " . $data->cuti);
            syslog(LOG_ERR, "before cuti2 : " . $data->cuti2);
            $data->cuti = $data->cuti2;

            syslog(LOG_ERR, "-------------------------");
            syslog(LOG_ERR, "after cuti : " . $data->cuti);
            syslog(LOG_ERR, "after cuti2 : " . $data->cuti2);
            syslog(LOG_ERR, "-------------------------");
            
            $data->save();

        }
        //

        syslog(LOG_ERR, "sukses");
    }
}
