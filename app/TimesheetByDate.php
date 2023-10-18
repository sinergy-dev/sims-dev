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

class TimesheetByDate extends Model
{
    protected $table = 'tb_timesheet';
    protected $primaryKey = 'id';
    protected $fillable = ['nik','schedule', 'date','type','pid','task','phase','level','activity','duration','status','date_add','point_mandays'];

    protected $appends = ['activity'];

    public function getActivityAttribute()
    {
        $data = DB::table('tb_timesheet')
            ->leftJoin('tb_timesheet_phase','tb_timesheet_phase.id','tb_timesheet.phase')
            ->leftJoin('tb_timesheet_task','tb_timesheet_task.id','tb_timesheet.task')
            ->select('nik','tb_timesheet.id','pid','start_date','end_date','activity','duration','status','point_mandays',DB::raw('YEAR(start_date) year, MONTH(start_date) month'),'type','schedule',
                DB::raw("(CASE WHEN (`tb_timesheet`.`task` is null) THEN '-' WHEN (`tb_timesheet`.`task` = 'null') THEN '-' WHEN (`tb_timesheet`.`task` = '') THEN '-' ELSE `tb_timesheet_task`.`task` END) as task"),
                DB::raw("(CASE WHEN (`tb_timesheet`.`phase` is null) THEN '-' WHEN (`tb_timesheet`.`task` = 'null') THEN '-'  WHEN (`tb_timesheet`.`task` = '') THEN '-' ELSE `tb_timesheet_phase`.`phase` END) as phase"),
                DB::raw("(CASE WHEN (`tb_timesheet`.`level` is null) THEN '-' WHEN (`tb_timesheet`.`level` = 'null') THEN '-' WHEN (`tb_timesheet`.`level` = '') THEN '-' ELSE `tb_timesheet`.`level` END) as level"),
            )
            ->where('nik',$this->nik)
            ->where('pid',$this->pid)
            ->where('start_date',$this->start_date)
            ->orderby('start_date','asc')->distinct()
            ->get();

        return $data;
    }
}
