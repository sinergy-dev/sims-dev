<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Sales;
use App\SalesChangeLog;
use DB;
use Auth;
use Log;

class SalesUpdateStatusHold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SalesUpdateStatusHold:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Lead after 2 months not updated';

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
        $twoMonthsAgo = now()->subMonths(2)->format('Y-m-d');

        $years = [date('Y'),date('Y', strtotime('-1 year'))];
        // $years = [date('Y')];

        $getId = DB::table('sales_lead_register')->join('sales_change_log','sales_change_log.lead_id','sales_lead_register.lead_id')->select('sales_change_log.lead_id','id','year','sales_change_log.result')->where('sales_lead_register.nik','like','1%')->whereIn('year',$years);
        $getLastId = DB::table($getId,'temp')->groupBy('lead_id')->selectRaw('MAX(`temp`.`id`) as `last_id_log`')->selectRaw('lead_id');

        // return $getLastId->get();

        $data = DB::table($getLastId, 'temp2')->join('sales_change_log','sales_change_log.id','temp2.last_id_log')->join('sales_lead_register','sales_change_log.lead_id','sales_lead_register.lead_id')->select('status','sales_change_log.id','sales_change_log.created_at','sales_change_log.lead_id','sales_lead_register.result','sales_change_log.id','sales_change_log.result as result_log')
            ->where('sales_change_log.created_at', '<=', $twoMonthsAgo)
            ->whereRaw("(`sales_lead_register`.`result` = 'INITIAL' OR `sales_lead_register`.`result` = 'SD' OR `sales_lead_register`.`result` = '' OR `sales_lead_register`.`result` = 'TP')")->get();

        // $newLogCreated = false;
        foreach ($data as $key => $value) {

            $updateLead = Sales::where('lead_id', $value->lead_id)->first();
            $updateLead->result = 'HOLD';
            $updateLead->save();

            $newLog = new SalesChangeLog();
            $newLog->result = 'HOLD';
            $newLog->lead_id = $value->lead_id;
            $newLog->nik = 1171296100;
            $newLog->status = 'Automatically update status HOLD';
            $newLog->save();
        }    
    }
}
