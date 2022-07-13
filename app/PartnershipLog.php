<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipLog extends Model
{
    protected $table = 'tb_partnership_log';
    protected $primaryKey = 'id';
    protected $fillable = ['id_partnership', 'description', 'nik'];
}
