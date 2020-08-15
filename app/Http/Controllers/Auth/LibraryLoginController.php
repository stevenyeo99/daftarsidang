<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\LibraryUser;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class LibraryLoginController extends Controller {

    use RedirectsUsers;

    protected $redirectTo = '/library/hardcover_kp';

    public function __costruct() {
        $this->middleware('guest:lib')->except('logout');
    }

    public function showLoginForm() {
        return view('auth.library-login');
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|max:50',
            'password' => 'required',
        ]);

        if(Auth::guard('lib')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('hardcover_kp'));
        }

        $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request) {
        Auth::guard('lib')->logout();
        $request->session()->invalidate();
        return redirect('library/login');
    }

    protected function sendFailedLoginResponse(Request $request) {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}