<?php

namespace App;
use DB;
use Carbon\Carbon;
use App\GanttTaskPmo;
use App\PMOIssue;
use App\PMORisk;

use Illuminate\Database\Eloquent\Model;

class PMOProgressReport extends Model
{
    protected $table = 'tb_pmo_progress_report';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project'];
    public $timestamps = false;

    protected $appends = ['doc_distribution', 'customer_info', 'get_milestone_reached', 'get_milestone_to_be_reached', 'get_issue', 'get_risk', 'get_sign','cp_name','project_pm','project_pc', 'periode'];

    public function getPeriodeAttribute()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->format('d M Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('d M Y');

        return $startOfWeek . ' - ' . $endOfWeek;
    }

    public function getProjectPmAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm')->where('role', 'Project Manager')->where('tb_pmo.id', $this->id)->first();

        return empty($data->project_pm)?'-':$data->project_pm;
    }

    public function getProjectPcAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc')->where('role', 'Project Coordinator')->where('tb_pmo.id', $this->id)->first();

        return empty($data->project_pc)?'-':$data->project_pc;
    }

    public function getCpNameAttribute()
    {
        $data = DB::table('tb_pmo_progress_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_progress_report.id_project')->join('tb_pmo_project_charter', 'tb_pmo.id','tb_pmo_project_charter.id_project')->select('customer_cp')->where('tb_pmo_progress_report.id_project', $this->id_project)->first();
        return $data->customer_cp;
    }

    public function getDocDistributionAttribute()
    {
    	$data = DB::table('tb_pmo_progress_report')->join('tb_pmo_progress_report_distribution', 'tb_pmo_progress_report_distribution.id_report', 'tb_pmo_progress_report.id')->where('tb_pmo_progress_report.id_project', $this->id_project)->select('recipient_name','company_name','title','email')->get();

    	return $data;
    }

    public function getCustomerInfoAttribute()
    {
    	$data = DB::table('tb_pmo_progress_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_progress_report.id_project')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id_project', 'tb_pmo.id')->join('tb_id_project', 'tb_id_project.id_project', 'tb_pmo.project_id')->select('logo_company', 'tb_id_project.customer_name', 'name_project')->where('tb_pmo_progress_report.id_project', $this->id_project)->first();

    	return $data;
    }

    public function getGetMilestoneReachedAttribute()
    {

    	$dateFormat = DB::table('gantt_tasks_pmo')->select(DB::raw("DATE_FORMAT(baseline_start, '%d %M %Y') as start_date_format"),DB::raw("DATE_FORMAT(baseline_end, '%d %M %Y') as end_date_format"),'gantt_tasks_pmo.id as id_gantt')->where('parent','!=',0); 

        $dataParent = DB::table('gantt_tasks_pmo')->select('id as id_parent','text as text_parent')->where('parent','==',0); 

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $dataMilestone = GanttTaskPmo::leftJoinSub($dateFormat, 'format_date_task',function($join){
                        $join->on("gantt_tasks_pmo.id", '=', 'format_date_task.id_gantt');
                    })->leftJoinSub($dataParent, 'parent_text',function($join){
                        $join->on("gantt_tasks_pmo.parent", '=', 'parent_text.id_parent');
                    })
                    ->select(DB::raw("CONCAT(format_date_task.`start_date_format`,' - ',format_date_task.`end_date_format`) AS periode"),'text as milestone','format_date_task.id_gantt','parent_text.text_parent', 'deliverable_document', 'baseline_end', 'end_date')
                    ->where('id_pmo',$this->id_project)
                    ->where('status','Done')
                    // ->whereRaw("(`text` !=  'Initiating' OR `text` != 'Planning' OR `text` != 'Executing' OR `text` != 'Done')")
                    ->where('parent','!=','0')
                    ->whereBetween('baseline_start', [$startOfWeek,$endOfWeek])
                    ->get();

    	return $dataMilestone;
    }

    public function getGetMilestoneToBeReachedAttribute()
    {

    	$dateFormat = DB::table('gantt_tasks_pmo')->select(DB::raw("DATE_FORMAT(baseline_start, '%d %M %Y') as start_date_format"),DB::raw("DATE_FORMAT(baseline_end, '%d %M %Y') as end_date_format"),'gantt_tasks_pmo.id as id_gantt')->where('parent','!=',0); 

        $dataParent = DB::table('gantt_tasks_pmo')->select('id as id_parent','text as text_parent')->where('parent','==',0); 

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $nextWeek = Carbon::parse('next monday')->toDateString();
        $endNextWeek = Carbon::parse('next monday')->add(6,'day')->toDateString();

        $dataMilestone = GanttTaskPmo::leftJoinSub($dateFormat, 'format_date_task',function($join){
                        $join->on("gantt_tasks_pmo.id", '=', 'format_date_task.id_gantt');
                    })->leftJoinSub($dataParent, 'parent_text',function($join){
                        $join->on("gantt_tasks_pmo.parent", '=', 'parent_text.id_parent');
                    })
                    ->select(DB::raw("CONCAT(format_date_task.`start_date_format`,' - ',format_date_task.`end_date_format`) AS periode"),'text as milestone','format_date_task.id_gantt','parent_text.text_parent', 'deliverable_document', 'baseline_end', 'end_date','start_date')
                    ->where('id_pmo',$this->id_project)
                    ->where('status','!=','Done')
                    // ->whereBetween('baseline_start', [$nextWeek,$endNextWeek])
                    ->get();

    	return $dataMilestone;
    }

    public function getGetIssueAttribute()
    {
    	$startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $dateFormat = DB::table('tb_pmo_issue')->select(DB::raw("DATE_FORMAT(actual_date, '%d %M %Y') as actual_date"),DB::raw("DATE_FORMAT(expected_date, '%d %M %Y') as expected_date"), 'tb_pmo_issue.id as id_issue')->where('id_project',$this->id_project); 

        // return $dateFormat->get();

        $dataIssue = PMOIssue::join('tb_pmo', 'tb_pmo.id', 'tb_pmo_issue.id_project')
        		// ->leftJoinSub($dateFormat, 'format_date',function($join){
          //           $join->on("tb_pmo_issue.id", '=', 'format_date.id_issue');
          //       })
        		->select('issue_description','solution_plan','owner','rating_severity','status', DB::raw("(CASE WHEN (expected_date is null) THEN '-' ELSE expected_date END) as expected_date"), DB::raw("(CASE WHEN (actual_date is null) THEN '-' ELSE actual_date END) as actual_date")
        			// DB::raw("CONCAT(format_date.`actual_date`,' - ',format_date.`expected_date`) AS periode")
        			// DB::raw("CONCAT(`actual_date`,' - ',`expected_date`) AS periode")
        		)
        		->where('status', 'Open')->where('tb_pmo_issue.id_project', $this->id_project)->get();
        return $dataIssue;
    }

    public function getGetRiskAttribute()
    {
    	$startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $dataRisk = PMORisk::join('tb_pmo', 'tb_pmo.id', 'tb_pmo_identified_risk.id_project')->select('risk_description','risk_response','risk_owner','due_date','status','impact','likelihood','response_plan')->where('tb_pmo_identified_risk.id_project', $this->id_project)->get();
        return $dataRisk;
    }

    public function getGetSignAttribute()
    {
    	$data = PMO::where('id', $this->id_project)->first();
    	$get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $this->id_project)->first();

    	$activity = DB::table('tb_pmo_activity')->where('id_project', $this->id_project)->where('activity','Create Weekly Report');

    	// return $activity->get();

    	$sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    DB::raw("(CASE WHEN (`roles`.`name` = 'PMO Project Coordinator') THEN 'Project Coordinator' WHEN (`roles`.`name` = 'PMO Staff') THEN 'Project Manager' ELSE `roles`.`name` END) as position"), 
                    'roles.group as group',
                    'users.ttd as ttd_digital',
                    'users.email',
                    'users.avatar',
                    DB::raw("IFNULL(SUBSTR(`temp_tb_pmo_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                    DB::raw('IF(ISNULL(`temp_tb_pmo_activity`.`date_time`),"false","true") AS `signed`')
                )
            ->leftJoinSub($activity,'temp_tb_pmo_activity',function($join){
                // $join->on("temp_tb_pmo_activity.operator","=","users.name");
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pmo_activity.operator, '%')"));
            })
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy');

        if ($data->project_type == 'maintenance') {
           $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "')")
            ->orderByRaw('FIELD(position, "Project Coordinator")');
        } else {
            $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "')")
            ->orderByRaw('FIELD(position, "Project Manager")');
        }

        return $sign->get();
    }
}