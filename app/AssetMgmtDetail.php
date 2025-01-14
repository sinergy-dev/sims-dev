<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
        'accessoris',
        'date_add',
        'pr'
    ];

    public $timestamps = false;

    protected $appends = ['document_name'];

    public function getDocumentNameAttribute()
    {
        $data = DB::table('tb_asset_management_dokumen')
            ->join('tb_asset_management_detail','tb_asset_management_detail.id','=','tb_asset_management_dokumen.id_detail_asset')
            ->select(
                'tb_asset_management_dokumen.document_name as docBAST',
                'tb_asset_management_dokumen.document_location as docLocBAST',
                'tb_asset_management_dokumen.link_drive as driveBAST',
                'tb_asset_management_dokumen.id',
            )
            ->where('id_detail_asset', $this->id)
            ->get();

        return $data;
    }
}
