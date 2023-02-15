<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class PMO extends Model
{
	protected $table = 'tb_pmo';
    protected $primaryKey = 'id';
    protected $fillable = ['current_phase','project_type', 'implementation_type','project_id'];
    public $timestamps = false;

    protected $appends = ['indicator_project','sign','type_project','name_project','owner','no_po_customer','project_pm','project_pc','type_project_array'];

    public function getPhaseAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_activity','tb_pmo_activity.id_project','tb_pmo.id')->select('phase')->where('project_id',$this->project_id)->first();

        $activity = DB::table('tb_pmo')->join('tb_pmo_activity','tb_pmo_activity.id_project','tb_pmo.id')->where('project_id',$this->project_id);
        $activity->where(function($query){
            $query->where('tb_pmo_activity.phase', 'Submit Final Project Closing Report');
        });

        return $activity->get()->pluck('phase');

        return empty($data->phase)?'-':$data->phase;
    }

    public function getProjectPmAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm')->where('role', 'Project Manager')->where('tb_pmo.project_id', $this->project_id)->first();

        // return $data;

        return empty($data->project_pm)?'-':$data->project_pm;
    }

    public function getProjectPcAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc')->where('role', 'Project Coordinator')->where('tb_pmo.project_id', $this->project_id)->first();

        // return $data->project_pc;
        return empty($data->project_pc)?'-':$data->project_pc;
    }

    public function getTypeProjectAttribute()
    {
        $data = DB::table('tb_pmo')->select('project_type')->where('project_id',$this->project_id)->get()->pluck('project_type');
        if ($data == '["implementation","maintenance"]') {
            return 'Implementation + Maintenance & Managed Service';
        } elseif($data == '["implementation"]') {
            return 'Implementation';
        } elseif($data == '["maintenance"]') {
            return 'Maintenance & Managed Service';
        } elseif($data == '["supply_only"]') {
            return 'Supply Only';
        }
    }

    public function getTypeProjectArrayAttribute()
    {
        $data = DB::table('tb_pmo')->select('project_type')->where('project_id',$this->project_id)->get();

        return $data;
    }

    public function getNameProjectAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_id_project','tb_id_project.id_project','tb_pmo.project_id')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->select('opp_name as name_project')->where('project_id',$this->project_id)->first();

        // return $data;
        return empty($data->name_project)?'-':$data->name_project;
    }

    public function getOwnerAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_id_project','tb_id_project.id_project','tb_pmo.project_id')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->join('users','users.nik','sales_lead_register.nik')->select('users.name as owner')->where('id',$this->id)->first();

        // return $data;
        return empty($data->owner)?'-':$data->owner;
    }


    public function getNoPoCustomerAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_id_project','tb_id_project.id_project','tb_pmo.project_id')->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')->select('no_po_customer')->where('project_id',$this->project_id)->first();

        // return $data;
        return empty($data->no_po_customer)?'-':$data->no_po_customer;
    }

    public function getIndicatorProjectAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_progress_report','tb_pmo.id','tb_pmo_progress_report.id_project')->select('project_indicator')->where('tb_pmo_progress_report.id_project',$this->id)->orderBy('tb_pmo_progress_report.reporting_date','desc')->first();

        return empty($data->project_indicator)?'-':$data->project_indicator;
    }

    public function getSignAttribute()
    {
        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $this->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $this->id)->first();
        // return $get_name_pm;

        if(PMOActivity::where('phase',"Update Project Charter")->where('id_project', $this->id)->exists()){
            $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('id')
                ->where('id_project',$this->id)
                ->where('tb_pmo_activity.phase', 'Update Project Charter')
                ->orderBy('date_time', 'desc')->take(1)->first();

            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$this->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->where('tb_pmo_activity.id','!=',$get_last_activity->id)
                ->orderBy('id', 'desc')->get();
        } else {
            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$this->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->orderBy('id', 'desc')->get();
        }
        
        $activity = DB::table('tb_pmo_activity')->where('id_project',$this->id);

        if(count($unapproved) != 0){
            $activity->where('tb_pmo_activity.id','>',$unapproved->first()->id);
        }
            
        $activity->where(function($query){
            $query->where('tb_pmo_activity.phase', 'Approve Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'Update Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'New Project Charter');
        });


        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    'roles.name as position', 
                    'roles.group as group',
                    'users.ttd_digital',
                    'users.email',
                    'users.avatar',
                    DB::raw("IFNULL(SUBSTR(`temp_tb_pmo_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                    DB::raw('IF(ISNULL(`temp_tb_pmo_activity`.`date_time`),"false","true") AS `signed`')
                )
            ->leftJoinSub($activity,'temp_tb_pmo_activity',function($join){
                $join->on("temp_tb_pmo_activity.operator","=","users.name");
            })
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy');

        if ($this->project_type == 'maintenance') {
           $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
            ->orderByRaw('FIELD(position, "PMO Project Coordinator","PMO Manager","Sales Staff","Sales Manager","BCD Manager","Operations Director")');
        } else if ($this->project_type == 'implementation'){
            $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
            ->orderByRaw('FIELD(position, "PMO Staff","PMO Manager","Sales Staff","Sales Manager","BCD Manager","Operations Director")');
        }

        if ($this->project_type != 'supply_only') {
            if (empty($sign->get()->where('signed','false')->first()->name)?'-':$sign->get()->where('signed','false')->first()->name == $get_name_pm->name) {
                return '-';
            } else {
                return empty($sign->get()->where('signed','false')->first()->name)?'-':$sign->get()->where('signed','false')->first()->name;    
            }
            
        } else {
            return '-';
        }
        
    }
}
