<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PMORisk extends Model
{
    protected $table = 'tb_pmo_identified_risk';
    protected $primaryKey = 'id';
    protected $fillable = ['risk_description','risk_owner', 'impact_to', 'impact', 'risk_response', 'likelihood', 'impact_description', 'impact_rank', 'due_date', 'review_date', 'status', 'response_plan', 'date_time'];
    public $timestamps = false;
}
