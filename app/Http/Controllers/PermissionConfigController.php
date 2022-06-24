<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionConfigController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testPermissionConfig(){ 
        return view('testPermissionConfig')->with(['initView'=> $this->initMenuBase(),'feature_item'=>$this->RoleDynamic('permission_config')]);
    }
}
