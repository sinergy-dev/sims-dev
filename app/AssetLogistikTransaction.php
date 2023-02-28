<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetLogistikTransaction extends Model
{
    protected $table = 'tb_asset_logistik_transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_barang', 'nik_peminjam', 'qty_awal','status', 'keterangan', 'no_transac', 'qty_akhir', 'note'];
}
