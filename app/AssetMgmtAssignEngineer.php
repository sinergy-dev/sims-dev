<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtAssignEngineer extends Model
{
    protected $table = 'tb_asset_management_assign_engineer';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_asset',
        'engineer_atm',
        'date_add'
    ];

    public $timestamps = false;
}
