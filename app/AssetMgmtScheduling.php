<?php

namespace App;
use App\AssetMgmt;
use DB;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtScheduling extends Model
{
    protected $table = 'tb_asset_management_scheduling';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'pid',
        'maintenance_end',
        'maintenance_start',
        'date_add',
        'status'
    ];

    public $timestamps = false;

    protected $appends = ['get_history'];

    public function getGetHistoryAttribute()
    {
        $data = AssetMgmt::join('tb_asset_management_detail', 'tb_asset_management_detail.id_asset', 'tb_asset_management.id')
            ->select('maintenance_start', 'maintenance_end','pid')
            ->addSelect(DB::raw("'DONE' as status"))
            ->where('tb_asset_management.id_asset', $this->id_asset)
            ->get();

        return $data;
    }
}
