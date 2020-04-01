<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Sales2;
use App\Mail\EmailRemainderReport;

class ReportTerritoryRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReportTerritoryRemainder:reportterritoryremainder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remainder report bulanan customer by territory';

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
        //
        $users = DB::table('users')
            ->where('email','nabil@sinergy.co.id')
            ->orwhere('email','rony@sinergy.co.id')
            ->select('nik','name','email')
            ->get();

        $parameterEmail = collect();

        foreach ($users as $key => $user) {

            print_r($user->email);
            Mail::to($user->email)->send(new EmailRemainderReport(collect([
                    "to" => $user->name,
                    "ter"=> DB::table("tb_territory")->select("tb_territory.id_territory")->join("users","users.id_territory","=","tb_territory.id_territory","left")->join("sales_lead_register","sales_lead_register.nik","=","users.nik","left")->where('tb_territory.id_territory','like','TERRITORY%')->whereMonth('sales_lead_register.created_at',date("m"))
                        ->whereYear('sales_lead_register.created_at',date("Y"))->groupBy("tb_territory.id_territory")->get(),
                    "cus"=> Sales2::join('users','users.nik','=','sales_lead_register.nik')
                        ->join('tb_contact','tb_contact.id_customer','=','sales_lead_register.id_customer')
                        ->select('users.name','users.id_territory','tb_contact.brand_name','users.id_territory',
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "OPEN",1,NULL)) AS "INITIAL"'), 
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "",1,NULL)) AS "OPEN"'), 
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "SD",1,NULL)) AS "SD"'),
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "TP",1,NULL)) AS "TP"'),
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "WIN",1,NULL)) AS "WIN"'),
                            DB::raw('COUNT(IF(`sales_lead_register`.`result` = "LOSE",1,NULL)) AS "LOSE"'),
                            DB::raw('COUNT(*) AS `All`'))
                        ->where('result','!=','CANCEL')
                        ->where('result','!=','HOLD')
                        ->where('result','!=','SPECIAL')
                        ->whereMonth('sales_lead_register.created_at',date("m"))
                        ->whereYear('sales_lead_register.created_at',date("Y"))
                        ->where('id_territory','like','TERRITORY%')
                        ->where('sales_lead_register.result','!=','hmm')
                        ->groupBy('sales_lead_register.nik')
                        ->groupBy('sales_lead_register.id_customer')
                        ->get(),
                ])
            ));
        }
    }
}
