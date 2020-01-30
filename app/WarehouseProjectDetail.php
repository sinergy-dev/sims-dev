<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProjectDetail extends Model
{
    protected $table = 'detail_inventory_project_transaction';
    protected $primaryKey = 'id_detail_project';
    protected $fillable = ['id_transaction','id_detail_barang','qty','created_at', 'updated_at'];
}
