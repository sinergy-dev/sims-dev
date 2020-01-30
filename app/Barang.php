<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'tb_barang';
    protected $primaryKey = 'id_item';
    protected $fillable = ['item_name', 'quantity', 'info'];
    public $timestamps = false;
}
