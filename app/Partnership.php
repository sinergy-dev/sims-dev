<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\PartnershipCertification;
use App\PartnershipTarget;
use DB;

class Partnership extends Model
{
    protected $table = 'tb_partnership';
    protected $primaryKey = 'id_partnership';
    protected $fillable = ['id_partnership', 'partner', 'level', 'renewal_date', 'annual_fee', 'sales_target', 'sales_certification', 'engineer_certification', 'type', 'levelling', 'cam', 'cam_email', 'cam_phone', 'email_support', 'id_mitra', 'logo'];

    protected $appends = ['cert_user', 'target', 'target_count', 'total_cert', 'total_cert_integer'];

    public function certification(){
        return $this->hasMany('App\PartnershipCertification','id_partnership','id_partnership')->orderBy('level_certification');
    }

    public function target()
    {
        return $this->hasMany('App\PartnershipTarget','id_partnership','id_partnership')->orderBy('target');
    }

    public function getCertUserAttribute()
    {
        // return PartnershipCertification::where('id_partnership',$this->id_partnership)->get();
        $certs = PartnershipCertification::where('id_partnership',$this->id_partnership)->get();
        foreach($certs as $cert){
            $cert->name = User::select('name')->where('name', $cert->name)->first()->name;
            $cert->avatar = User::select('avatar')->where('name', $cert->name)->first()->avatar;
            $cert->gambar = User::select('gambar')->where('name', $cert->name)->first()->gambar;
        }
        return $certs->groupBy('level_certification');
    }

    public function getTotalCertAttribute()
    {
        $total = PartnershipCertification::selectRaw("COUNT(`level_certification`) as `total_cert`, CONCAT(COUNT(`level_certification`), ': ', `level_certification`, '<br>') as `combine`")->where('id_partnership', $this->id_partnership)->groupby('level_certification')->orderBy('total_cert', 'desc')->orderBy('level_certification', 'asc')->get();

        return $total;
    }

    public function getTotalCertIntegerAttribute()
    {
        $total = PartnershipCertification::selectRaw("COUNT(`level_certification`) as `total_cert`")->where('id_partnership', $this->id_partnership)->groupby('id_partnership')->get();
        return $total;
    }

    public function getTargetAttribute()
    {
        $statusDone = PartnershipTarget::where('id_partnership',$this->id_partnership)->where('status', 'Done')->count();
        $statusAll = PartnershipTarget::where('id_partnership',$this->id_partnership)->count();

        return $statusDone . '/' . $statusAll;
    }

    public function getTargetCountAttribute()
    {
        $statusAll = PartnershipTarget::where('id_partnership',$this->id_partnership)->count();
        $statusDone = PartnershipTarget::where('id_partnership',$this->id_partnership)->where('status', 'Done')->count();
        
        $total_count = DB::table(function ($sub) use ($statusDone, $statusAll){
            $sub->selectRaw($statusDone . "/" . $statusAll . " AS `target_count_integer`");
        }, 'target_count_integer');

        return $total_count->get()->values();
    }
}
