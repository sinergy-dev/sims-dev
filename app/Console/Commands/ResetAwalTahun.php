<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use GuzzleHttp\Client;
use DB;


class ResetAwalTahun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ResetAwalTahun:resetawaltahun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mendefine nilai cuti2 setiap 1 januari';

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
        $client = new Client();
        $client = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key=' . env('GOOGLE_API_YEY'));
        $variable = json_decode($client->getBody())->items;
        $i = 0;
        foreach ($variable as $key => $value) {
          if(strpos($value->summary,'Cuti Bersama') === 0){
            if(strpos($value->start->date , date('Y')) === 0){
              // echo "</br>". $value->start->date . strpos($value->summary,'Cuti Bersama') . ' - ' . $value->summary . "<br>";
              $i++;
            }
          }
        }

        // $cuti_custom = DB::table('tb_cuti_custom')->count();

        // $total_cuti = $i + $cuti_custom;

        // print_r(12  - $total_cuti);

        $total_cuti = 12 - $i;

        echo("cuti". $total_cuti);
        
        $reset = User::select('nik','name')->where('status_karyawan','cuti')->get();

        // $update = User::where('nik',$reset->nik)->first();
        //     // $data->cuti2 = 12 - $total_cuti;
        // $update->cuti2 = $total_cuti;
        // $update->update();

        foreach ($reset as $data) {
            $update = User::where('nik',$data->nik)->first();
            // $data->cuti2 = 12 - $total_cuti;
            $update->cuti2 = $total_cuti;
            $update->update();

        }
    }
}
