<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori_Asset extends Model
{
    protected $table = 'tb_kategori_asset';
    protected $primaryKey = 'id_kat';
    protected $fillable = ['kategori', 'qty', 'desc'];
    public $timestamps = false;
}
