<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipTechnology extends Model
{
    protected $table = 'tb_partnership_technology';
    protected $primaryKey = 'id';
    protected $fillable = ['id_partnership', 'technology'];
}
