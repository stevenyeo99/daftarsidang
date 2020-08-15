<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\ProdiUser;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class ProdiLoginController extends Controller {

    use RedirectsUsers;

    /**
     * Where to redirect prodi after login
     */
    protected $redirectTo = '/prodi/request/kp';

    /**
     * Create a new controller instance
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('guest:prodis')->except('logout');
    }

    /**
     * show the application login form of prodi
     */
    public function showLoginForm() {
        return view('auth.prodi-login');
    }

    /**
     * when prodi user try to login
     */
    public function login(Request $request) {
        // validate the form data first
        $this->validate($request, [
            'email' => 'required|email|max:50',
            'password' => 'required',
        ]);

        if(Auth::guard('prodis')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            // if successful, then redirect to their intented location
            if(Auth::guard('prodis')->user()->is_admin == 1) {
                return redirect()->intended(route('prodi.request.kp'));
            } else {
                return redirect()->intended(route('prodi.berita.acara.kp'));
            }            
        }
        
        $this->sendFailedLoginResponse($request);
    }

    /**
     * when prodi user log out
     */
    public function logout(Request $request) {
        Auth::guard('prodis')->logout();

        $request->session()->invalidate();

        return redirect('prodi/login');
    }

    /**
     * send failed login response
     */
    protected function sendFailedLoginResponse(Request $request) {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}