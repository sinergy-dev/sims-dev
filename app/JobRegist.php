<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobRegist extends Model
{
    protected $table = 'tb_job_regist';
    protected $guarded = [];

    public function career()
    {
        return $this->belongsTo('App\Career','id_job');
    }
}
