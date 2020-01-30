<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'tb_warehouse';
    protected $primaryKey = 'item_code';
    protected $fillable = ['item_code','id_item','name_item', 'quantity', 'information'];
}
