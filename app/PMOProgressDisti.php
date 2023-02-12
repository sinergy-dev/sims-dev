<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOProgressDisti extends Model
{
    protected $table = 'tb_pmo_progress_report_distribution';
    protected $primaryKey = 'id';
    protected $fillable = ['id_report'];
    public $timestamps = false;
}
