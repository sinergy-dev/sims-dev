<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PRNotesDetail;
use DB;

class PRNotes extends Model
{
    protected $table = 'tb_pr_notes';
	
	protected $primaryKey = 'id';

	public $timestamps = false;
	
	protected $fillable = [
		'id',
		'id_draft_pr',
		'resolve',
		'date_add',
		'operator',
		'notes'
	];

	protected $appends = ['reply', 'no_pr', 'image', 'issuance'];

	public function getReplyAttribute()
	{
		$data = PRNotesDetail::join('users', 'users.name', 'tb_pr_notes_detail.operator')->select('reply', 'operator', 'date_add', 'gambar', 'avatar')->where('id_notes', $this->id)->orderBy('date_add', 'asc')->get();
		return $data;
	}

	public function getNoPrAttribute()
	{
		$data = DB::table('tb_pr_notes')->join('tb_pr', 'tb_pr.id_draft_pr', 'tb_pr_notes.id_draft_pr')->select('no_pr')->where('id', $this->id)->first();
		return empty($data->no_pr)?$this->id:$data->no_pr;
	}

	public function getImageAttribute()
	{
		$data = DB::table('tb_pr_notes')->join('users', 'users.name', 'tb_pr_notes.operator')->select('gambar')->where('id', $this->id)->first();
		return empty($data->gambar)?'-':$data->gambar;
	}

	public function getIssuanceAttribute()
	{
		$data = DB::table('tb_pr_draft')->join('users', 'users.nik', 'tb_pr_draft.issuance')->where('id', $this->id_draft_pr)->first()->nik;
		return $data;
	}

	///////////
}
