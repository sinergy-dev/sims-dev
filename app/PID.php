<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PID extends Model
{
    protected $table = 'tb_pid';
    protected $primaryKey = 'id_pid';
    protected $fillable = ['lead_id', 'amount_pid', 'date_po', 'no_po'];
}
