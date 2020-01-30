<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tech_asset extends Model
{
    protected $table = 'tb_asset';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nik', 'nama_barang', 'qty','description', 'status', 'serial_number', 'total_pinjam', 'status_pinjam'];
    public $timestamps = false;
}
