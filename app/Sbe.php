<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Sbe extends Model
{
    protected $table = 'tb_sbe';
    protected $primaryKey = 'id';
    protected $fillable = ['lead_id','nominal','status'];

    protected $appends = ['link_document'];

    public function getLinkDocumentAttribute()
    {
      $data = DB::table('tb_sbe_document')->join('tb_sbe','tb_sbe.id','=','tb_sbe_document.id_sbe')->select('link_drive')->where('tb_sbe.id',$this->id)->orderBy('tb_sbe_document.id','desc')->first();

      return empty($data->link_drive) ? "-" : $data->link_drive;     

      // return empty($data->link_drive)?(empty(DB::table('tb_sbe_document')->join('tb_sbe','tb_sbe.id','=','tb_sbe_document.id_sbe')->select('link_drive')->where('tb_sbe.id',$this->id)->orderBy('tb_sbe_document.id','desc')->first()->link_drive) ? "-" : DB::table('tb_sbe_document')->join('tb_sbe','tb_sbe.id','=','tb_sbe_document.id_sbe')->select('link_drive')->where('tb_sbe.id',$this->id)->orderBy('tb_sbe_document.id','desc')->first()->link_drive):$data->link_drive;
    }

    // public function getDetailConfigNominalAttribute()
    // {
    //   // return $this->lead_id;
    //   $data = DB::table('tb_sbe')->join('tb_sbe_config','tb_sbe_config.id_sbe','tb_sbe.id')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe.id',$this->id)->where('tb_sbe_config.status','Choosed')->orderBy('item','asc')->distinct()->get();

    //   $total_nominal = 0;
    //   foreach($data as $key_point => $valueSumPoint){
    //     $total_nominal += $valueSumPoint->total_nominal;
    //   }

    //   return $total_nominal;
    // }
}
