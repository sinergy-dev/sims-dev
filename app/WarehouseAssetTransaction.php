<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseAssetTransaction extends Model
{
    protected $table = 'inventory_asset_transaction';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_barang','nik_peminjam','qty_awal', 'qty_akhir', 'status', 'tgl_pengembalian'];
}
