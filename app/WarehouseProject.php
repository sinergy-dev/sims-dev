<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProject extends Model
{
    protected $table = 'inventory_project_transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_barang','nama_project','no_do','tgl_keluar', 'created_at', 'updated_at'];
}
