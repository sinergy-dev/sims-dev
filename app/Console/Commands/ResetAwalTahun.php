<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use GuzzleHttp\Client;
use DB;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;
use DateTime;


class ResetAwalTahun extends Command
{


    //testtt
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
        // $client = new Client();
        // $client = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key=' . env('GOOGLE_API_YEY'));
        // $variable = json_decode($client->getBody())->items;
        // $i = 0;
        // foreach ($variable as $key => $value) {
        //   if(strpos($value->summary,'Cuti Bersama') === 0){
        //     if(strpos($value->start->date , date('Y')) === 0){
        //       // echo "</br>". $value->start->date . strpos($value->summary,'Cuti Bersama') . ' - ' . $value->summary . "<br>";
        //       $i++;
        //     }
        //   }
        // }

        // $cuti_custom = DB::table('tb_cuti_custom')->count();

        // $total_cuti = $i + $cuti_custom;

        // print_r(12  - $total_cuti);

        // $total_cuti = 12 - $i;
        $total_cuti = 12;
        echo($total_cuti);
        syslog(LOG_ERR, $total_cuti);
        // echo(count($holiday_indonesia_final_detail));

        $startDate = Carbon::now()->startOfYear()->format("Y-m-d");
        $endDate = Carbon::now()->endOfYear()->format("Y-m-d");

        $formattedStartDate = Carbon::parse($startDate)->toISOString();
        $formattedEndDate   = Carbon::parse($endDate)->toISOString();

        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?timeMin='. $formattedStartDate .'&timeMax='. $formattedEndDate .'&key='.env('GCALENDAR_API_KEY'));
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_date = collect();
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) && (strstr($value['summary'], "Joint")) || ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) && ($value["summary"] == 'Boxing Day') )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"]]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
        }

        $period = new DatePeriod(
             new DateTime($startDate),
             new DateInterval('P1D'),
             new DateTime($endDate . '23:59:59')
        );

        $workDays = collect();
        foreach($period as $date){
            if(!($date->format("N") == 6 || $date->format("N") == 7)){
                $workDays->push($date->format("Y-m-d"));
            }
        }

        $workDaysMinHoliday = $workDays->diff($holiday_indonesia_final_date->unique());
        $workDaysMinHolidayKeyed = $workDaysMinHoliday->map(function ($item, $key) {
            return ["date" => $item];
            // return (object) array('date' => $item);
            return $item;
        });

        
        // echo(count($holiday_indonesia_final_detail));

        // echo("cuti". $total_cuti);
        syslog(LOG_ERR, "Reset Cuti Start");
        syslog(LOG_ERR, "-------------------------");
        
        $reset = DB::table('users')->select('nik','name','cuti','cuti2')
            ->where('status_karyawan','cuti')
            ->orderBy('name','ASC') 
            ->get();

        // $update = User::where('nik',$reset->nik)->first();
        //     // $data->cuti2 = 12 - $total_cuti;
        // $update->cuti2 = $total_cuti;
        // $update->update();

        $cuti2 = 12-count($holiday_indonesia_final_detail);

        foreach ($reset as $data) {
            syslog(LOG_ERR, "Reset Cuti for " . $data->name);
            syslog(LOG_ERR, "before reset cuti : " . $data->cuti);
            syslog(LOG_ERR, "before reset cuti2 : " . $data->cuti2);
            syslog(LOG_ERR, "-------------------------");

            $update = User::where('nik',$data->nik)->first();
            // $data->cuti2 = 12 - $total_cuti;
            // $update->cuti2 = $total_cuti;
            // $update->update();
            $update->cuti  = $data->cuti2;
            $update->cuti2 = $cuti2;
            syslog(LOG_ERR, "after reset cuti : " . $data->cuti);
            syslog(LOG_ERR, "after reset cuti2 : " . $data->cuti2);
            syslog(LOG_ERR, "-------------------------");
            
            $update->save();

        }
    }
}
