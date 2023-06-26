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
    protected $table = 'tb_timesheet';
    protected $primaryKey = 'id';
    protected $fillable = ['nik','schedule', 'date','type','pid','task','phase','level','activity','duration','status','date_add','point_mandays','month'];
    public $timestamps = false;

    protected $appends = ['planned', 'threshold'];

    // public function getLockDurationAttribute()
    // {
    // 	$getLock = TimesheetLockDuration::where('division',Auth::User()->id_division)->first();

    // 	return empty($getLock->lock_duration)?(empty(DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration) ? "1" : DB::table('tb_timesheet_lock_duration')->where('division',Auth::User()->id_division)->first()->lock_duration):$getLock->lock_duration;
    // }

    // public function getPlannedAttribute()
    // {
    //     // return 'abc';
    //     $nik = Auth::User()->nik;
    //     $cek_role = DB::table('role_user')->join('roles', 'roles.id', '=', 'role_user.role_id')->select('name', 'roles.group')->where('user_id', $nik)->first(); 

    //     $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
    //     $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
    //     $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
    //     $workdays = count($workdays);

    //     $getLeavingPermit = Cuti::join('tb_cuti_detail','tb_cuti_detail.id_cuti','tb_cuti.id_cuti')->select('date_off as date')->where('tb_cuti.status','v')->whereYear('date_off',date('Y'))->orderby('date','desc');

    //     $getPermit = TimesheetPermit::select('start_date');

    //     $threshold = $workdays*80/100;

    //     if ($cek_role->group == 'pmo') {
    //         if ($cek_role->name == 'PMO Manager') {
    //             $listGroup = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','pmo')->pluck('nik');
    //             $getData = DB::table('users')->select('nik','name')->whereIn('nik',$listGroup)->get();
    //             $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
    //             $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         } else {
    //             $getData = DB::table('users')->select('nik','name')->where('nik',$nik)->get();
    //             $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
    //             $getPermit = $getPermit->where('nik',$nik)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         }
    //     }elseif ($cek_role->group == 'DPG') {
    //         if ($cek_role->name == 'SID Manager') {
    //             $listGroup = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','DPG')->pluck('nik');
    //             $getData = DB::table('users')->select('nik','name')->whereIn('nik',$listGroup)->get();
    //             $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
    //             $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         } else {
    //             $getData = DB::table('users')->select('nik','name')->where('nik',$nik)->get();
    //             $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
    //             $getPermit = $getPermit->where('nik',$nik)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         }
    //     }elseif ($cek_role->group == 'presales') {
    //         if ($cek_role->name == 'SOL Manager') {
    //             $listGroup = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','presales')->pluck('nik');
    //             $getData = DB::table('users')->select('nik','name')->whereIn('nik',$listGroup)->get();
    //             $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
    //             $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         } else {
    //             $getData = DB::table('users')->select('nik','name')->where('nik',$nik)->get();
    //             $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
    //             $getPermit = $getPermit->where('nik',$nik)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         }
    //     }elseif ($cek_role->group == 'bcd') {
    //         if ($cek_role->name == 'BCD Manager') {
    //             $listGroup = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','bcd')->pluck('nik');
    //             $getData = DB::table('users')->select('nik','name')->whereIn('nik',$listGroup)->get();
    //             $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
    //             $getPermit = $getPermit->whereMonth('start_date',date('m'))->whereIn('nik',$listGroup)->get();
    //             $sumMandays = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('point_mandays','users.name','tb_timesheet.nik')->whereIn('tb_timesheet.nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik')->get();
    //         } else {
    //             $getData = DB::table('users')->select('nik','name')->where('nik',$nik)->get();
    //             $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
    //             $getPermit = $getPermit->whereMonth('start_date',date('m'))->where('nik',$nik)->get();
    //             $sumMandays = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->selectRaw('tb_timesheet.nik')->selectRaw('users.name')->selectRaw('SUM(point_mandays) AS `point_mandays`')->where('tb_timesheet.nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->groupby('tb_timesheet.nik')->get();
    //         }
    //     }elseif ($cek_role->group == 'hr') {
    //         if ($cek_role->name == 'HR Manager') {
    //             $listGroup = DB::table('users')->join('role_user', 'role_user.user_id', '=', 'users.nik')->join('roles', 'roles.id', '=', 'role_user.role_id')->where('roles.group','hr')->pluck('nik');
    //             $getData = DB::table('users')->select('nik','name')->whereIn('nik',$listGroup)->get();
    //             $getLeavingPermit = $getLeavingPermit->whereIn('nik',$listGroup)->get();
    //             $getPermit = $getPermit->whereIn('nik',$listGroup)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->whereIn('nik',$listGroup)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         } else {
    //             $getData = DB::table('users')->select('nik','name')->where('nik',$nik)->get();
    //             $getLeavingPermit = $getLeavingPermit->where('nik',$nik)->get();
    //             $getPermit = $getPermit->where('nik',$nik)->get();
    //             $sumMandays = DB::table('tb_timesheet')->select('point_mandays')->where('nik',$nik)->where('status','Done')->whereMonth('start_date',date('m'))->sum('point_mandays');
    //         }
    //     }

    //     // return $sumMandays;

    //     $allWorkdays = $this->getWorkDays($startDate,$endDate,"workdays");
    //     $allWorkdays = $allWorkdays->toArray();

    //     $getAllPermit = collect();
    //     $getPermit = json_decode($getPermit, true);

    //     $getAllLeavingPermit = collect();
    //     $getLeavingPermit = json_decode($getLeavingPermit, true);

    //     foreach ($getPermit as $value) {
    //         $getAllPermit->push($value['start_date']);
    //     }

    //     foreach ($getLeavingPermit as $value) {
    //        $getAllLeavingPermit->push($value['date']);
    //     }

    //     $getAllLeavingPermit = $getAllLeavingPermit->toArray();

    //     $all = array_merge($allWorkdays);

    //     $differenceArray = array_diff($all, $getAllPermit->toArray());
    //     $differenceArrayMerged = array_merge($differenceArray);
    //     $differenceArray2 = array_diff($differenceArrayMerged, $getAllLeavingPermit);

    //     $billable = count($differenceArray2);

    //     $getDataForDeviation = $sumMandays->map(function ($item, $key){
    //         // $item->planned = $item['planned'];
    //         // $item->actual = $item['point_mandays'];
    //         // $planned = $item->planned;
    //         // $actual = $item->point_mandays;
    //         return $item;
    //     });

    //     // $percentage = number_format($billable/$planned[0][0]*100,  2, '.', '');

    //     // return collect(["planned"=>$workdays,"threshold"=>$threshold,"billable"=>$billable]);
    //     // return [$workdays,$threshold];
    // }

    public function getPlannedAttribute()
    {
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        return $workdays = count($workdays);
    }

    public function getThresholdAttribute()
    {
        $startDate = Carbon::now()->startOfMonth()->format("Y-m-d");
        $endDate = Carbon::now()->endOfMonth()->format("Y-m-d");
        $workdays = $this->getWorkDays($startDate,$endDate,"workdays");
        $workdays = count($workdays);
        return $threshold = $workdays*80/100;

        // return collect(["planned"=>$workdays,"threshold"=>$threshold]);
        // return [$workdays,$threshold];
    }

    public function getWorkdays($startDate,$endDate,$remarks)
    {
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

        if ($remarks == 'holiday') {
            return $holiday_indonesia_final_detail;
        } else {
            return $workDaysMinHolidayKeyed;
        }
    }


}
