<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetLogistik extends Model
{
    protected $table = 'tb_asset_logistik';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nik', 'nama_barang', 'qty','description', 'merk'];
}
