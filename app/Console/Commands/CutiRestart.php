<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class CutiRestart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CutiRestart:cutirestart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart total cuti setiap akhir maret';

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
        //
        $totalcuti = User::select('cuti','nik','name')->get(); 

        foreach ($totalcuti as $data) {
            // print_r($data->name . $data->nik . "\n");
            $update = User::where('nik',$data->nik)->first();
            $data->cuti = 0;
            $data->save();
        }
    }
}
