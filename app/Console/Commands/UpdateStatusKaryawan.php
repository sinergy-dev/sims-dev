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
        $client = new Client();
        $startDate = Carbon::now()->format("Y-m-d");
        $endDate = Carbon::now()->endOfYear()->format("Y-m-d");
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GCALENDAR_API_KEY'));
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_details = collect();
        $holiday_indonesia_final_date = collect();
        $holiday_indonesia_final_dates = collect();


        foreach ($holiday_indonesia["items"] as $value) {
            if(( (( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate )) && (($value['summary'] == 'Idul Fitri Joint Holiday') || ($value['summary'] == 'Boxing Day')) )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"]]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
        }

        $holiday_indonesia_final_detail = $holiday_indonesia_final_detail->merge($holiday_indonesia_final_details);

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

        $count = count($holiday_indonesia_final_detail);

        $total_cuti = 12 - $count;
        // $reset = User::select('nik','name','status_karyawan')->where('nik',1191094060)->first();
        $reset = DB::table('users')->select('nik','name',DB::raw('DATEDIFF(NOW(),date_of_entry) AS date_of_entrys'),'status_karyawan')->where('status_karyawan','!=','dummy')->get();

        foreach ($reset as $data) {

            // print_r($data->name . $data->nik . "\n");
            if ($data->date_of_entrys >= '365') {
                if ($data->status_karyawan == 'belum_cuti') {
                    // print_r($data->name . $data->nik . "\n");

                    $update = User::where('nik',$data->nik)->first();
                    $update->status_karyawan = 'cuti';
                    // if (date("n") == 4 && date("j") == 1) {
                        $update->cuti = 0;
                        $update->cuti2 = $count; 
                    // } else {
                        // $update->cuti = $count;
                        // $update->cuti2 = 0; 
                    // }
                    $update->update();
                    syslog(LOG_ERR, $update->nik . " - " . $update->name . "leaving permit permission updated ");
                }
            }
            // else if ($data->status_karyawan == 'belum_cuti') {
            //     $update = User::where('nik',$data->nik)->first();
            //     $update->status_karyawan = 'cuti';
            //     $update->update();
            //     syslog(LOG_ERR, $update->nik . " - " . $update->name . "leaving permit permission updated ");
            // }
            
        }
        // $update = User::where('nik',$reset->nik)->first();
        // $update->status_karyawan = 'cuti';
        // $update->cuti2 = $total_cuti;
        // $update->update();


        syslog(LOG_ERR, "All user has been check for leaving permit");

    }
}
