<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class LOGINController extends Controller
{
    public function showLoginForm()
    {
    	return view('login.login');
    }

    public function attempt(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|exists:users,email',
            'password' => 'required',
        ]);

        $attempts = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($attempts, (bool) $request->remember)) {
            return redirect()->intended('/');
        }

        return redirect()->back();
    }
}
