<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOActivity extends Model
{
    protected $table = 'tb_pmo_activity';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','phase','operator','activity','date_time'];
    public $timestamps = false;
}
