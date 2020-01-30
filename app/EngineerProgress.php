<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngineerProgress extends Model
{
    protected $table = 'tb_engineer_progress';
    protected $primaryKey = 'id_engineer_progress';
    protected $fillable = ['id_engineer','ket'];
    public $timestamps = false;
}
