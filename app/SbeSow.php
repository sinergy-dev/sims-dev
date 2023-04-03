<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SbeSow extends Model
{
    protected $table = 'tb_sbe_sow';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id','id_sbe','sow','oos','date_add'];
    public $timestamps = false;
}
