<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/request/kp';

    /**
     * Where to redirect meteor users after login.
     *
     * @var string
     */
    protected $redirectMeteorTo = 'admin/meteor/skripsi/student';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

    /**
     * Create a custom logout.
     *
     * @return void
     */
    }
    public function logout(Request $request)
    {
        $this->performLogout($request);

        $request->session()->invalidate();

        return redirect()->route('login');
    }
}
