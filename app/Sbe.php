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
      $data = DB::table('tb_sbe')->join('tb_sbe_document','tb_sbe_document.id_sbe','tb_sbe.id')->select('link_drive')->where('tb_sbe.id',$this->id)->first();

      return empty($data->link_drive)?(empty(DB::table('tb_sbe')->join('tb_sbe_document','tb_sbe_document.id_sbe','tb_sbe.id')->select('link_drive')->where('tb_sbe.id',$this->id)->orderBy('tb_sbe_document.id','desc')->first()->link_drive) ? "-" : DB::table('tb_sbe')->join('tb_sbe_document','tb_sbe_document.id_sbe','tb_sbe.id')->select('link_drive')->where('tb_sbe.id',$this->id)->orderBy('tb_sbe_document.id','desc')->first()->link_drive):$data->link_drive;;
    }
}
