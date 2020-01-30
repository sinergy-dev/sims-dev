<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ESMProgress extends Model
{
    protected $table = 'dvg_esm_progress';
    protected $primaryKey = 'id';
    protected $fillable = ['no','keterangan','status','amount','nik'];
}
