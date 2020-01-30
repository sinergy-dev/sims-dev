<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    protected $table = 'tb_partnership';
    protected $primaryKey = 'id_partnership';
    protected $fillable = ['id_partnership', 'partner', 'level', 'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type'];
}
