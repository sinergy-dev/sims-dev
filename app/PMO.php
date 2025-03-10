<?php

namespace App;
use DB;
use App\Sbe;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class PMO extends Model
{
	protected $table = 'tb_pmo';
    protected $primaryKey = 'id';
    protected $fillable = ['current_phase','project_type', 'implementation_type','project_id'];
    public $timestamps = false;

    // protected $appends = ['indicator_project','sign','type_project','name_project','owner','no_po_customer','project_pm','project_pc','type_project_array','status','plan_sbe','actual_sbe','health_status','sbe'];
    protected $appends = ['indicator_project','sign','type_project','name_project','owner','no_po_customer','project_pm','project_pc','type_project_array','status'];

    public function getIncrementingNumberAttribute()
    {
        // Replace this logic with your desired incrementing number generation logic.
        // For example, you can increment a counter each time a new instance is created.
        
        // Here, we'll use a simple static counter as an example:
        static $counter = 1;
        
        // Return the current value of the counter and increment it for the next call.
        return $counter++;
    }

    public function getStatusAttribute()
    {
        $data = DB::table('tb_pmo')->leftJoin('tb_pmo_project_charter','tb_pmo_project_charter.id_project','=','tb_pmo.id')->select('status')->where('tb_pmo.id',$this->id)->first();

        if ($this->type_project == 'Implementation + Maintenance & Managed Service' && $this->project_type == 'maintenance') {
            return 'Done';
        } else {
            return $data->status;
        }        
    }

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
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pm','tb_pmo.project_type')->where('role', 'Project Manager')->where('tb_pmo.project_id', $this->project_id)->first();

        // return $data;
        return empty($data->project_pm)?'-':$data->project_pm;
    }

    public function getProjectPcAttribute()
    {
        $data = DB::table('tb_pmo')->join('tb_pmo_assign', 'tb_pmo_assign.id_project', 'tb_pmo.id')->join('users', 'users.nik','tb_pmo_assign.nik')->select('users.name as project_pc','tb_pmo.project_type')->where('role', 'Project Coordinator')->where('tb_pmo.project_id', $this->project_id)->first();

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
        $data = DB::table('tb_pmo')->join('tb_pmo_progress_report','tb_pmo.id','tb_pmo_progress_report.id_project')->select('project_indicator')->where('tb_pmo_progress_report.id_project',$this->id)->orderBy('tb_pmo_progress_report.id','desc')->first();

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

        if ($this->project_type == 'maintenance') {
            foreach ($sign->get() as $key => $value) {
                if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Coordinator","VP Program & Project Management","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                } else{
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Coordinator","Project Management Office Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                }
            }
        } else if ($this->project_type == 'implementation'){
            foreach ($sign->get() as $key => $value) {
                if ($value->name == 'Agustinus Angger Muryanto' && $value->signed == 'true') {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `users`.`id_division` = 'PMO' AND `users`.`id_position` = 'MANAGER' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Manager","VP Program & Project Management","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                } else {
                    $sign->whereRaw("(`users`.`name` = '" . $get_name_pm->name . "' OR `roles`.`name` = 'Project Management Office Manager' OR `users`.`name` = '" . $get_name_sales->name . "')")
                    ->orderByRaw('FIELD(position, "Delivery Project Manager","Project Management Office Manager","Account Executive","VP Sales","BCD Manager","Chief Operating Officer")');
                }
            } 
        }

        // return $sign->get();

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

    public function getPlanSbeAttribute($value='')
    {
        // return $this->project_id;

        $data = PMO::join('tb_id_project','tb_pmo.project_id','=','tb_id_project.id_project')
                    ->join('sales_lead_register','tb_id_project.lead_id','=','sales_lead_register.lead_id')
                    ->join('tb_sbe','tb_id_project.lead_id','=','tb_sbe.lead_id')
                    ->where('tb_sbe.status','Fixed')
                    ->select('tb_sbe.nominal')
                    ->where('tb_pmo.project_id',$this->project_id)
                    ->first();

        if (empty($data)) {
            $data = 0;
        }else{
            $data = (int)$data->nominal;
        }

        return $data;
    }

    public function getActualSbeAttribute($value='')
    {
        $checkYear = explode("/", $this->project_id);
        // Get the last part (the year)
        $year = end($checkYear);

        $duplicateCount     = DB::table('tb_pmo')
                                ->select(DB::raw('COUNT(project_id) as count'))
                                ->where('project_id',$this->project_id)
                                ->havingRaw('COUNT(*) > 1')
                                ->count();

        $nik_pmo = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo.id','=','tb_pmo_assign.id_project')
                        ->where('tb_pmo.id',$this->id)
                        ->select('tb_pmo_assign.nik')->first()->nik;

        $amount_pr  = DB::table('tb_pr')
                    ->join('tb_pr_product_draft','tb_pr.id_draft_pr','=','tb_pr_product_draft.id_draft_pr')
                    // ->joinSub($subQuery, 'aggregated_product_pr', function ($join) {
                    //     $join->on('tb_pr_product_draft.id_product', '=', 'aggregated_product_pr.id'); // Join subquery on PID
                    // })
                    ->join('tb_pr_product','tb_pr_product_draft.id_product','=','tb_pr_product.id')
                    ->select(DB::raw('SUM(tb_pr_product.grand_total) as used_amount_pr'))
                    ->where('tb_pr.type_of_letter','EPR')
                    ->where('tb_pr.category','Perjalanan Dinas')
                    ->where('budget_type','OPERASIONAL') 
                    ->where('tb_pr.status','Done')
                    ->where('tb_pr.project_id',$this->project_id);

        $amount_claim  = DB::table('tb_claim_pid')
                        ->join('tb_claim','tb_claim.id','=','tb_claim_pid.id_claim')
                        ->select(DB::raw('SUM(tb_claim.nominal) as used_amount_claim'))
                        ->whereNotNull('tb_claim.nominal')
                        ->where('tb_claim.status','DONE')
                        ->where('tb_claim_pid.pid',$this->project_id);


        if ($duplicateCount > 0) {
            $checkLastTimeActivityImp = DB::table('tb_pmo_activity')
                    ->where('id_project',$this->id)
                    ->where('phase','Submit Final Project Closing Report')
                    ->select('date_time')
                    ->first();

            $checkRoleEngforPr = DB::table('tb_pmo')
                ->join('tb_id_project','tb_pmo.project_id','=','tb_id_project.id_project')
                ->join('tb_sbe','tb_id_project.lead_id','=','tb_sbe.lead_id')
                ->join('tb_sbe_config','tb_sbe.id','=','tb_sbe_config.id_sbe')
                ->join('tb_sbe_detail_config','tb_sbe_config.id','=','tb_sbe_detail_config.id_config_sbe')
                ->join('tb_sbe_detail_item','tb_sbe_detail_config.detail_item','=','tb_sbe_detail_item.id')->select('tb_sbe_detail_config.item')
                ->where('tb_pmo.project_id',$this->project_id)
                ->where('tb_sbe.status','Fixed')
                ->where('tb_sbe_config.status','Choosed');

            if (isset($checkLastTimeActivityImp)) {
                $checkLastTimeActivityImp = Carbon::parse($checkLastTimeActivityImp->date_time)->format('Y-m-d');
            }else{
                $latestDate = Carbon::create($year, 12, 31);
                $currentYear = Carbon::now()->year;

                if ($year < $currentYear) {
                  $formattedDate = $latestDate->format('Y-m-d');
                }else{
                  $formattedDate = date('Y-m-d');
                }

                $checkLastTimeActivityImp = $formattedDate;
                $checkLastTimeActivityImp = date('Y-m-d');
            }

            if ($this->project_type == 'maintenance') {
                $checkRoleEngforPr = $checkRoleEngforPr
                                    ->where('tb_sbe_config.project_type','Maintenance')
                                    ->distinct()
                                    ->pluck('item')
                                    ->toArray();

               $amount_claim  = $amount_claim->where('tb_claim.date','>=',$checkLastTimeActivityImp)->get()->first();
               $amount_pr     = $amount_pr->whereIn('roles_engineer',$checkRoleEngforPr)->orWhere('for',$this->project_pc)->get()->first();
            }else if ($this->project_type == 'implementation') {
                $checkRoleEngforPr = $checkRoleEngforPr
                                    ->where('tb_sbe_config.project_type','Implementation')
                                    ->distinct()
                                    ->pluck('item')
                                    ->toArray();

                $amount_claim  = $amount_claim->where('tb_claim.date','<=',$checkLastTimeActivityImp)->get()->first();
                $amount_pr     = $amount_pr->whereIn('roles_engineer',$checkRoleEngforPr)->orWhere('for',$this->project_pm)->get()->first();
            }
        }else{
            $amount_claim  = $amount_claim->get()->first();
            $amount_pr     = $amount_pr->get()->first();
        }

        if (empty($amount_pr)) {
            $amount_pr = 0;
        }else{
            $amount_pr = $amount_pr->used_amount_pr;
        }

        if (empty($amount_claim) && isset($amount_claim)) {
            $amount_claim = 0;
        }else{
            $amount_claim = $amount_claim->used_amount_claim;
        }

        $amount_settlement  = DB::table('tb_settlement_pid')
                        ->join('tb_settlement','tb_settlement.id','=','tb_settlement_pid.id_settlement')
                        ->select(DB::raw('SUM(tb_settlement.nominal) as used_amount_settlement'))
                        // ->whereYear('tb_settlement_pid.date_add',$year)
                        ->whereNotNull('tb_settlement.nominal')
                        ->where('tb_settlement.status','DONE')
                        ->where('tb_settlement_pid.pid',$this->project_id)
                        // ->where('tb_settlement.issuance',$nik_pmo)
                        ->get()->first();

        if (empty($amount_settlement)) {
            $amount_settlement = 0;
        }else{
            $amount_settlement = $amount_settlement->used_amount_settlement;
        }

        return $amount_pr + $amount_settlement + $amount_claim;
    }

    public function getHealthStatusAttribute($value='')
    {
        if ($this->getActualSbeAttribute() == 0 || $this->getPlanSbeAttribute() == 0) {
            $percentageAvgSbe = 0;
        }else{
            $percentageAvgSbe = $this->getActualSbeAttribute() / $this->getPlanSbeAttribute() * 100;
        }

        if ($percentageAvgSbe > 90 && $percentageAvgSbe < 100) {
            $healthStatus = 'Almost Over Budget';
        }else if($percentageAvgSbe < 90){
            if ($percentageAvgSbe == 0) {
                $healthStatus = '-';
            }else{
                $healthStatus = 'On Budget';
            }
        }else if ($percentageAvgSbe > 100) {
            $healthStatus = 'Over Budget';
        }

        return $healthStatus;
    }

    public function getSbeAttribute($value='')
    {
        $data = Sbe::join('tb_id_project','tb_sbe.lead_id','=','tb_id_project.lead_id')
                ->join('tb_sbe_config','tb_sbe.id','=','tb_sbe_config.id_sbe')
                ->where('tb_sbe_config.status','Choosed')
                ->select('tb_sbe_config.id','tb_sbe_config.project_type')
                ->where('id_project',$this->project_id)
                ->where('tb_sbe.status','Fixed')
                ->groupBy('tb_sbe_config.project_type','tb_sbe_config.id')
                ->orderByRaw("FIELD(tb_sbe_config.project_type, 'Implementation', 'Maintenance', 'Supply Only')");
            

        if ($this->getTypeProjectAttribute() == 'Maintenance & Managed Service') {
            $data = $data->where('tb_sbe_config.project_type','Maintenance')
                ->get()->makeHidden([
                    'link_document'
                ]);
        }else if($this->getTypeProjectAttribute() == 'Implementation') {
            $data = $data->where('tb_sbe_config.project_type','Implementation')
                ->get()->makeHidden([
                    'link_document'
                ]);
        }else if ($this->getTypeProjectAttribute() == 'Supply Only') {
            $data = $data->where('tb_sbe_config.project_type','Supply Only')
                ->get()->makeHidden([
                    'link_document'
                ]);
        }else if ($this->getTypeProjectAttribute() == 'Implementation + Maintenance & Managed Service') {
            $data = $data
                ->get()->makeHidden([
                    'link_document'
                ]);
        }

        return $data;
    }
}
