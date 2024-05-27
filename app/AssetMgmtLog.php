<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtLog extends Model
{
    protected $table = 'tb_asset_management_log';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'operator',
        'date_add'
    ];

    public $timestamps = false;
}
