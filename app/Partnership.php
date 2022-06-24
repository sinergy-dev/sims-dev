<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PartnershipCertification;

class Partnership extends Model
{
    protected $table = 'tb_partnership';
    protected $primaryKey = 'id_partnership';
    protected $fillable = ['id_partnership', 'partner', 'level', 'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type', 'levelling', 'cam', 'cam_email', 'cam_phone', 'email_support', 'id_mitra'];

    protected $appends = ['cert_user'];

    public function certification(){
        return $this->hasMany('App\PartnershipCertification','id_partnership','id_partnership')->orderBy('level_certification');
    }

    public function getCertUserAttribute()
    {
        // return PartnershipCertification::where('id_partnership',$this->id_partnership)->get();
        $certs = PartnershipCertification::where('id_partnership',$this->id_partnership)->get();
        foreach($certs as $cert){
            $cert->name = User::select('name')->where('nik', $cert->nik)->first()->name;
        }
        return $certs->groupBy('level_certification');
    }
}
