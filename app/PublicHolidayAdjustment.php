<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicHolidayAdjustment extends Model
{
    //'
    protected $table = 'tb_cuti_adjustment';
    protected $primaryKey = 'id';
    protected $fillable = ['date', 'description', 'reason', 'date_add', 'date_modified'];
    public $timestamps = false;
}
