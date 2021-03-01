<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetHrRequest extends Model
{
    //
    protected $table = 'tb_asset_hr_request';
    protected $primaryKey = 'id_request';
    protected $fillable = ['kategori_request', 'nama', 'qty','nik','status','link','merk','created_at','updated_at'];
}
