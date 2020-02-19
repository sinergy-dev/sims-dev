<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class UpdateStatusKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateStatusKaryawan:updatestatuskaryawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status karyawan yang sudah lebih dari setahun jadi bisa cuti';

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

        $reset = User::select('nik','name',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'status_karyawan')->get();

        foreach ($reset as $data) {
            // print_r($data->name . $data->nik . "\n");
            if ($data->date_of_entrys >= '365') {
                if ($data->status_karyawan == 'belum_cuti') {
                   $update = User::where('nik',$data->nik)->first();
                   $data->status_karyawan = 'cuti';
                   $data->update();
                }
            }
            
        }
    }
}
