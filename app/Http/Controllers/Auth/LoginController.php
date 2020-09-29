<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\HistoryAuth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    use AuthenticatesUsers{
        logout as performeLogout;
    }

    public function logout(Request $request){
        
        $log = new HistoryAuth;
        $log->nik = $request->nik;
        $log->information = "Log Out";
        $log->datetime = Carbon::now()->toDateTimeString();
        $log->ip_address = $request->getClientIp();
        $log->save();
        $this->performeLogout($request);

        return redirect()->route('login');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user)
    {
        syslog(LOG_ERR, $request->getClientIp());
        $log = new HistoryAuth;
        $log->nik = $user->nik;
        $log->information = "Log In";
        $log->datetime = Carbon::now()->toDateTimeString();
        $log->ip_address = $request->getClientIp();
        $log->save();
    }


}
