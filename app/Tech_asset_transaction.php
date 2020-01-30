<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tech_asset_transaction extends Model
{
    protected $table = 'tb_asset_transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_barang', 'nik_peminjam', 'qty','status', 'keterangan', 'tgl_peminjaman', 'tgl_pengembalian', 'note', 'keperluan', 'no_peminjaman'];
    public $timestamps = false;
}
