<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CutiDetil extends Model
{
    protected $table = 'tb_cuti_detail';
    protected $primaryKey = 'idtb_cuti_detail';
    protected $fillable = ['id_cuti', 'date_off'];
    public $timestamps = false;
}
