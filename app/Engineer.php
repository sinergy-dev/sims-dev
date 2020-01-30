<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engineer extends Model
{
    protected $table = 'tb_engineer';
    protected $primaryKey = 'id_engineer';
    protected $fillable = ['nik','lead_id','status','result'];
    public $timestamps = false;
}
