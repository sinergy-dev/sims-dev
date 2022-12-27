<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PR;
use DB;

class SalesProject extends Model
{
 	protected $table = 'tb_id_project';
    protected $primaryKey = 'id_pro';
    protected $fillable = ['customer_name','id_project','nik', 'no_po_customer', 'id_contact','name_project', 'amount_usd','amount_idr','date','note','sales_name'];

    // protected $appends = ['get_pr'];

    // public function getGetPrAttribute()
    // {
    //     $data = SalesProject::join('tb_pr', 'tb_pr.project_id', 'tb_id_project.id_project')
    //     	->select('no_pr', 'tb_pr.amount', DB::raw("(CASE WHEN (tb_pr.title is null) THEN tb_pr.description ELSE tb_pr.title END) as title"))
    //         ->where('id_project', $this->id_project)
    //         ->get();

    //     return $data;
    // }
}
