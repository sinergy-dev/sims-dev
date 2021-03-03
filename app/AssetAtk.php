<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetAtk extends Model
{
    protected $table = 'tb_asset_atk';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nik', 'nama_barang', 'qty','description', 'merk'];
}
