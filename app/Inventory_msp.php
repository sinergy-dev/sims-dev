<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory_msp extends Model
{
    protected $table = 'inventory_produk_msp';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['kode_barang','nama','kategori','tipe', 'qty','qty_sn','note'];
}
