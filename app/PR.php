<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PR extends Model
{
    protected $table = 'tb_pr';
    protected $primaryKey = 'no';
    protected $fillable = ['no','no_pr', 'position', 'type_of_letter', 'month', 'to', 'attention', 'title', 'project', 'description', 'from', 'division', 'issuance', 'project_id','result', 'note', 'id_draft_pr'];
}
