<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetLogistikChangeLog extends Model
{
    protected $table = 'tb_asset_logistik_changelog';
    protected $primaryKey = 'id';
    protected $fillable = ['nik', 'id_barang', 'qty','created_at', 'updated_at','status'];
}
