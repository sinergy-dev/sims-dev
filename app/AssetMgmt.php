<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmt extends Model
{
    protected $table = 'tb_asset_management';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'category',
        'category_peripheral',
        'asset_owner',
        'status',
        'vendor',
        'type_device',
        'serial_number',
        'spesifikasi',
        'rma',
        'notes'
    ];
}
