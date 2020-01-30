<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TB_Contact extends Model
{
	protected $table = 'tb_contact';
	protected $primaryKey = 'id_customer';
   	protected $fillable = ['code','customer_legal_name','brand_name', 'office_building', 'street_address', 'city', 'province', 'postal', 'phone'];
}
