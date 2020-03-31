<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRCrud extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'nik';
	protected $fillable = ['name', 'email', 'password', 'id_company', 'id_division', 'id_position', 'date_of_entry', 'date_of_birth', 'address', 'phone'];
	public $timestamps = false;
}
