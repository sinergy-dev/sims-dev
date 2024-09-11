<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetMgmtDocument extends Model
{
    //
    protected $table = 'tb_asset_management_dokumen';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'id_detail_asset',
        'document_name',
        'document_location',
        'link_drive',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false;
}
