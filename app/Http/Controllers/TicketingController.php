<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketingController extends Controller
{
	public function index(){
		return view('ticketing.ticketing')->with(['initView'=>$this->initMenuBase(),'sidebar_collapse' => 'True']);
	}
}
