<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AssetMgmt;
use DB;
use Mail;
use App\Mail\MailReminderMaintenanceEndAsset;

class ReminderMaintenanceEndAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ReminderMaintenanceEndAsset:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder ke Sales, PM/PC H-90 Hari sebelum maintenance end';

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
        $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
        $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');

        $getAll = DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
            ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','status','serial_number','maintenance_end')
            ->where('category_peripheral','-')
            ->orderBy('tb_asset_management.created_at','desc')->where(DB::raw("DATEDIFF(now(), maintenance_end)"), '=', '-90')->get()->pluck('pid'); 

        $getIdPmo = DB::table('tb_pmo')->whereIn('project_id',$getAll)->get()->pluck('id');

        $getPidPm = DB::table('tb_pmo')->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')->join('users','users.nik','tb_pmo_assign.nik')->whereIn('id_project',$getIdPmo)->select('name','email')->where('role','Project Coordinator');

        $dataAll = DB::table('users')
                    ->whereIn('nik',function($query) use ($getAll){
                        $query->select('users.nik')
                            ->from('tb_id_project')
                            ->join('sales_lead_register','sales_lead_register.lead_id','tb_id_project.lead_id')
                            ->join('users','users.nik','sales_lead_register.nik')
                            ->whereIn('id_project',$getAll)
                            ->groupBy('users.nik');
                    })
                    ->orWhereIn('nik',function($query) use ($getIdPmo){
                        $query->select('users.nik')
                            ->from('tb_pmo')
                            ->join('tb_pmo_assign','tb_pmo_assign.id_project','tb_pmo.id')
                            ->join('users','users.nik','tb_pmo_assign.nik')
                            ->whereIn('id_project',$getIdPmo)
                            ->where('role','Project Coordinator')
                            ->groupBy('users.nik');
                    })
                    ->select('nik','users.email','users.name')
                    ->get();

        foreach ($dataAll as $key => $data) {
            $getId = AssetMgmt::join('tb_asset_management_detail','tb_asset_management_detail.id_asset','tb_asset_management.id_asset')->select('tb_asset_management_detail.id_asset','detail_lokasi','tb_asset_management_detail.id');
            $getLastId = DB::table($getId,'temp')->groupBy('id_asset')->selectRaw('MAX(`temp`.`id`) as `id_last_asset`')->selectRaw('id_asset');
            Mail::to($data->email)->send(new MailReminderMaintenanceEndAsset(collect([
                "to" => $data->name,
                "data" => DB::table($getLastId, 'temp2')->join('tb_asset_management','tb_asset_management.id_asset','temp2.id_asset')->join('tb_asset_management_detail','tb_asset_management_detail.id','temp2.id_last_asset')
                    ->select('tb_asset_management_detail.pid','asset_owner','category','category_peripheral','tb_asset_management.id_asset','status','serial_number','client','pid',DB::raw("CONCAT(`detail_lokasi`, ' - ', `alamat_lokasi`, ' - ', `kota`) AS `lokasi`"),DB::raw("CONCAT(`maintenance_start`, ' s/d ', `maintenance_end`) AS `periode`"))
                    ->where('category_peripheral','-')->where(DB::raw("DATEDIFF(now(), maintenance_end)"), '=', '-90')->get(),
                ])
            ));
        }
    }
}
