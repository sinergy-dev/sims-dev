<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class pamProgress extends Model
{
	protected $table = 'dvg_pam_progress';
    protected $primaryKey = 'id_progress';
    protected $fillable = ['keterangan', 'status', 'amount'];
    //
}
