<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseDetailProduk extends Model
{
    protected $table = 'detail_inventory_produk_msp';
    protected $primaryKey = 'id_detail_barang';
    protected $fillable = ['id_barang','serial_number','created_at', 'updated_at'];
}
