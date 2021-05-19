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
    	$listMenuAll = DB::table('features')
			->orderBy('index_of','ASC')
			->whereIn('id',DB::table('roles_feature')
				->whereIn('role_id',DB::table('role_user')
					->where('user_id',Auth::User()->nik)
					->pluck('role_id'))
				->pluck('feature_id'))
			->orderBy('index_of','ASC')
			->get();

		$groups = [];
		foreach($listMenuAll->pluck('group')->unique()->values() as $group){
			$listMenuEach = $listMenuAll->where('group',$group)->values();
			array_push($groups, [
				"text" => $group,
				"icon_group" => $listMenuEach[0]->icon_group,
				"children" => $listMenuEach
			]);
		}
		$role_user = DB::table('role_user')->where('user_id','=',Auth::User()->nik)->first();
		if(!$role_user){
			$role_user = 1;
		} else {
			$role_user = $role_user->role_id;
		}

		return collect([
			'userRole' => DB::table('roles')->where('id','=',$role_user)->first(),
			'listMenu' => $groups
		]);
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
