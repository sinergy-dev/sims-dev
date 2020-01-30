<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory_produk';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['kode_barang','nama','kategori','tipe', 'qty','note'];
}
