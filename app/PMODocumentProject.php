<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class PMODocumentProject extends Model
{
    protected $table = 'tb_pmo_doc_project';
    protected $primaryKey = 'id';
    protected $fillable = ['id_project','id_document','sub_task', 'date_time'];
    public $timestamps = false;

    protected $appends = ['show_document'];

    public function getShowDocumentAttribute()
    {
        $document = DB::table('tb_pmo_document')->join('tb_pmo_doc_project','tb_pmo_doc_project.id_document','=','tb_pmo_document.id')->join('gantt_tasks_pmo','gantt_tasks_pmo.id',"=","tb_pmo_doc_project.sub_task")->get();

        return $document;
    }
}
