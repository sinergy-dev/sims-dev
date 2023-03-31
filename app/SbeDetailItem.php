<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeDetailItem extends Model
{   
    protected $table = 'tb_sbe_detail_item';
    protected $primaryKey = 'id';
    protected $fillable = ['detail_item','price'];
}
