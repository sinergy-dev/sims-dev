<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtCategory extends Model
{
    protected $table = 'tb_asset_management_category';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_category',
        'name',
        'date_add'
    ];

    public $timestamps = false;
}
