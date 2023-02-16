<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\PMOProjectCharter;
use App\User;
use App\Mail\ReminderProjectCharter;
use Mail;

class ReminderCreateProjectCharter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReminderCreateProjectCharter:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder Create Project Charter';

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
        $data = DB::table('tb_pmo')->select('tb_pmo.id as id_pmo','project_id');
        $get_id_min = DB::table($data,'temp')->groupBy('project_id')->selectRaw('MIN(`temp`.`id_pmo`) as `id`');
        $getAll = DB::table($get_id_min,'temp2')->join('tb_pmo','tb_pmo.id','temp2.id')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->select('temp2.id','project_id','nik')->where('parent_id_drive',NULL)->pluck('nik');

        $dataAll = User::whereIn('users.nik',$getAll)
                    ->select('nik','email','name')
                    ->get();

        // var_dump($dataAll);

        foreach ($dataAll as $key => $data) {
            Mail::to($data->email)->send(new ReminderProjectCharter(collect([
                "to" => $data->name,
                "project" => DB::table('tb_pmo')
                    ->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')
                    ->join('tb_id_project','tb_id_project.id_project','tb_pmo.project_id')
                    ->select('project_id','project_type','sales_name','name_project')
                    ->where('project_type','!=','supply_only')
                    ->where('tb_pmo_assign.nik',$data->nik)
                    ->get(),
                ])
            ));
        }

        // return $dataAll;
    }
}
