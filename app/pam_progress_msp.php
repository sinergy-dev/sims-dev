<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pam_progress_msp extends Model
{
    protected $table = 'tb_pam_progress_msp';
    protected $primaryKey = 'id_progress';
    protected $fillable = ['keterangan', 'status', 'amount'];
}
