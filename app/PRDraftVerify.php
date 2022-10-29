<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PRDraftVerify extends Model
{
    protected $table = 'tb_pr_draft_verify';
	
	protected $primaryKey = 'id';
	
	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'verify_type_of_letter',
		'verify_category',
		'verify_to',
		'verify_email',
		'verify_phone',
		'verify_fax',
		'verify_attention',
		'verify_title',
		'verify_address',
		'verify_issuance',
		'verify_request_method',
		'verify_pid',
		'verify_lead_id',
		'verify_term_payment',
		'verify_nominal'
	];
}
