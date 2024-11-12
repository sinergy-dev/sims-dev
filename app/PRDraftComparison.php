<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PRCompare;
use App\PR;
use DB;
use Auth;

class PRDraftComparison extends Model
{
    protected $table = 'tb_pr_draft';
	
	protected $primaryKey = 'id';
	
	protected $fillable = [
		'id',
		'type_of_letter',
		'category',
		'to',
		'email',
		'phone',
		'fax',
		'attention',
		'title',
		'address',
		'issuance',
		'request_method',
		'pid',
		'lead_id',
		'term_payment',
		'quote_number',
		'nominal',
		'status',
		'status_tax',
        'isCommit',
        'created_at'
	];

	protected $appends = ['comparison'];

    public function getComparisonAttribute()
    {
        $data = PRCompare::join('tb_pr_draft', 'tb_pr_draft.id', '=', 'tb_pr_compare.id_draft_pr')
        		// ->join('tb_pr', 'tb_pr.id_draft_pr', '=', 'tb_pr_draft.id', 'left')
        		->join('users', 'users.nik', '=', 'tb_pr_draft.issuance')
        		->select('tb_pr_compare.to', 'tb_pr_compare.email', 'tb_pr_compare.phone', DB::raw("(CASE WHEN (tb_pr_compare.fax is null) THEN '-' ELSE tb_pr_compare.fax END) as fax"), 'tb_pr_compare.attention', 'tb_pr_compare.title', 'tb_pr_compare.address', 'tb_pr_compare.term_payment', 'tb_pr_compare.nominal', 'tb_pr_compare.note_pembanding', 'tb_pr_compare.id', 'tb_pr_compare.id_draft_pr', 'tb_pr_draft.type_of_letter', 'tb_pr_draft.request_method', 'tb_pr_draft.pid', 'tb_pr_draft.lead_id', 'name as issuance', 'tb_pr_compare.status', 'users.name',DB::raw("(CASE WHEN (tb_pr_draft.quote_number = 'null') THEN '-' ELSE quote_number END) as quote_number"),DB::raw("(CASE WHEN (`tb_pr_compare`.`status_tax` is null) THEN 'false' ELSE `tb_pr_compare`.`status_tax` END) as status_tax"), DB::raw("(CASE WHEN (`tb_pr_compare`.`tax_pb` is null) THEN 'false' WHEN (`tb_pr_compare`.`tax_pb` = '0') THEN 'false' ELSE `tb_pr_compare`.`tax_pb` END) as tax_pb"), DB::raw("(CASE WHEN (`tb_pr_compare`.`service_charge` is null) THEN 'false' WHEN (`tb_pr_compare`.`service_charge` = '0') THEN 'false' ELSE `tb_pr_compare`.`service_charge` END) as service_charge"), DB::raw("(CASE WHEN (`tb_pr_compare`.`discount` is null) THEN 'false' WHEN (`tb_pr_compare`.`discount` = '0') THEN 'false' ELSE `tb_pr_compare`.`discount` END) as discount"))
        		->where('tb_pr_compare.id_draft_pr', $this->id)
        		->orderBy('tb_pr_compare.id','asc')
        		->orderByRaw('FIELD(tb_pr_draft.status, "SAVED", "DRAFT", "REJECT", "VERIFIED", "COMPARING", "CIRCULAR", "DISAPPROVE", "FINALIZED", "SENDED", "REJECTED")')
        		->get();

        return $data->map(function ($item, $key){
        	$item->product = $item->product_detail;
        	$item->document = $item->document_detail;
        	return $item;
        });
    }
}
