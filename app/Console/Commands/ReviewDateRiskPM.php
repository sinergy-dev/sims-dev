<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Mail\MailReviewDateRiskPM;
use Mail;

class ReviewDateRiskPM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReviewDateRiskPM:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Review Date Identified Risk Management';

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
        $dataAll = DB::table('users')
                    ->whereIn('nik',function($query){
                        $query->select('nik')
                            ->from('tb_pmo_assign')
                            ->join('tb_pmo_identified_risk','tb_pmo_assign.id_project','tb_pmo_identified_risk.id_project')
                            ->whereRaw("(`status` =  'Active' OR `status` = 'active')")
                            ->where(DB::raw("DATEDIFF(now(), review_date)"), '=', '-1')
                            ->groupBy('nik');
                    })
                    ->select('nik','users.email','users.name')
                    ->get();

        foreach ($dataAll as $key => $data) {
            Mail::to($data->email)->send(new MailReviewDateRiskPM(collect([
                "to" => $data->name,
                "risk" => DB::table('tb_pmo_identified_risk')
                    ->join('tb_pmo','tb_pmo.id','tb_pmo_identified_risk.id_project')
                    ->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')
                    ->join('users','users.nik','tb_pmo_assign.nik')
                    ->select('users.email','users.name','risk_description','risk_response','tb_pmo.project_id','risk_owner','review_date','tb_pmo.id as id_pmo','tb_pmo_identified_risk.id as id_risk','project_type')
                    ->whereRaw("(`status` =  'Active' OR `status` = 'active')")
                    ->where('tb_pmo_assign.nik',$data->nik)
                    ->where(DB::raw("DATEDIFF(now(), review_date)"), '=', '-1')
                    ->get(),
                ])
            ));
        }
    }
}
