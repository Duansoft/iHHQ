<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $me = Auth::user();
        $country = Country::findOrFail($me->country_id);

        return View('pages.setting', compact('me', 'country'));
    }

    public function postProfile(Request $request)
    {
        $data = $request->except('mobile');
        $me = Auth::user();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'passport_no' => 'required|max:50',
            'country_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        $me->name = $data['name'];
        $me->address = $data['address'];

        // Update Profile Photo
        if (Input::hasFile('photo')) {
            $photo = Input::file('photo');
            $fileName = $me->id . str_random(1) . '.' . $photo->extension();
            $img_dir = public_path() . '/upload/avatars/' . $fileName;
            Image::make($photo)->encode('png')->resize(150, 150)->save($img_dir);

            $me->photo = $fileName;
        }

        // Update Password
        if (Input::has('password')) {
            $validator = Validator::make($data, [
                'password' => 'required|min:6|confirmed',
                'current_password' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->messages())->withInput();
            }

            $password = Input::get('password');
            $confirm = Input::get('password_confirmation');

            if (!Hash::check(Input::get('current_password'), $me->password)) {
                return redirect()->back()->withErrors(['The password does not match']);
            } else if ($password != $confirm) {
                return redirect()->back()->withErrors(['The password confirmation does not match']);
            }

            $me->password = bcrypt($password);
        }

        $me->save();

        return redirect()->back()->with('flash_message', 'Your Profile have been updated successfully');
    }

    public function postNotificationSetting()
    {
        $me = Auth::user();
        $me->is_enable_push = Input::get('is_enable_push') == "on" ? true : false;
        $me->is_enable_email = Input::get('is_enable_email') == "on" ? true : false;
        $me->save();

        return redirect()->back();
    }
}
