<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class solution_design extends Model
{
   protected $table = 'sales_solution_design';
   protected $primaryKey = 'id_sd';
   protected $fillable = ['lead_id','nik','assessment','pov','pb','pd','priority','project_size', 'assessment_date', 'pov_date', 'pd_date'];
    //
}
