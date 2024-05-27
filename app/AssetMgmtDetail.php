<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtDetail extends Model
{
    protected $table = 'tb_asset_management_detail';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'id_device_customer',
        'client',
        'pid',
        'kota',
        'alamat_lokasi',
        'detail_lokasi',
        'ip_address',
        'server',
        'port',
        'status_cust',
        'second_level_support',
        'operating_system',
        'version_os',
        'installed_date',
        'license',
        'license_start_date',
        'license_end_date',
        'maintenance_start_date',
        'maintenance_end_date',
        'date_add'
    ];

    public $timestamps = false;
}
