<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use DB;
use Carbon\Carbon;
use App\Timesheet;

class UpdateStatusTimesheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateStatusTimesheet:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status planned timesheet';

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
        $data = DB::table('tb_timesheet')->select('end_date','id')->where('schedule','Planned')->where('status',null)->where('end_date',date('Y-m-d'))->get();

        foreach ($data as $value) {
            // print_r($value->id);
            $update = Timesheet::where('id',$value->id)->first();
            $update->status = 'Undone';
            $date = Carbon::now();
            $update->end_date = $date->addDays(1);
            $update->save();
        }
        // print_r($data);
        syslog(LOG_ERR, "All user has been check for end date's timesheet");
    }
}
