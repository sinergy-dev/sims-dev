<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeDetailConfig extends Model
{
    protected $table = 'tb_sbe_detail_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id_config_sbe','item','detail_item','qty','price','total_nominal','date_add'];
    public $timestamps = false;
}
