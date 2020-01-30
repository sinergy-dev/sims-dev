<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_Transaction_Tech_Asset extends Model
{
    protected $table = 'tb_detail_asset_transaction';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['created_at', 'updated_at'];
}
