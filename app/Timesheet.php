<?php

namespace App;
use App\TimesheetLockDuration;
use DB;
use Auth;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;
use DateTime;

use GuzzleHttp\Client;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    ///////
    protected $table = 'tb_timesheet';
    protected $primaryKey = 'id';
    protected $fillable = ['nik','schedule', 'date','type','pid','task','phase','level','activity','duration','status','date_add','point_mandays'];
    public $timestamps = false;

    protected $appends = ['planned', 'threshold', 'plannedMonth'];

    public function getPlannedAttribute($month,$year)
    {
        $this->month = $month;
        $this->year = $year;
        if (isset($this->month)) {
            $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];
            foreach($this->month as $month){
                $date = Carbon::parse($month);
                // Get the numeric representation of the month (1 to 12)
                $numericMonth = $date->month;
                // return $numericMonth;

                if (isset($this->year)) {
                    $startDate = Carbon::createFromDate($this->year, 1, 1);
                }else{
                    $startDate = Carbon::now();
                }

                $startDate->month($numericMonth);

                if (isset($this->year)) {
                    $endDate = Carbon::createFromDate($this->year, 1, 1);
                }else{
                    $endDate = Carbon::now();
                }

                $endDate->month($numericMonth);

                $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                $workdays = $this->getWorkDays($startDateFinal,$endDateFinal,"workdays");
                $arrMonthMandays[$numericMonth-1] = $arrMonthMandays[$numericMonth-1]+count($workdays);
            }

            return $sumMandays = array_sum($arrMonthMandays);
        }else{
            $startDate = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
            return $workdays = count($workdays);
        }
    }

    public function getPlannedMonthAttribute()
    {
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        return $workdays = count($workdays);
    }

    public function getThresholdAttribute($month,$year)
    {
        $this->month = $month;
        $this->year = $year;

        if (isset($this->month)) {
            $arrMonthMandays = [0,0,0,0,0,0,0,0,0,0,0,0];
            foreach($this->month as $month){
                $date = Carbon::parse($month);
                // Get the numeric representation of the month (1 to 12)
                $numericMonth = $date->month;
                // return $numericMonth;

                if (isset($this->year)) {
                    $startDate = Carbon::createFromDate($this->year, 1, 1);
                }else{
                    $startDate = Carbon::now();
                }
                $startDate->month($numericMonth);

                if (isset($this->year)) {
                    $endDate = Carbon::createFromDate($this->year, 1, 1);
                }else{
                    $endDate = Carbon::now();
                }

                $endDate->month($numericMonth);

                $startDateFinal = $startDate->startOfMonth()->format("Y-m-d");
                $endDateFinal = $endDate->endOfMonth()->format("Y-m-d");

                $workdays = $this->getWorkDays($startDateFinal,$endDateFinal,"workdays");
                $arrMonthMandays[$numericMonth-1] = $arrMonthMandays[$numericMonth-1]+count($workdays);
            }

            $sumMandays = array_sum($arrMonthMandays);

            return $threshold = (float)$sumMandays*80/100;
        }else{
            $startDate = Carbon::now()->startOfYear()->format("Y-m-d");
            $endDate = Carbon::now()->endOfYear()->format("Y-m-d");
            $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
            $workdays = count($workdays);
            return $threshold = (float)$workdays*80/100;
        }
        // return collect(["planned"=>$workdays,"threshold"=>$threshold]);
        // return [$workdays,$threshold];
    }

    public function getWorkdays($startDate,$endDate,$remarks)
    {
        $formattedStartDate = Carbon::parse($startDate)->toISOString();
        $formattedEndDate   = Carbon::parse($endDate)->toISOString();
        
        $client = new Client();
        $api_response = $client->get('https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?timeMin='. $formattedStartDate .'&timeMax='. $formattedEndDate .'&key='.env('GCALENDAR_API_KEY'));
        $json = (string)$api_response->getBody();
        $holiday_indonesia = json_decode($json, true);

        $holiday_indonesia_final_detail = collect();
        $holiday_indonesia_final_details = collect();
        $holiday_indonesia_final_date = collect();
        $holiday_indonesia_final_dates = collect();
        // return $holiday_indonesia;
        
        foreach ($holiday_indonesia["items"] as $value) {
            if(( (( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate )) && (($value["description"] == 'Public holiday')) && (!strstr($value['summary'], "Joint")  && ($value["summary"] != 'Boxing Day')) )){
                $holiday_indonesia_final_detail->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
                $holiday_indonesia_final_date->push($value["start"]["date"]);
            }
            if(( (( $value["start"]["date"] >= $startDate ) && ( $value["start"]["date"] <= $endDate )) && (($value['summary'] == 'Idul Fitri Joint Holiday') || ($value['summary'] == 'Boxing Day')) )){
                $holiday_indonesia_final_details->push(["start_date" => $value["start"]["date"],"activity" => $value["summary"],"remarks" => "Cuti Bersama"]);
                $holiday_indonesia_final_dates->push($value["start"]["date"]);
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

        if ($remarks == 'holiday') {
            return $holiday_indonesia_final_detail;
        } else {
            return $workDaysMinHolidayKeyed;
        }
    }
}
