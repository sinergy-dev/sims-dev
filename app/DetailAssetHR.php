<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailAssetHR extends Model
{
    protected $table = 'tb_asset_hr_transaction';
    protected $primaryKey = 'id_transaction';
    protected $fillable = ['id_barang', 'nik_peminjam', 'qty','status', 'keterangan', 'tgl_peminjaman', 'tgl_pengembalian', 'note', 'keperluan', 'no_peminjaman', 'note', 'no_transac'];
}
