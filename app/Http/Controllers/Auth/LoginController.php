<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


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
     * Where to redirect users after logout
     */
    protected $redirectAfterLogout  = '/login';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return '/admin/dashboard';
        } else if ($user->hasRole('staff') || $user->hasRole('lawyer') || $user->hasRole('logistics') || $user->hasRole('billing')) {
            return '/admin/overview';
        } else if ($user->hasRole('client')) {
            return '/overview';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function postLogin(Request $request)
    {
        $email = Input::get('email');
        $password = Input::get('password');
        $remember = Input::get('remember');
        $recaptcha_response = Input::get('g-recaptcha-response');

        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            //'g-recaptcha-response' => $recaptcha_response,
        ], [
            'email' => 'required | email',
            'password' => 'required',
            //'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (!Auth::attempt(['email' => $email, 'password' => $password, 'verified' => true], $remember)) {
            $this->incrementLoginAttempts($request);
            return redirect()->back()->withErrors('Email or Password is invalid');
        }

        $user = Auth::user();
        if (!$user->is_allow) {
            Auth::logout();
            return redirect()->back()->withErrors('Your login Permission is not allowed');
        }

        return redirect()->intended($this->redirectTo());
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'These credentials do not match our records.';
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/login');
    }
}
