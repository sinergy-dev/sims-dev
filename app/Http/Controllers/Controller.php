<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use DB;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function initMenuBase(){
   //  		$listMenuAll = DB::table('features')
			// ->orderBy('index_of','ASC')
			// ->whereIn('id',DB::table('roles_feature')
			// 	->whereIn('role_id',DB::table('role_user')
			// 		->where('user_id',Auth::User()->nik)
			// 		->pluck('role_id'))
			// 	->pluck('feature_id'))
			// ->orderBy('index_of','ASC')
			// ->get();

			// $groups = [];
			// foreach($listMenuAll->pluck('group')->unique()->values() as $group){
			// 	$listMenuEach = $listMenuAll->where('group',$group)->values();
			// 	array_push($groups, [
			// 		"text" => $group,
			// 		"status" => $listMenuEach[0]->notif_status,
			// 		"name"=>$listMenuEach[0]->name,
			// 		"icon_group" => $listMenuEach[0]->icon_group,
			// 		"children" => $listMenuEach
			// 	]);
			// }


    		$getName = DB::table('features')->pluck('name')->unique()->values();

			// return $listMenuAll;

			$getGroupAll = DB::table('features')->select('group')->whereNotIn('group',$getName)->whereIn('id',DB::table('roles_feature')
					->whereIn('role_id',DB::table('role_user')
						->where('user_id',Auth::User()->nik)
						->pluck('role_id'))
					->pluck('feature_id'))->groupBy('group');

			$getChildAll = DB::table($getGroupAll, 'temp')->join('features','features.group','temp.group')
				->whereIn('id',DB::table('roles_feature')
				->whereIn('role_id',DB::table('role_user')
					->where('user_id',Auth::User()->nik)
					->pluck('role_id'))
				->pluck('feature_id'))
				->select('features.group','url','icon_group','notif_status',DB::raw("REPLACE(`name`,'-','') as `name`"),'features.index_of')
				->orderBy('index_of','ASC')->get()->groupBy('group');

			$getGroupChild = DB::table('features')->select('group')->whereIn('group',$getName)->whereIn('id',DB::table('roles_feature')
					->whereIn('role_id',DB::table('role_user')
						->where('user_id',Auth::User()->nik)
						->pluck('role_id'))
					->pluck('feature_id'))->groupBy('group');

			$getChildLevelTwo = DB::table($getGroupChild, 'temp')->join('features','features.group','temp.group')
				->whereIn('id',DB::table('roles_feature')
				->whereIn('role_id',DB::table('role_user')
					->where('user_id',Auth::User()->nik)
					->pluck('role_id'))
				->pluck('feature_id'))
				->select('features.group','url','icon_group','notif_status',DB::raw("REPLACE(`name`,'-','') as `name`"),'features.index_of')
				->orderBy('index_of','ASC')->get()->groupBy('group');
			// $getChildLevelTwo->pluck('group')->unique()->values();
			// return $getChildLevelTwo->keys()->toArray();

			foreach ($getChildAll as $allData) {
				foreach ($allData as $key => $value) {
					$modified = $value->name;
					if (in_array($value->name, $getChildLevelTwo->keys()->toArray())) {
						// $value->child = collect(["group"=>$value->group,"name"=>$value->name]);
						$value->child[$modified] = $getChildLevelTwo[$value->name];
						$value->count = count($getChildLevelTwo[$value->name]);
					}else {
						$value->child = [];
						$value->count = '0';
					}
				}
			}

			$role_user = DB::table('role_user')->where('user_id','=',Auth::User()->nik)->first();
			if(!$role_user){
				$role_user = 1;
			} else {
				$role_user = $role_user->role_id;
			}

			return collect([
				'userRole' => DB::table('roles')->where('id','=',$role_user)->first(),
				'listMenu' => $getChildAll
			]);
	    		// code...
    }

    public function RoleDynamic($group){
		return DB::table('feature_item')
        ->where('group',$group)
          ->whereIn('id',DB::table('roles_feature_item')
            ->whereIn('roles_id',DB::table('role_user')
              ->where('user_id',Auth::User()->nik)
              ->pluck('role_id'))
            ->pluck('feature_item_id'))
          ->get()->pluck('item_id');
   
  	}
}
