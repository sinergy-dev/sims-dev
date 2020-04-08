<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailMessenger extends Model
{
    protected $table = 'tb_detail_messenger';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['id_messenger', 'finish_time'];
}
