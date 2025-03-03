<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Mail\EmailRemainderWeekly;

class SalesRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SalesRemainder:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This remainder for check 2 condition, whene 1 month phase is passed by some lead, and 3 month';

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
        $years = [date('Y'),date('Y', strtotime('-1 year'))];

        $users = DB::table('users')
            ->whereIn('nik',function($query) use ($years){
                $query->select('nik')
                    ->from('sales_lead_register')
                    ->where('result' ,'TP')
                    ->orWhere('result' ,'SD')
                    ->whereIn('year',$years)
                    ->groupBy('nik');
            })
            ->where('status_karyawan','!=','dummy')
            ->where('email','<>','presales@sinergy.co.id')
            ->select('nik','name','email')
            ->get();

        $users_ta = DB::table('users')
            ->whereIn('nik',function($query) use ($years){
                $query->select('nik_ta')
                    ->from('sales_solution_design')->join('sales_lead_register','sales_lead_register.lead_id','sales_solution_design.lead_id')
                    ->where('result' ,'TP')
                    ->whereIn('year',$years)
                    ->orWhere('result' ,'SD')
                    ->groupBy('nik_ta');
            })
            ->where('status_karyawan','!=','dummy')
            ->select('nik','name','email')
            ->get();

        $users_vp = DB::table('users')->join('role_user','role_user.user_id','users.nik')->join('roles','roles.id','role_user.role_id')
            ->where('status_karyawan','!=','dummy')
            ->select('nik','roles.name','email')
            ->where('roles.name','VP Solutions & Partnership Management')
            ->get();

        $parameterEmail = collect();

        foreach ($users as $key => $user) {
            Mail::to($user->email)->send(new EmailRemainderWeekly(collect([
                    "to" => $user->name,
                    "proses_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->whereIn('year',$years)->whereRaw('(`result` = "SD" OR `result` = "TP")')->count(),
                    "tp_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->whereIn('year',$years)->where('result' ,'TP')->count(),
                    "tp_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik',$user->nik)->whereIn('year',$years)
                        ->where('result' ,'TP')
                        ->get(),
                    "sd_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->whereIn('year',$years)->where('result' ,'SD')->count(),
                    "sd_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik',$user->nik)->whereIn('year',$years)
                        ->where('result' ,'SD')
                        ->get(),
                    "open_count" => 0,
                    "open_detail" => [],
                ])
            ));
        }

        foreach ($users_vp as $key => $user) {
            Mail::to($user->email)->send(new EmailRemainderWeekly(collect([
                    "to" => $user->name,
                    "proses_count" => DB::table('sales_lead_register')->whereIn('year',$years)->whereRaw('(`result` = "SD" OR `result` = "TP")')->count(),
                    "tp_count" => DB::table('sales_lead_register')->whereIn('year',$years)->where('result' ,'TP')->count(),
                    "tp_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')                        
                        ->where('result' ,'TP')->whereIn('year',$years)
                        ->get(),
                    "sd_count" => DB::table('sales_lead_register')->whereIn('year',$years)->where('result' ,'SD')->count(),
                    "sd_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')                        
                        ->where('result' ,'SD')->whereIn('year',$years)
                        ->get(),
                    "open_count" => DB::table('sales_lead_register')->whereIn('year',$years)->where('result' ,'')->count(),
                    "open_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('result' ,'')->whereIn('year',$years)
                        ->distinct()
                        ->get(),
                ])
            ));
        }

        foreach ($users_ta as $key => $user) {
            Mail::to($user->email)->send(new EmailRemainderWeekly(collect([
                    "to" => $user->name,
                    "proses_count" => DB::table('sales_lead_register')->select('sales_solution_design.lead_id','nik_ta')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')->where('nik_ta',$user->nik)->whereRaw('(`result` = "SD" OR `result` = "TP" OR `result` = "")')->whereIn('year',$years)->groupBy('sales_solution_design.lead_id','nik_ta')->get()->count(),
                    "tp_count" => DB::table('sales_lead_register')->select('sales_solution_design.lead_id','nik_ta')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')->where('nik_ta',$user->nik)->where('result' ,'TP')->whereIn('year',$years)->groupBy('sales_solution_design.lead_id','nik_ta')->get()->count(),
                    "tp_detail" => DB::table('sales_lead_register')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik_ta',$user->nik)
                        ->where('result' ,'TP')->whereIn('year',$years)
                        ->distinct()
                        ->get(),
                    "sd_count" => DB::table('sales_lead_register')->select('sales_solution_design.lead_id','nik_ta')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')->where('nik_ta',$user->nik)->where('result' ,'SD')->whereIn('year',$years)->groupBy('sales_solution_design.lead_id','nik_ta')->get()->count(),
                    "sd_detail" => DB::table('sales_lead_register')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik_ta',$user->nik)
                        ->where('result' ,'SD')->whereIn('year',$years)
                        ->distinct()
                        ->get(),
                    "open_count" => DB::table('sales_lead_register')->select('sales_solution_design.lead_id','nik_ta')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')->where('nik_ta',$user->nik)->where('result' ,'')->whereIn('year',$years)->groupBy('sales_solution_design.lead_id','nik_ta')->get()->count(),
                    "open_detail" => DB::table('sales_lead_register')->join('sales_solution_design','sales_lead_register.lead_id','sales_solution_design.lead_id')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik_ta',$user->nik)
                        ->where('result' ,'')->whereIn('year',$years)
                        ->distinct()
                        ->get(),
                ])
            ));
        }
    }
}
