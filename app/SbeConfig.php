<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class SbeConfig extends Model
{
    protected $table = 'tb_sbe_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id_sbe','project_type','project_location','duration','estimated_running','nominal','status','date_add'];
    public $timestamps = false;

    protected $appends = ['detail_config','get_function','detail_all_config_choosed','detail_config_nominal'];

    public function getDetailConfigAttribute()
    {
    	$data = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$this->id)->orderBy('item','asc')->distinct()->orderBy('detail_item','asc')->get()->groupby('item');
      // $data = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$this->id)->orderBy('tb_sbe_config.id','asc')->get()->groupby('item');

      // $sums = $data->sum(function ($group) {
      //     $group->sum('total_nominal');
      // });

      // $sums = $data->mapWithKeys(function ($group, $key) {
      //     return [$key => $group->sum('total_nominal')];
      // });

    	return $data;
    }

    public function getDetailAllConfigChoosedAttribute()
    {
      $data = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->join('tb_sbe_detail_item','tb_sbe_detail_item.id','tb_sbe_detail_config.detail_item')->select('item','tb_sbe_detail_item.detail_item','total_nominal','qty','tb_sbe_detail_config.price','manpower')->where('tb_sbe_config.id',$this->id)->where('status','Choosed')->orderBy('item','asc')->distinct()->get()->groupby('item')->reverse();

      // $data = $data->toArray();

      return $data;

      // $dataConfig = DB::table('tb_sbe_config')
      //     ->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')
      //     ->selectRaw('item')
      //     ->selectRaw('SUM(total_nominal) AS `total_nominal`')
      //     ->where('tb_sbe_config.id',$this->id)
      //     ->groupby('item')->get();

      // $dataConfig->toArray();

      // return gettype($dataConfig);

      // return array($data,$dataConfig);

      // return collect(["data"=>$data,"total_nominal"=>$dataConfig]);
    }

    public function getDetailConfigReverseAttribute()
    {
      $data = DB::table('tb_sbe_config')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->join('tb_sbe_detail_item','tb_sbe_detail_item.id','tb_sbe_detail_config.detail_item')->select('item','tb_sbe_detail_item.detail_item','total_nominal','qty','tb_sbe_detail_config.price','manpower')->where('tb_sbe_config.id',$this->id)->where('status','Choosed')->orderBy('tb_sbe_detail_config.id','asc')->get()->groupby('item')->reverse();

      return $data;
    }

    public function getGetFunctionAttribute()
    {
    	$getByOrderId = DB::table('tb_sbe_detail_config')->where('tb_sbe_detail_config.id_config_sbe',$this->id)->select('id','id_config_sbe','item','total_nominal')->orderBy('id','asc');

    	// return $getByOrderId->get();

    	$data = DB::table('tb_sbe_config')
    			// ->joinSub($getByOrderId, 'getByOrderId',function($join){
       //              $join->on("tb_sbe_config.id", '=', 'getByOrderId.id_config_sbe');
       //          })
    			->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')
    			->select('item','detail_item','total_nominal','qty','price','manpower')
    			// ->selectRaw('SUM(total_nominal) AS `total_nominal`')
    			->where('tb_sbe_config.id',$this->id)
          ->distinct(['item','detail_item','total_nominal','qty','price','manpower'])
          ->orderBy('item','asc')
    			->get()
          ->groupby('item');

      foreach($data as $key_point => $valueSumPoint){
        $total_nominal = array($key_point => 0);
        foreach($valueSumPoint as $datas){
          $data[$key_point] = collect([
            "item"=>$datas->item,
            "total_nominal"=> $total_nominal[$key_point] += $datas->total_nominal
          ]); 
        }
      }

      // $data = $data->toArray();

    	// return collect([
     //        'data'=>$data,
     //        'grand_total'=>DB::table('tb_sbe_config')->where('tb_sbe_config.id',$this->id)->first()->nominal
     //    ]);

    	// return array_reverse($data);
      return $data;
    }

    public function getDetailConfigNominalAttribute()
    {
      $data = DB::table('tb_sbe_config')->join('tb_sbe','tb_sbe.id','tb_sbe_config.id_sbe')->join('tb_sbe_detail_config','tb_sbe_detail_config.id_config_sbe','tb_sbe_config.id')->select('item','detail_item','total_nominal','qty','price','manpower')->where('tb_sbe_config.id',$this->id)->orderBy('item','asc')->distinct()->get();

      $total_nominal = 0;
      foreach($data as $key_point => $valueSumPoint){
        // return $valueSumPoint->total_nominal;
        $total_nominal += $valueSumPoint->total_nominal;
      }

      return $total_nominal;
    }
}
