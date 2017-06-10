<?php

namespace App\Http\Controllers\Auth;

use App\Temp_User;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Role;

class VerificationController extends Controller
{
    protected $redirectPath = '/overview';

    public function getVerification($token)
    {
        $user = Temp_User::where('token', $token)->first();
        if (empty($user)) {
            abort(404);
        }

        return view('auth.verification', compact('token'));
    }

    public function postVerification($token)
    {
        $verification_code = Input::get('code');

        $tempUser = Temp_User::where('token', $token)->first();
        if (empty($tempUser)) {
            abort(404);
        }

        if ($tempUser->code != $verification_code) {
            return redirect()->back()->withErrors(['Verification code is not match']);
        }

        $user = User::create([
            'name' => $tempUser->name,
            'email' => $tempUser->email,
            'password' => $tempUser->password,
            'passport_no' => $tempUser->passport_no,
            'mobile' => $tempUser->mobile,
            'country_id' => $tempUser->country_id,
            'verified' => 1,
        ]);

        $clientRole = Role::where('name', 'client')->first();
        $user->attachRole($clientRole);
        $user->save();

        $tempUser->delete();

        Auth::login($user);

        return redirect($this->redirectPath);
    }
}
