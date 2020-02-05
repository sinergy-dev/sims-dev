<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Mail\EmailRemainderWeekly;

class CustomRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CustomRemainder:all_remainder';

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
        $users = DB::table('users')
            ->whereIn('nik',function($query){
                $query->select('nik')
                    ->from('sales_lead_register')
                    ->where('result' ,'TP')
                    ->orWhere('result' ,'SD')
                    ->groupBy('nik');
            })
            ->where('email','<>','presales@sinergy.co.id')
            ->select('nik','name','email')
            ->get();

        $parameterEmail = collect();

        foreach ($users as $key => $user) {
            Mail::to($user->email)->send(new EmailRemainderWeekly(collect([
                    "to" => $user->name,
                    "proses_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->whereRaw('(`result` = "SD" OR `result` = "TP")')->count(),
                    "tp_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->where('result' ,'TP')->count(),
                    "tp_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik',$user->nik)
                        ->where('result' ,'TP')
                        ->get(),
                    "sd_count" => DB::table('sales_lead_register')->where('nik',$user->nik)->where('result' ,'SD')->count(),
                    "sd_detail" => DB::table('sales_lead_register')
                        ->select('sales_lead_register.lead_id','tb_contact.brand_name','sales_lead_register.opp_name')
                        ->join('tb_contact','sales_lead_register.id_customer','=','tb_contact.id_customer')
                        ->where('nik',$user->nik)
                        ->where('result' ,'SD')
                        ->get(),
                ])
            ));
        }
    }
}
