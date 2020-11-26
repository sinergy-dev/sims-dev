<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use GuzzleHttp\Client;
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
        // $client = new Client();
        // $client = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key=' . env('GOOGLE_API_YEY'));
        // $variable = json_decode($client->getBody())->items;
        // $i = 0;
        // foreach ($variable as $key => $value) {
        //   if(strpos($value->summary,'Cuti Bersama') === 0){
        //     if(strpos($value->start->date , date('Y')) === 0){
        //       $i++;
        //     }
        //   }
        // }
        // $total_cuti = 12 - $i;
        // $reset = User::select('nik','name','status_karyawan')->where('nik',1191094060)->first();
        $reset = User::select('nik','name',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'status_karyawan')->get();

        foreach ($reset as $data) {
            // print_r($data->name . $data->nik . "\n");
            // if ($data->date_of_entrys >= '365') {
            //     if ($data->status_karyawan == 'belum_cuti') {
            //         $update = User::where('nik',$data->nik)->first();
            //         $update->status_karyawan = 'cuti';
            //         $update->update();
            //         syslog(LOG_ERR, $update->nik . " - " . $update->name . "leaving permit permission updated ");
            //     }
            // }
            if ($data->status_karyawan == 'belum_cuti') {
                $update = User::where('nik',$data->nik)->first();
                $update->status_karyawan = 'cuti';
                $update->update();
                syslog(LOG_ERR, $update->nik . " - " . $update->name . "leaving permit permission updated ");
            }
            
        }
        // $update = User::where('nik',$reset->nik)->first();
        // $update->status_karyawan = 'cuti';
        // $update->cuti2 = $total_cuti;
        // $update->update();


        syslog(LOG_ERR, "All user has been check for leaving permit");

    }
}
