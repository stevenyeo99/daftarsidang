<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\FinanceUser;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class FinanceLoginController extends Controller {
    
    use RedirectsUsers;

    protected $redirectTo = '/finance/request/skripsi';

    /**
     * Create a new controller instance
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('guest:finance')->except('logout');
    }

    /**
     * show the application login form of finance
     */
    public function showLoginForm() {
        return view('auth.finance-login');
    }

    /**
     * when finance user try to login
     */
    public function login(Request $request) {
        // validate the form data first
        $this->validate($request, [
            'email' => 'required|email|max:50',
            'password' => 'required',
        ]);

        if(Auth::guard('finance')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            // if successful, then redirect to their intented location       ;     
            return redirect()->intended(route('finance.request.skripsi'));                        
        }
        
        $this->sendFailedLoginResponse($request);
    }

    /**
     * when finance user log out
     */
    public function logout(Request $request) {
        Auth::guard('finance')->logout();

        $request->session()->invalidate();

        return redirect('finance/login');
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