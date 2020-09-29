<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryAuth extends Model
{
    //
    protected $table = 'tb_history_auth';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nik', 'ip_address', 'datetime'
    ];

    public $timestamps = false;
}
