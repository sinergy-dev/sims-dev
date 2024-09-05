<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\HistoryAuth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Auth;
use Socialite;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Log;
use Illuminate\Foundation\Auth\ThrottlesLogins;

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

    use ThrottlesLogins;

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
        syslog(LOG_ERR, $request->getClientIp());
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
        
        if ($request->password == 'sinergy') {
            Auth::User()->is_default_password = 'true';
        }else{
            Auth::User()->is_default_password = 'false';
        }
        syslog(LOG_ERR, Auth::User()->is_default_password);

        syslog(LOG_ERR, $request->getClientIp());
        $log = new HistoryAuth;
        $log->nik = $user->nik;
        $log->information = "Log In";
        $log->datetime = Carbon::now()->toDateTimeString();
        $log->ip_address = $request->getClientIp();
        $log->save();
    }

    public function redirectToProvider() {
        try {
            $user = Socialite::driver('google')->redirect();
            return $user;
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()
                ->to('/login')
                ->withErrors([
                    'email_company' => ['Google services are currently unreachable, please use your local account or try again later.'],
                    'email_google_eror' => ['Google services are currently unreachable, please use your local account or try again later.']
                ]);;
        } 
    }

    public function handleProviderCallback() {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()
                ->to('/login')
                ->withErrors([
                    'email_company' => ['Google services are currently unreachable, please use your local account or try again later.'],
                    'email_google_eror' => ['Google services are currently unreachable, please use your local account or try again later.']
                ]);
        }    
        
        if(explode("@", $user->email)[1] !== env('COMPANY_EMAIL_PREFIX')){
            return redirect()
            ->to('/login')
            ->withErrors([
                'email_company' => ['You must enter your ' . env('COMPANY_EMAIL_PREFIX') . ' email to be able to login with your Google Account']
            ]);
        }

        // dd($user);

        $existingUser = User::where('email', $user->email)->first();

        if($existingUser){

            if($existingUser->status_karyawan == "dummy" && $existingUser->status_delete == "D"){
                return redirect()
                    ->to('/login')
                    ->withErrors([
                        'email_company' => ['Sorry, your account has been deactivated. Please confirm with HR to reactivate.']
                    ]);
            } else {
                $existingUser->google_id = $user->id;
                $existingUser->avatar = $user->avatar;
                $existingUser->avatar_original = $user->avatar_original;

                $existingUser->save();

                auth()->login($existingUser, true);
            }
        } else {
            return redirect()
                ->to('/login')
                ->withErrors([
                    'email_company' => ['Sorry, your email address "' . $user->email . '" was not found in our database, please check your email again.']
                ]);
        }
        return redirect()->to('/');
    }

    // protected function maxAttempts()
    // {
    //     return 1; 
    // }

    // protected function decayMinutes()
    // {
    //     return 1; 
    // }

    // protected function sendLockoutResponse(Request $request)
    // {
    //     $seconds = $this->limiter()->availableIn(
    //         $this->throttleKey($request)
    //     );

    //     return redirect()->back()
    //         ->withInput($request->only($this->username(), 'remember'))
    //         ->withErrors([
    //             $this->username() => trans('auth.throttle', ['seconds' => $seconds]),
    //             'remaining_time' => $seconds,
    //         ]);
    // }
}
