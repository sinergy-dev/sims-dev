<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogAssetTech extends Model
{
    //
    protected $table = 'tb_log_asset_tech';
    protected $primaryKey = 'id';
    protected $fillable = ['nik', 'keterangan', 'created_at','updated_at'];
}
