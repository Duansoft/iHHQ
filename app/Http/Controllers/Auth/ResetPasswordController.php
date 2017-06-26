<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/overview';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display password reset view
     */
    public function getReset()
    {
        return view('auth.passwords.reset');
    }

    public function postReset()
    {
        if (Auth::user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } else if (Auth::user()->hasRole('client')) {
            return redirect('/overview');
        } else {
            return redirect('/admin/overview');
        }
        //return redirect()->back();
    }
}
