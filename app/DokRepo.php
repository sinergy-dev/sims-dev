<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DokRepo extends Model
{
    protected $table = 'tb_dokumen';
    protected $primaryKey = 'id_dokumen';
    protected $fillable = ['id_dokumen', 'nama', 'deskripsi'];
}
