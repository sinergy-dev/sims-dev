<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetHR extends Model
{
    protected $table = 'tb_asset_hr';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nik', 'nama_barang', 'qty','description','availability','note','merk'];
}
