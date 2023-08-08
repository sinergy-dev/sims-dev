<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\Mail\ReminderUpdateTimesheet;
use Mail;

class ReminderUpdateStatusTimesheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReminderUpdateStatusTimesheet:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder kirim email update status timesheet planned';

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
        $data = DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('end_date','id','users.name','activity')->where('schedule','Planned')->where('status',null)->where('end_date',date('Y-m-d'))->get();

        $getNik = DB::table('tb_timesheet')->select('nik')->where('schedule','Planned')->where('status',null)->where('end_date',date('Y-m-d'))->pluck('nik');

        $getEmail = DB::table('users')->whereIn('users.nik',$getNik)
                    ->select('nik','email','name')
                    ->get();

        // print_r($data);

        foreach ($getEmail as $key => $data) {
            Mail::to($data->email)->send(new ReminderUpdateTimesheet(
                $all = collect([
                    "to" => $data->name,
                    "data" => DB::table('tb_timesheet')->join('users','users.nik','tb_timesheet.nik')->select('end_date','start_date','id','users.name','activity')->where('schedule','Planned')->where('status',null)->where('end_date',date('Y-m-d'))->where('tb_timesheet.nik',$data->nik)->get()
                ])
            ));
        }
    }
}
