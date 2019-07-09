<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Redirect;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function username()
    {
        return 'userprincipalname';
    }


    public function showLoginForm()
    {
       return View::make("auth/login");
    }

    protected function login(Request $request)
    {
      $userprincipalname = $request->userprincipalname;
      $password = $request->password;
      $result = Adldap::search()->users()->find($userprincipalname);
      $user_format = env('LDAP_USER_FORMAT', 'cn=%s,'.env('LDAP_BASE_DN', ''));
      $userdn = sprintf($user_format, $userprincipalname);
      $connection = Adldap::auth()->attempt($userdn, $password, $bindAsUser = true);
      
     if($connection) {
	 $user = new \App\User();
         $user->name = $userprincipalname;
         $user->username = $userprincipalname;
         $user->password = '';
	 $this->guard('auth')->login($user, true);
	 return redirect('/home');
 
      }
     else{
      return View::make("auth/login");
      }
    }

    public function logout(Request $request)
   {
     
       $this->guard($request)->logout();
      
       $request->session()->invalidate();

       return redirect('/login');
   }
      
}
