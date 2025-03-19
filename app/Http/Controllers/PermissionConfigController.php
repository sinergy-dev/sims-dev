<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use HttpOz\Roles\Models\Role;
use App\User;

class PermissionConfigController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testPermissionConfig(){ 
        return view('testPermissionConfig')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('permission_config')]);
    }

    public function changeFeatureItem(Request $req){
        sleep(1);
        $roleFeatureItem = DB::table('roles_feature_item')
            ->where('roles_id','=',$req->role)
            ->where('feature_item_id','=',$req->feature);

        if($roleFeatureItem->exists()){
            $roleFeatureItem->delete();
            return "deleted";
        } else {
            DB::table('roles_feature_item')
                ->insert([
                    'roles_id' => $req->role,
                    'feature_item_id' => $req->feature
                ]);
            return "added";
        }
    }

    public function getUserList(){

        $checkForAllFeatureItem = [7,33,17,39];

        $permitted = false;
        if(in_array(DB::table('role_user')->where('user_id',Auth::User()->nik)->first()->role_id,$checkForAllFeatureItem)){
            $permitted = true;
        }

        return collect([ "data" => DB::table('role_user')
            ->select(
                    'role_user.*',
                    DB::raw('`roles`.`group` AS `name_group`'),
                    DB::raw('`roles`.`name` AS `name_roles`'),
                    'users.name',
                    'roles_feature_each.each_feature'
                )
            ->join('users','role_user.user_id','=','users.nik')
            ->join('roles','role_user.role_id','=','roles.id')
            ->joinSub(DB::table('roles_feature')
                ->select(
                    'roles_feature.role_id',
                    DB::raw('GROUP_CONCAT(`features`.`name`) AS `each_feature`')
                )
                ->join('features','features.id','=','roles_feature.feature_id')
                ->groupBy('roles_feature.role_id'), 'roles_feature_each', function ($join) {
                $join->on('roles.id', '=', 'roles_feature_each.role_id');
            })
            ->where('status_karyawan', '!=', 'dummy')
            // ->take(1)
            ->get(),"permitted" => $permitted]);
    }

    public function getParameter(){
        $name = DB::table('users')
            ->select(
                    DB::raw('`nik` AS `id`'),
                    DB::raw('`name` AS `text`'),
                )
            ->where('id_company','=','1')
            ->where('status_karyawan','!=','dummy')
            ->get();

        $roles = DB::table('roles')
            ->select(
                DB::raw('`id` AS `id`'),
                DB::raw('`name` AS `text`'),
                DB::raw('`group` AS `group`'),
            )->get();

            $groups = [];
            foreach($roles->pluck('group')->unique()->values() as $group){
                array_push($groups, ["text" => strtoupper($group),"children" => $roles->where('group',$group)->values()]);
            }

            // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
            // return $groups;
        return collect(['name' => $name,'roles' => $groups]);
    }

    public function getParameterFeature(Request $req){
            $features = DB::table('features')
            ->select(
                DB::raw('`features`.`id` AS `id`'),
                DB::raw('`name` AS `text`'),
                DB::raw('`group` AS `group`'),
            )->whereNotIn('id', function($query) use ($req){
                $query->select('feature_id')
                            ->from('roles_feature')->where('role_id',$req->roles_id);
            })
            ->get();

            $groupsFeature = [];
            foreach($features->pluck('group')->unique()->values() as $group){
                array_push($groupsFeature, ["text" => strtoupper($group),"children" => $features->where('group',$group)->values()]);
            }

            // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
            // return $groups;
        return collect(['features' => $groupsFeature]);
    }

    public function getFeatureRole(){
        return DB::table('roles')
            ->select(
                    DB::raw('`roles`.`group` AS `name_group`'),
                    DB::raw('`roles`.`name` AS `name_roles`'),
                    DB::raw('`roles_feature_each`.`each_feature` AS `feature_name`')
                )
            // ->join('features','features.id','=','roles_feature.feature_id')
            ->joinSub(DB::table('roles_feature')
                ->select(
                    'roles_feature.role_id',
                    DB::raw('GROUP_CONCAT(`features`.`name`) AS `each_feature`')
                )
                ->join('features','features.id','=','roles_feature.feature_id')
                ->groupBy('roles_feature.role_id'), 'roles_feature_each', function ($join) {
                $join->on('roles.id', '=', 'roles_feature_each.role_id');
            })
            ->get();
        // return DB::table('roles')->get();
    }

    public function setRoles(Request $req){
        foreach ($req->id_role as $id_role) {
            User::find($req->id_user)->roles()->sync([$id_role]);
        }
        // return $req->id_role;
        return "Success";
    }

    public function setRolesFeature(Request $req){
        foreach ($req->id_feature as $id_feature) {
            DB::table('roles_feature')->insert([
                'role_id' => Role::find($req->id_role)->id,
                'feature_id' => $id_feature
            ]);
            // Role::find($req->id_role)->attachRole(DB::table('features')->find($id_feature));
        }
        // return $req->id_role;
        return "Success";
    }

    public function getParameterRoles(){
            $roles = DB::table('roles')
            ->select(
                DB::raw('`id` AS `id`'),
                DB::raw('`name` AS `text`'),
                DB::raw('`group` AS `group`'),
            )->get();

            $groups = [];
            foreach($roles->pluck('group')->unique()->values() as $group){
                array_push($groups, ["text" => strtoupper($group),"children" => $roles->where('group',$group)->values()]);
            }
            // return collect(["result" => ["text" => "Sales","children" => [["id"=>1],["id"=>2]]]]);
            // return $groups;
        return collect(['roles' => $groups]);
    }

    public function getRoles(Request $req){
        return DB::table('roles')->get();
    }

    public function getFeature(Request $req){
        return DB::table('features')->get();
    }

    public function addConfigRoles(Request $req){
        DB::table('roles')->insert([
                'name' => $req->name,
                'slug' => $req->slug,
                'description' => $req->description,
                'group' => $req->group
        ]);

        return "Success";
    }

    public function addConfigFeature(Request $req){
        if ($req->id_feature) {
            $group_name = DB::table('features')->where('id',$req->id_feature)->first();

            $groupId = DB::table('features')->select('id')->where('group',$group_name->group)->get();

            foreach($groupId as $groupId){
                DB::table('features')
                    ->where('id',$groupId->id)
                    ->update([
                    'icon' => $req->icon,
                    'icon_group' => $req->icon,
                ]);
            }
        }else{
            $index_of = DB::table('features')->latest('index_of')->first()->index_of + 1;

            DB::table('features')->insert([
                'name' => $req->name,
                'description' => $req->description,
                'group' => $req->group,
                'index_of'=> $index_of,
                'url'=>$req->url,
                'icon' => $req->icon,
                'icon_group' => $req->icon,
            ]);
        }
        

        return "Success";
    }

    public function addConfigFeatureItem(Request $req){
        DB::table('feature_item')->insert([
            'item_id' => $req->item_id,
            'group' => $req->group,
        ]);

        return "Success";
    }

    public function getRoleDetail(Request $req){
        return collect([
            'role' => DB::table('roles')->where('id','=',$req->id)->get()->first(),
            'holder' => DB::table('role_user')
                ->select('users.name')
                ->join('users','role_user.user_id','=','users.nik')
                ->where('role_id','=',$req->id)
                ->get()
        ]);
    }

    public function getConfigFeature(Request $req){
        return DB::table('features')->where('id',$req->id)->get();
    }

    public function getFeatureItem(Request $req){
        if($req->group == "All") {
            // $column2 = [];
            $return = [];

            return collect(['data' => $return]);
        } else {
            $column = DB::table('roles')->selectRaw('`id`,`name` AS `title`, REPLACE(`slug`,".","_") AS `name`,CONCAT("condition_",REPLACE(`slug`,".","_")) AS `data`');

            $column2 = $column->where('group','=',$req->group)
                ->get();

            $column = $column2->map(function ($item, $key) {
                $item->class = "text-center";
                return $item;
            })->filter(function ($item, $key) {
                return $item->title != "Admin";
            })->sortBy('title')->values();

            $return = DB::table('feature_item')
                ->select("feature_item.*");
                
            foreach ($column as $key => $value) {
                $return = $return->addSelect($value->name . ".condition_" . $value->name);
                $leftJoin = DB::table('roles_feature_item')
                    ->where('roles_id','=',$value->id);

                $join = DB::table('feature_item')
                    ->selectRaw('`feature_item`.`id`, CONCAT("' . "<label class='switch'><input class='featureItemCheck' type='checkbox' id='" . $value->id . "-" . '",' . '`feature_item`.`id`' . ',"\' ",' . "IF(`roles_feature_item_filtered`.`id` > 0,'checked','')" . ',"' . "><span class='slider round'></span></label>" . '") AS `condition_' . $value->name . '`')
                    ->leftJoinSub($leftJoin,'roles_feature_item_filtered',function($join){
                        $join->on('roles_feature_item_filtered.feature_item_id','=','feature_item.id');
                    });
                $return = $return->joinSub($join,$value->name,function($join) use ($value){
                    $join->on($value->name . '.id','=','feature_item.id');
                });

                // $return = $return->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `' . $value->name . '`');
                
                // $return = $return->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `' . $value->name . '`');
            }
            
            $column->prepend(['title' => "Item",'data' => "item_id"]);
            $column->prepend(['title' => "Feature",'data' => "group"]);

            return collect(['data' => $return->get(),'column' => $column]);
            // return $return->get();
        }

        // return DB::table('feature_item')
        //   ->selectRaw("*")
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `director`')
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `staff`')
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `admin`')
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `hrstaff`')
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `hrga`')
        //   ->selectRaw('CONCAT("' . "<label class='switch'><input type='checkbox' id='checkbox1'><span class='slider round'></span></label>" . '") AS `pmostaff`')
        //   ->get();
    }

    public function getFeatureItemParameterByFeatureItem(){
        // return DB::table('roles')->select('group')->groupBy('group')->pluck('group')->toArray();
        // return gettype(DB::table('roles')->select('group')->groupBy('group')->pluck('group')->toArray());
        $data = DB::table('feature_item')->select('group')->groupBy('group')->pluck('group')->toArray();
        array_unshift($data, "", "All");
        return $data;
    }

    public function getFeatureItemParameterByRoleGroup(){
        // return DB::table('roles')->select('group')->groupBy('group')->pluck('group')->toArray();
        // return gettype(DB::table('roles')->select('group')->groupBy('group')->pluck('group')->toArray());

        // dd(DB::table('role_user')->where('user_id',Auth::User()->nik)->first());
        // return DB::table('roles')->where('id',DB::table('role_user')->where('user_id',Auth::User()->nik)->first()->role_id)->pluck('group')->toArray();

        // $checkForAllFeatureItem = DB::table('roles_feature_item')
        //  ->where('roles_id',DB::table('role_user')->where('user_id',Auth::User()->nik)->first()->role_id)
        //  ->where('feature_item_id',124)
        //  ->first();


        // For Production
        $checkForAllFeatureItem = [7,33,17,39];

        // For Development
        // $checkForAllFeatureItem = [7,33,17,28];

        if(in_array(DB::table('role_user')->where('user_id',Auth::User()->nik)->first()->role_id,$checkForAllFeatureItem)){
            $data = DB::table('roles')->where('group','<>','default')->select('group')->groupBy('group')->pluck('group')->toArray();
            // array_unshift($data, "All");
        } else {
            $data = DB::table('roles')->where('id',DB::table('role_user')->where('user_id',Auth::User()->nik)->first()->role_id)->pluck('group')->toArray();
            // array_unshift($data, "");
        }

        return $data;
    }
}
