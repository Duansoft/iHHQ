<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $me = Auth::user();
        $countries = Country::all();
        return View('admin.pages.setting', compact('me', 'countries'));
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

        $me->fill($data);
        $me->save();

        if (Input::hasFile('photo')) {
            $photo = Input::file('photo');
            $fileName = $me->id . str_random(2) . '.' . $photo->extension();
            $img_dir = public_path() . '/upload/avatars/' . $fileName;
            Image::make($photo)->encode('png')->resize(150, 150)->save($img_dir);

            $me->photo = $fileName;
            $me->save();
        }

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
