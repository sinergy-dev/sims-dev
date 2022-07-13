<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipTarget extends Model
{
    protected $table = 'tb_partnership_target';
    protected $primaryKey = 'id';
    protected $fillable = ['id_partnership', 'target', 'description', 'countable', 'status'];
}
