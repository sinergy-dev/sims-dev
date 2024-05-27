<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtAssign extends Model
{
    protected $table = 'tb_asset_management_assign';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'id_asset_peripheral',
        'date_add'
    ];

    public $timestamps = false;
}
