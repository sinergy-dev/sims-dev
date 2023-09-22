<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feelings extends Model
{
    //
    protected $table = 'tb_feelings';
    protected $primaryKey = 'idtb_feelings';
    protected $fillable = ['nik', 'code_feeling', 'date_add'];
    public $timestamps = false;
}
