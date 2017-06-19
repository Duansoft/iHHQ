<?php

namespace App\Http\Controllers\Auth;

use App\Temp_User;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Country;

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

        $diff = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $tempUser->updated_at)->diffInMinutes(Carbon::now());
        if ($diff > 10) {
            return redirect()->back()->withErrors(['The code is not available now']);
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

        return redirect('login')->withErrors('Thanks. We will contact soon.');
        //Auth::login($user);
        //return redirect($this->redirectPath);
    }

    public function resendSMS($token)
    {
        $user = Temp_User::where('token', $token)->first();
        if (empty($user)) {
            abort(403);
        }

        if ($user->attempt > 5) {
            $diff = \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $user->updated_at)->diffInDays(Carbon::now());
            if ($diff < 1) {
                return back()->withErrors(['msg' => 'You have tried more than 5 times today. Try later']);
            }

            $user->attempt = 0;
        }

        $token = str_random(32);
        $randomNumber = rand(100000, 999999);

        DB::beginTransaction();
        try {
            $country = Country::findOrFail($user->country_id);
            $phoneNumber = $country->phone_code . $user->mobile;
            $message = "Verification Code for iHHQ is " . $randomNumber;

            $user->code = $randomNumber;
            $user->token = $token;
            $user->attempt = $user->attempt + 1;
            $user->save();

            $this->sendSMS($phoneNumber, $message);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['msg' => 'Failed to send verification SMS to your mobile']);
        }

        return redirect('verification/' . $token)->with('flash_message', 'Have sent SMS with verification code');
    }
}
