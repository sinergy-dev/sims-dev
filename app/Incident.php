<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'dvg_im';
    protected $primaryKey = 'no';
    protected $fillable = ['date', 'chase', 'user', 'division', 'status', 'solution', 'time', 'impact'];
}