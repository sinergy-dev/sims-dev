<?php

namespace App;
use DB;
use App\PMO;

use Illuminate\Database\Eloquent\Model;

class PMOFinalReport extends Model
{
	protected $table = 'tb_pmo_final_report';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project'];
    public $timestamps = false;

    // protected $appends = ['note_reject', 'project_pm','project_pc','owner','customer_info','get_sign','project_id','get_milestone','project_document', 'document_distribution','internal_stakeholder'];
    protected $appends = ['note_reject', 'project_pm','project_pc','owner','customer_info','get_sign','project_id','project_document', 'document_distribution','internal_stakeholder','term_payment','payment_date','get_milestone','project_description'];

    public function getTermPaymentAttribute()
    {
        $data = DB::table('tb_pmo_final_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_final_report.id_project')->select('term_payment')->where('tb_pmo.id', $this->id_project)->first();

        $data = json_decode($data->term_payment);

        return $data;
    }

    public function getProjectDescriptionAttribute()
    {
        $getPid = DB::table('tb_pmo')->select('project_id')->where('id',$this->id_project)->first()->project_id;
        $countPid = DB::table('tb_pmo')->where('project_id',$getPid)->count();
        if ($countPid == 2 && DB::table('tb_pmo')->select('project_type')->where('id',$this->id_project)->first()->project_type == 'maintenance') {
            $id_pmo = $this->id_project-1;
        } else {
            $id_pmo = $this->id_project;
        }

        $data = DB::table('tb_pmo_final_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_final_report.id_project')->join('tb_pmo_project_charter','tb_pmo_project_charter.id_project','tb_pmo.id')->select('project_description')->where('tb_pmo.id', $id_pmo)->first();
    }

    public function getPaymentDateAttribute()
    {
        $data = DB::table('tb_pmo_final_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_final_report.id_project')->select('payment_date')->where('tb_pmo.id', $this->id_project)->first();

        $data = json_decode($data->payment_date);

        return $data;
    }

    public function getInternalStakeholderAttribute()
    {
        
        $data = DB::table('tb_pmo_internal_stakeholder')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_internal_stakeholder.id_project')->join('users', 'users.nik', 'tb_pmo_internal_stakeholder.nik')->select('users.name', 'tb_pmo_internal_stakeholder.role', 'users.email', 'users.phone', 'tb_pmo_internal_stakeholder.nik')->where('tb_pmo_internal_stakeholder.id_project', $this->id_project)->whereRaw("(`role` = 'Technical Lead Engineer' OR `role` = 'IT Network Engineer' OR `role` = 'IT Network Security Engineer' OR `role` = 'IT Network Engineer' OR `role` = 'IT System Engineer' OR `role` = 'Cabling Engineer' OR `role` = 'MSM Technical Lead Engineer' OR `role` = 'MSM Engineer')")->get();

        return $data;
    }

    public function getProjectIdAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_id_project', 'tb_pmo.project_id', 'tb_id_project.id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik', 'sales_lead_register.nik')->select('name_project','amount_idr as amount','tb_id_project.id_project as project_id', 'no_po_customer','users.name as owner')->where('tb_pmo.id', $this->id_project)->first();

        return $data;
    }

    public function getNoteRejectAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_final_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_final_report.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();
        $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('activity')
                ->where('id_project',$get_id_pmo->id)
                ->where('tb_pmo_activity.phase', 'Reject Final Report')
                ->orderBy('date_time', 'desc')->take(1)->first();

        return empty($get_last_activity->activity)?'-':$get_last_activity->activity;
    }

    public function getCustomerInfoAttribute()
    {
        $data = DB::table('tb_pmo_final_report')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_final_report.id_project')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id_project', 'tb_pmo.id')->join('tb_id_project', 'tb_id_project.id_project', 'tb_pmo.project_id')->select('logo_company', 'tb_id_project.customer_name', 'name_project')->where('tb_pmo_final_report.id_project', $this->id_project)->first();

        return $data;
    }

    public function getProjectPmAttribute()
    {
        // $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm')->where('role', 'Project Manager')->where('tb_pmo.id', $this->id_project)->first();

        return empty($data->project_pm)?'-':$data->project_pm;
    }

    public function getProjectPcAttribute()
    {
        // $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc')->where('role', 'Project Coordinator')->where('tb_pmo.id', $this->id_project)->first();

        return empty($data->project_pc)?'-':$data->project_pc;
    }

    // public function getProjectIdAttribute()
    // {
    //     $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    //     $data = PMO::where('tb_pmo.id', $get_id_pmo->id)->first();

    //     return $data->project_id;
    // }

    public function getOwnerAttribute()
    {
        $getPid = DB::table('tb_pmo')->select('project_id')->where('id',$this->id_project)->first()->project_id;
        $countPid = DB::table('tb_pmo')->where('project_id',$getPid)->count();
        if ($countPid == 2 && DB::table('tb_pmo')->select('project_type')->where('id',$this->id_project)->first()->project_type == 'maintenance') {
            $id_pmo = $this->id_project-1;
        } else {
            $id_pmo = $this->id_project;
        }
        $data = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->join('tb_id_project','tb_id_project.id_project','=','tb_pmo.project_id')->join('sales_lead_register','sales_lead_register.lead_id','=','tb_id_project.lead_id')->join('users','users.nik','=','sales_lead_register.nik')->select('users.name as owner')->where('tb_pmo.id', $id_pmo)->first();

        return $data->owner;
    }

    public function getGetSignAttribute()
    {
        $data = PMO::where('id', $this->id_project)->first();
        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $this->id_project)->first();

        $activity = DB::table('tb_pmo_activity')->where('id_project', $this->id_project);

        $activity->where(function($query){
            $query->where('activity','Create Final Report')
            ->orWhere('tb_pmo_activity.activity', 'Approve Final Report');
        });

        

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
               $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` =  'Project Management Office Manager')")
                ->orderByRaw('FIELD(position, "Project Coordinator","Project Management Manager")');
            } else {
                $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager')")
                ->orderByRaw('FIELD(position, "Project Manager","Project Management Manager")');
            }

        return $sign->get();
    }

    public function getGetMilestoneAttribute()
    {
        $getParent = GanttTaskPmo::where('id_pmo',$this->id_project)->where('parent',0)->orderByRaw('FIELD(text, "Initiating", "Planning", "Executing", "Closing")')->get();
        $getType = DB::table('tb_pmo')->select('project_type')->where('id',$this->id_project)->first();
        foreach($getParent as $dataParent){
                $modifiedData = $dataParent->text;
                if ($dataParent->text == 'Executing') {
                    // return $dataParent->id;
                    if ($getType->project_type == 'implementation') {
                        $getParentKey = GanttTaskPmo::where('id_pmo',$this->id_project)->where('parent',$dataParent->id)->get();

                        foreach($getParentKey as $dataParentkey){
                            if ($dataParentkey->duration == 0) {
                                $milestoneArray[$modifiedData][$dataParentkey->text] = GanttTaskPmo::where('id_pmo', $this->id_project)->where('parent', $dataParentkey->id)->get(); 
                            }

                            if ($dataParentkey->duration != 0) {
                                $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $this->id_project)->where('parent', $dataParent->id)->get();

                            }
                                                  
                        }
                    }else{
                        $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $this->id_project)->where('parent', $dataParent->id)->get();
                    }                
                }else{
                    $milestoneArray[$modifiedData] = GanttTaskPmo::where('id_pmo', $this->id_project)->where('parent', $dataParent->id)->get();
                }
        }

        // return $dataParent = DB::table('gantt_tasks_pmo')->select('id as id_parent','text as text_parent')->where('parent','==',0)->get(); 

        $dataMilestone = GanttTaskPmo::select('text', 'baseline_end', 'end_date','start_date')
                    ->where('id_pmo',$this->id_project)
                    ->where('parent','!=','0')
                    // ->whereBetween('baseline_start', [$startOfWeek,$endOfWeek])
                    ->get();

        return $milestoneArray;
    }

    public function getProjectDocumentAttribute()
    {
        $doc = DB::table('gantt_tasks_pmo')->where('id_pmo', $this->id_project)->select('text', 'deliverable_document')->where('deliverable_document', '!=', 'false')->get();
        return $doc;
    }

    public function getDocumentDistributionAttribute()
    {
        // $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();
        $getPid = DB::table('tb_pmo')->select('project_id')->where('id',$this->id_project)->first()->project_id;
        $countPid = DB::table('tb_pmo')->where('project_id',$getPid)->count();
        if ($countPid == 2 && DB::table('tb_pmo')->select('project_type')->where('id',$this->id_project)->first()->project_type == 'maintenance') {
            $get_id_pmo = $this->id_project-1;
        } else {
            $get_id_pmo = $this->id_project;
        }

        $data = PMO::where('id', $get_id_pmo)->first();

        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $get_id_pmo)->first();

        // $data = PMO::where('id', $get_id_pmo->id)->first();

        // $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        // $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $get_id_pmo->id)->first();

        $get_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')
            ->select('users.name', 'roles.name as position', 'email', DB::raw("(CASE WHEN (`roles`.`name` = 'PMO Staff') THEN 'Delivery Project Manager' WHEN (`roles`.`name` = 'Account Executive') THEN 'Account Manager' ELSE `roles`.`name` END) as position"))
            ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `id_territory` is null OR `users`.`name` = '" . $get_name_sales->name . "' OR `users`.`name` = '" . $get_name_pm->name . "')")
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy')
            ->get();

        return $get_user;
    }
}
