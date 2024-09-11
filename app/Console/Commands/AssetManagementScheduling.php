<?php

namespace App\Console\Commands;
use DB;

use Illuminate\Console\Command;

class AssetManagementScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AssetManagementScheduling:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduling asset move to another pid';

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
        $cek = DB::table('tb_asset_management_scheduling')->where('status','PENDING')->where('maintenance_start',date("Y-m-d"))->get();

        foreach ($cek as $value) {
            $updateScheduling = AssetMgmtScheduling::where('id_asset',$value->id_asset)->first();
            $updateScheduling->status = 'DONE';
            $updateScheduling->save();

            $getDetail = AssetMgmtDetail::where('id_asset',$value->id_asset)->orderby('id','desc')->first();
            $addDetail = new AssetMgmtDetail();
            $addDetail->id_asset = $getDetail->id_asset;
            $addDetail->pid = $value->pid;
            $addDetail->maintenance_start = $value->maintenance_start;
            $addDetail->maintenance_end = $value->maintenance_end;
            $addDetail->id_device_customer = $getDetail->id_device_customer;
            $addDetail->client = $getDetail->client;
            $addDetail->kota = $getDetail->kota;
            $addDetail->alamat_lokasi = $getDetail->alamat_lokasi;
            $addDetail->detail_lokasi = $getDetail->detail_lokasi;
            $addDetail->latitude = $getDetail->latitude;
            $addDetail->longitude = $getDetail->longitude;
            $addDetail->service_point = $getDetail->service_point;
            $addDetail->port = $getDetail->port;
            $addDetail->ip_address = $getDetail->ip_address;
            $addDetail->server = $getDetail->server;
            $addDetail->status_cust = $getDetail->status_cust;
            $addDetail->second_level_support = $getDetail->second_level_support;
            $addDetail->operating_system = $getDetail->operating_system;
            if (!empty($getDetail->installed_date)) {
                $addDetail->installed_date = $getDetail->installed_date;
            } else {
                $addDetail->installed_date = null;
            }

            $addDetail->license = $getDetail->license;

            if (!empty($getDetail->license_start_date)) {
                $addDetail->license_start_date = $getDetail->license_start_date;
            } else {
                $addDetail->license_start_date = null;
            }

            if (!empty($getDetail->license_end_date)) {
                $addDetail->license_end_date = $getDetail->license_end_date;
            } else {
                $addDetail->license_end_date = null;
            }
            
            $addDetail->save();
        }
    }
}
