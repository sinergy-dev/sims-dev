<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tb_task';
    protected $primaryKey = 'id_task';
    protected $fillable = ['nik','task_name','description', 'task_date','status'];
}
