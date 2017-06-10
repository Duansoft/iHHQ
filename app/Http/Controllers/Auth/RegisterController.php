<?php

namespace App\Http\Controllers\Auth;

use App\Temp_User;
use App\User;
use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verification/';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'mobile' => 'required|unique:users',
            'passport_no' => 'required|max:50|unique:users',
            'country_id' => 'required|numeric'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'passport_no' => $data['passport_no'],
            'mobile' => $data['mobile'],
            'country_id' => $data['country_id'],
        ]);

        $clientRole = Role::where('name', 'client')->first();
        $user->attachRole($clientRole);

        return $user;
    }

    protected function getRegister() {
        $countries = Country::get();
        return view('auth.register', compact('countries'));
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $token = str_random(32);
        $randomNumber = rand(100000, 999999);
        //$randomNumber = 555555;


        DB::beginTransaction();
        try {
            $data = $request->all();

            $user = Temp_User::where('mobile', $data['mobile'])
                ->where('country_id', $data['country_id'])
                ->first();
            if (!empty($user)) {
                $user->delete();
            }

            Temp_User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'passport_no' => $data['passport_no'],
                'country_id' => $data['country_id'],
                'mobile' => $data['mobile'],
                'code' => $randomNumber,
                'token' => $token,
                'attempt' => 0,
            ]);

            $country = Country::findOrFail($request->input('country_id'));
            $phoneNumber = $country->phone_code . $request->input('mobile');
            $message = "Verification Code for iHHQ is " . $randomNumber;

            $this->sendSMS($phoneNumber, $message);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['msg' => 'Failed to send verification SMS to your mobile']);
        }

        return redirect($this->redirectPath() . $token);
    }
}
