<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class pam extends Model
{
	protected $table = 'dvg_pam';
    protected $primaryKey = 'id_pam';
    protected $fillable = ['nik_admin', 'date_handover', 'no_pr', 'nominal', 'due_date', 'ket_pr', 'note_pr','status','personel','subject'];
    //
}
