<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRNumber extends Model
{
    protected $table = 'tb_hr_number';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_letter','type_of_letter', 'divsion', 'pt', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'project_id','note'];
}
