<?php

namespace App;
use DB;
use App\PMO;

use Illuminate\Database\Eloquent\Model;

class PMOProjectCharter extends Model
{
    protected $table = 'tb_pmo_project_charter';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','project_description','project_objectives', 'estimated_start_date', 'estimated_end_date', 'flexibility', 'scope_of_work', 'out_of_scope', 'customer_requirement', 'terms_of_payment', 'date_time', 'status', 'customer_name', 'customer_address', 'customer_phone', 'customer_cp', 'customer_email', 'customer_cp_phone', 'customer_cp_title', 'logo_company', 'market_segment'];
    public $timestamps = false;

    protected $appends = ['risk', 'internal_stakeholder', 'owner', 'no_po_customer', 'project_id', 'project_pm', 'project_pc', 'technology_used', 'dokumen', 'get_sign', 'note_reject', 'milestone', 'document_distribution', 'get_all_sign','type_project_array'];

    public function getTypeProjectArrayAttribute()
    {

        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.project_id')->where('tb_pmo.id', $this->id_project)->first();

        $data = DB::table('tb_pmo')->select('project_type')->where('project_id',$get_id_pmo->project_id)->get();
        // if ($data == '["implementation","maintenance"]') {
        //     return 'Implementation + Maintenance & Managed Service';
        // } elseif($data == '["implementation"]') {
        //     return 'Implementation';
        // } elseif($data == '["maintenance"]') {
        //     return 'Maintenance';
        // } elseif($data == '["supply_only"]') {
        //     return 'Supply Only';
        // }

        return $data;
        
        // return empty($data->project_type)?'-':$data->project_type;
    }

    public function getRiskAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo_identified_risk')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_identified_risk.id_project')->select('risk_description', 'risk_owner', 'impact', 'risk_response', 'likelihood', 'impact_rank', 'due_date', 'review_date', 'tb_pmo_identified_risk.status','impact_description')->where('tb_pmo_identified_risk.id_project', $get_id_pmo->id)->get();

    	return $data;
    }

    public function getInternalStakeholderAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo_internal_stakeholder')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_internal_stakeholder.id_project')->join('users', 'users.nik', 'tb_pmo_internal_stakeholder.nik')->select('users.name', 'tb_pmo_internal_stakeholder.role', 'users.email', 'users.phone', 'tb_pmo_internal_stakeholder.nik')->where('tb_pmo_internal_stakeholder.id_project', $get_id_pmo->id)->get();

        $data_eksternal = DB::table('tb_pmo_eksternal_stakeholder')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_eksternal_stakeholder.id_project')->select('name', 'tb_pmo_eksternal_stakeholder.role', 'email', 'phone',DB::raw('CONCAT("-") AS `nik`'),)->where('tb_pmo_eksternal_stakeholder.id_project', $get_id_pmo->id)->get();

        return array("data"=>$data->merge($data_eksternal));

    	// return $data;
    }

    public function getOwnerAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = PMO::join('tb_id_project', 'tb_pmo.project_id', 'tb_id_project.id_project')->select('sales_name')->where('tb_pmo.id', $get_id_pmo->id)->first();

    	return $data->sales_name;
    }

    public function getNoPoCustomerAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo')->join('tb_id_project', 'tb_pmo.project_id', 'tb_id_project.id_project')->select('no_po_customer')->where('tb_pmo.id', $this->id_project)->first();

    	return $data->no_po_customer;
    }

    public function getProjectIdAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo')->join('tb_id_project', 'tb_pmo.project_id', 'tb_id_project.id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik', 'sales_lead_register.nik')->select('name_project','amount_idr as amount','tb_id_project.id_project as project_id', 'no_po_customer','users.name as owner')->where('tb_pmo.id', $get_id_pmo->id)->first();

    	return $data;
    }

    public function getProjectPmAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id', 'project_id')->where('tb_pmo.id', $this->id_project)->first();

    	// $data = PMO::join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm')->where('role', 'Project Manager')->where('tb_pmo.id', $get_id_pmo->id)->first();

        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm')->where('role', 'Project Manager')->where('tb_pmo.project_id', $get_id_pmo->project_id)->first();

    	return empty($data->project_pm)?'-':$data->project_pm;
    }

    public function getProjectPcAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id','project_id')->where('tb_pmo.id', $this->id_project)->first();

    	// $data = PMO::join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc')->where('role', 'Delivery Project Coordinator')->where('tb_pmo.id', $get_id_pmo->id)->first();

        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc')->where('role', 'Delivery Project Coordinator')->where('tb_pmo.project_id', $get_id_pmo->project_id)->first();

    	// return $data->project_pc;
    	return empty($data->project_pc)?'-':$data->project_pc;
    }

    public function getTechnologyUsedAttribute()
    {
    	$get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo_technology_project_charter')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id', 'tb_pmo_technology_project_charter.id_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('technology_used')->where('tb_pmo.id', $get_id_pmo->id)->get();

    	return $data;
    }

    public function getDokumenAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

    	$data = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->join('tb_pmo_doc_project_charter', 'tb_pmo_doc_project_charter.id_project_charter', 'tb_pmo_project_charter.id')->join('tb_pmo_document', 'tb_pmo_document.id', 'tb_pmo_doc_project_charter.id_document')->select('document_name', 'document_location', 'link_drive')->where('tb_pmo.id', $get_id_pmo->id)->get();

    	// $data = DB::table('tb_pmo_technology_project_charter')->join('tb_pmo_project_charter', 'tb_pmo_project_charter.id', 'tb_pmo_technology_project_charter.id_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('technology_used')->where('tb_pmo.id', $get_id_pmo->id)->get();

    	return $data;
    }

    public function getGetSignAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

        $data = PMO::where('id',$get_id_pmo->id)->first();

        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $get_id_pmo->id)->first();

        if(PMOActivity::where('phase',"Update Project Charter")->where('id_project', $get_id_pmo->id)->exists()){
            $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('id')
                ->where('id_project',$get_id_pmo->id)
                ->where('tb_pmo_activity.phase', 'Update Project Charter')
                ->orderBy('date_time', 'desc')->take(1)->first();

            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$get_id_pmo->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->where('tb_pmo_activity.id','!=',$get_last_activity->id)
                ->orderBy('id', 'desc')->get();
        } else {
            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$get_id_pmo->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->orderBy('id', 'desc')->get();
        }
        
        $activity = DB::table('tb_pmo_activity')->where('id_project', $get_id_pmo->id);

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
                    'users.ttd as ttd_digital',
                    'users.email',
                    'users.avatar',
                    DB::raw("IFNULL(SUBSTR(`temp_tb_pmo_activity`.`date_time`,1,10),'-') AS `date_sign`"),
                    DB::raw('IF(ISNULL(`temp_tb_pmo_activity`.`date_time`),"false","true") AS `signed`'),
                )
            ->leftJoinSub($activity,'temp_tb_pmo_activity',function($join){
                // $join->on("temp_tb_pmo_activity.operator","=","users.name");
                $join->on("users.name","LIKE",DB::raw("CONCAT('%', temp_tb_pmo_activity.operator, '%')"));
            })
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy');

            if ($data->project_type == 'maintenance') {
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Project Coordinator","VP Project Management","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                    } else{
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Project Coordinator","Project Management Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                    }
                }
    
            } else if ($data->project_type == 'implementation'){
                foreach ($sign->get() as $key => $value) {
                    if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Project Manager","VP Project Management","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                    } else {
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Project Manager","Project Management Office Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                    }
                }
                
            }

        return empty($sign->get()->where('signed','false')->first()->name)?'-':$sign->get()->where('signed','false')->first()->name;
    }

    public function getGetAllSignAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();
        $data = PMO::where('id', $get_id_pmo->id)->first();

        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $get_id_pmo->id)->first();

        if(PMOActivity::where('phase',"Update Project Charter")->where('id_project', $get_id_pmo->id)->exists()){
            $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('id')
                ->where('id_project',$get_id_pmo->id)
                ->where('tb_pmo_activity.phase', 'Update Project Charter')
                ->orderBy('date_time', 'desc')->take(1)->first();

            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$get_id_pmo->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->where('tb_pmo_activity.id','!=',$get_last_activity->id)
                ->orderBy('id', 'desc')->get();
        } else {
            $unapproved = DB::table('tb_pmo_activity')
                ->where('id_project',$get_id_pmo->id)
                ->where(function($query){
                    $query->where('tb_pmo_activity.phase', 'Reject Project Charter')->orWhere('phase', 'Update Project Charter');
                })
                ->orderBy('id', 'desc')->get();
        }
        
        $activity = DB::table('tb_pmo_activity')->where('id_project', $get_id_pmo->id);

        if(count($unapproved) != 0){
            $activity->where('tb_pmo_activity.id','>',$unapproved->first()->id);
        }
            
        $activity->where(function($query){
            $query->where('tb_pmo_activity.phase', 'Approve Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'Update Project Charter')
            ->orWhere('tb_pmo_activity.phase', 'New Project Charter')
            ->orWhere('tb_pmo_activity.activity', 'Create New Project Charter');
        });

        $sign = User::join('role_user', 'role_user.user_id', '=', 'users.nik')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select(
                    'users.name', 
                    DB::raw("(CASE WHEN (`roles`.`name` = 'PMO Delivery Project Coordinator') THEN 'Delivery Project Coordinator' WHEN (`roles`.`name` = 'PMO Staff') THEN 'Delivery Project Manager' WHEN (`roles`.`name` = 'Account Executive') THEN 'Account Manager'  WHEN (`roles`.`name` = 'VP Sales') THEN 'Account Manager' ELSE `roles`.`name` END) as position"),
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
                foreach ($sign->get() as $key => $value) {
                    // if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    //     $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`name` = 'Agustinus Angger Muryanto' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    //     ->orderByRaw('FIELD(position, "Delivery Project Coordinator","VP Project Management","Account Manager","VP Sales")');
                    // } else{
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`name` = 'Agustinus Angger Muryanto' OR `roles`.`name` = 'Project Management Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Delivery Project Coordinator","VP Project Management", "Project Management Manager","Account Manager","Chief Operating Officer")')->havingRaw('signed = "true"');
                    // }
                }
    
            } else if ($data->project_type == 'implementation'){
                foreach ($sign->get() as $key => $value) {
                    // if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    //     $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`name` = 'Agustinus Angger Muryanto' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    //     ->orderByRaw('FIELD(position, "Delivery Project Manager","VP Project Management","Account Manager","Chief Operating Officer")');
                    // } else {
                        $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`name` = 'Agustinus Angger Muryanto' OR `roles`.`name` = 'Project Management Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                        ->orderByRaw('FIELD(position, "Project Manager","VP Project Management","Project Management Manager","Account Manager","Chief Operating Officer")')->havingRaw('signed = "true"');;
                    // }
                }
                
            }

        return empty($sign->get())?'-':$sign->get();
    }

    public function getNoteRejectAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();
        $get_last_activity = DB::table('tb_pmo_activity')
                ->join('users', 'users.name', 'tb_pmo_activity.operator')
                ->select('activity')
                ->where('id_project',$get_id_pmo->id)
                ->where('tb_pmo_activity.phase', 'Reject Project Charter')
                ->orderBy('date_time', 'desc')->take(1)->first();

        return empty($get_last_activity->activity)?'-':$get_last_activity->activity;
    }

    public function getMilestoneAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();
        $data = 'false';
        if(DB::table('tb_pmo_activity')->where('id_project',$get_id_pmo->id)->where('activity', 'Add Milestone')->exists()){
            $data = 'true';
        }

        return $data;
    }

    public function getDocumentDistributionAttribute()
    {
        $get_id_pmo = DB::table('tb_pmo_project_charter')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_project_charter.id_project')->select('tb_pmo.id')->where('tb_pmo.id', $this->id_project)->first();

        $data = PMO::where('id', $get_id_pmo->id)->first();

        $get_name_sales = DB::table('tb_id_project')->join('sales_lead_register', 'sales_lead_register.lead_id', 'tb_id_project.lead_id')->join('users', 'users.nik','sales_lead_register.nik')->select('users.name')->where('tb_id_project.id_project', $data->project_id)->first();

        $get_name_pm = PMO_assign::join('users', 'users.nik', 'tb_pmo_assign.nik')->join('tb_pmo', 'tb_pmo.id', 'tb_pmo_assign.id_project')->select('users.name')->where('id_project', $get_id_pmo->id)->first();

        $get_user = User::join('role_user','role_user.user_id', 'users.nik')->join('roles', 'roles.id', 'role_user.role_id')
            ->select('users.name', 'roles.name as position', 'email', DB::raw("(CASE WHEN (`roles`.`name` = 'PMO Staff') THEN 'Delivery Project Manager' WHEN (`roles`.`name` = 'Account Executive') THEN 'Account Manager' ELSE `roles`.`name` END) as position"))
            ->whereRaw("(`users`.`id_position` = 'MANAGER' AND `users`.`id_division` = 'TECHNICAL' AND `id_territory` is null OR `users`.`name` = '" . $get_name_sales->name . "' OR `users`.`name` = '" . $get_name_pm->name . "')")
            ->where('users.id_company', '1')
            ->where('users.status_karyawan', '!=', 'dummy')
            ->get();

        return $get_user;
    }
}
