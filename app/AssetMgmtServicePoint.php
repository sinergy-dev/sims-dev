<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtServicePoint extends Model
{
    protected $table = 'tb_asset_management_service_point';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'service_point',
        'detail_location',
        'latitude',
        'longitude',
        'date_add'
    ];

    public $timestamps = false;
}
