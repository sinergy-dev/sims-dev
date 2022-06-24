<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnershipCertification extends Model
{
    protected $table = 'tb_partnership_certification';
    protected $primaryKey = 'id';
    protected $fillable = ['id_partnership', 'nik', 'type_certification', 'name_certification'];

    public function user(){
        return $this->hasOne('App\User','nik','nik');
    }
}
