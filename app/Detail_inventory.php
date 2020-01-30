<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_inventory extends Model
{
    protected $table = 'detail_inventory_produk';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['id_barang','serial_number','id_po','tgl_masuk','note','status'];
}
