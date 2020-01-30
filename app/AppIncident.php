<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppIncident extends Model
{
    protected $table = 'tb_incident_app';
    protected $primaryKey = 'id_incident';
    protected $fillable = ['nik', 'nik_pic', 'date', 'status_problem', 'kasus', 'modul', 'solution', 'via'];
}
