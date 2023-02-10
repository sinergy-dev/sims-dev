<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMOIssue extends Model
{
	protected $table = 'tb_pmo_issue';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','issue_description','date_time','solution_plan','owner','rating_severity','actual_date','expected_date','status'];
    public $timestamps = false;
}
