<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOProgress extends Model
{
    protected $table = 'tb_pmo_progress';
    protected $primaryKey = 'id_pmo_progress';
    protected $fillable = ['id_pmo','tanggal','ket'];
    public $timestamps = false;
}
