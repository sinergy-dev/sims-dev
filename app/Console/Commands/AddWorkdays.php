<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use App\TimesheetWorkdays;
use DatePeriod;
use DateInterval;
use DateTime;

use GuzzleHttp\Client;

class AddWorkdays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AddWorkdays:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Workdays Every Month';

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
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $now = date('Y-m-d');

        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key='.env('GOOGLE_API_KEY'));
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_date = collect();
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( ( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate ) )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
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
            // return ["date" => $item];
            // return (object) array('date' => $item);
            return $item;
        });

        // if ($remarks == 'holiday') {
        //     return $holiday_indonesia_final_detail;
        // } else {
        //     return $workDaysMinHolidayKeyed;
        // }

        // print_r($workDaysMinHolidayKeyed);

        if ($now == $startDate) {
            $store = new TimesheetWorkdays();
            $store->workdays = count($workDaysMinHolidayKeyed);
            $store->month = date("n");
            $store->year = date('Y');
            $store->date_add = Carbon::now()->toDateTimeString();
            $store->save();
        }
    }
}
