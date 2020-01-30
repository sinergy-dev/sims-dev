<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PR_MSP extends Model
{
    protected $table = 'tb_pr_msp';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_pr', 'position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id'];
    public $timestamps = false;
}
