<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use GuzzleHttp\Client;


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
            if(strpos($value->start->date ,date('Y')) === 0){
              echo $value->start->date . strpos($value->summary,'Cuti Bersama') . ' - ' . $value->summary . "<br>";
              $i++;
            }
          }
        }
        
        $reset = User::select('nik','name')->where('status_karyawan','cuti')->get();

        foreach ($reset as $data) {
            $update = User::where('nik',$data->nik)->first();
            $data->cuti2 = 12 - $i;
            $data->update();

        }
    }
}
