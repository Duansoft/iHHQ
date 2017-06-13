<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = DB::table('users')
                ->select('users.id', 'roles.display_name', 'users.name', 'email', 'passport_no', 'is_allow')
                ->join('role_user', 'role_user.user_id', 'users.id')
                ->join('roles', 'roles.id', 'role_user.role_id');

            return Datatables::of($users)
                ->addColumn('action', function ($user) {
                    return '<div class="btn-group btn-group-fade">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="users/' . $user->id . '">Edit</a>
                                    </li>
                                </ul>
                            </div>';
                })
                ->make(true);
        }

        $countries = Country::all();
        return View('admin.pages.users', compact('countries'));
    }

    /**
     * Search client using ajax
     */
    public function findUserAjax(Request $request)
    {
        $results = $this->searchUsers($request, false);
        return response()->json($results);

        return response()->json($results);
    }

    /**
     * Search HHQ staff using ajax
     */
    public function findHHQStaffsAjax(Request $request)
    {
        $results = $this->searchUsers($request, true);
        return response()->json($results);
    }

    /**
     * Search users using ajax
     */
    protected function searchUsers(Request $request, $isHHQStaff)
    {
        $page = Input::get('page');
        $resultCount = 50;
        $offset = ($page - 1) * $resultCount;

        $data = [];

        if (!$request->has('q')) {
            $results = array(
                "results" => $data,
                "total_count" => 0,
                "pagination" => array(
                    "more" => false
                )
            );

            return response()->json($results);
        }

        if ($isHHQStaff) {
            $operator = "!=";
        } else {
            $operator = "=";
        }

        $search = $request->q;
        $data = DB::table("users")
            ->select('users.id', 'users.name', 'passport_no')
            ->leftJoin('role_user', 'role_user.user_id', 'users.id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.name', $operator, 'client')
            ->where('users.name', 'LIKE', "%$search%")
            ->orderBy('users.name')
            ->skip($offset)
            ->take($resultCount)
            ->get();

        $count = DB::table("users")
            ->select('users.id', 'users.name', 'passport_no')
            ->leftJoin('role_user', 'role_user.user_id', 'users.id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.name', $operator, 'client')
            ->where('users.name', 'LIKE', "%$search%")
            ->count();

        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = [
            "total_count" => $count,
            "pagination" => ["more" => $morePages],
            "results" => $data,
        ];

        return $results;
    }

    public function getClientAjax()
    {
        $user_id = Input::get('id');
        $countries = Country::all();

        if (!empty($user_id)) {
            $user = User::findOrFail($user_id);
            return View('admin.pages.addEditUser', compact($user, $countries));
        }

        return View('admin.pages.addEditUser', compact('countries'));
    }

    /**
     * Add, Edit User Functions
     */
    public function getCreateAdmin()
    {
        $countries = Country::all();
        $role = Role::where('name', 'admin')->first();

        return View('admin.pages.addEditUser', compact('countries', 'role'));
    }

    public function getCreateLawyer()
    {
        $countries = Country::all();
        $role = Role::where('name', 'lawyer')->first();

        return View('admin.pages.addEditUser', compact('countries', 'role'));
    }

    public function getCreateStaff()
    {
        $countries = Country::all();
        $role = Role::where('name', 'staff')->first();

        return View('admin.pages.addEditUser', compact('countries', 'role'));
    }

    public function getCreateClient()
    {
        $countries = Country::all();
        $role = Role::where('name', 'client')->first();

        return View('admin.pages.addEditUser', compact('countries', 'role'));
    }

    public function postUser(Request $request)
    {
        $data = $request->all();

        if ($request->get('id')) {
            $role =  [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'mobile' => 'required',
                'passport_no' => 'required|max:50',
                'country_id' => 'required|numeric'
            ];
        } else {
            $role =  [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
                'mobile' => 'required|unique:users',
                'passport_no' => 'required|max:50|unique:users',
                'country_id' => 'required|numeric'
            ];
        }

        $validator = Validator::make($data, $role);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        if ($request->get('id')) {
            $user = User::findOrFail($request->get('id'));
            $user->fill($data);
            $user->is_allow = Input::get('is_allow') == "on" ? true : false;
            $user->save();

            return redirect('admin/users')->with('flash_message', 'The user have been updated successfully');
        } else {
            $user = new User();
            $user->fill($data);
            $user->verified = true;
            $user->is_allow = Input::get('is_allow') == "on" ? true : false;
            $user->password = bcrypt($data['password']);
            $user->save();

            $role = Role::where('name', $request->get('role'))->first();
            $user->attachRole($role);
            return redirect('admin/users')->with('flash_message', 'The user have been created successfully');
        }
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        $countries = Country::all();
        $role = $user->roles()->first();

        return View('admin.pages.addEditUser', compact('user', 'countries', 'role'));
    }

    /**
     * User Detail
     */
    public function getUserDetail($id)
    {
        if (!empty($id)) {
            $user = User::findOrFail($id);
            return View('admin.pages.addEditUser', compact($user));
        }

        return View('admin.pages.addEditUser');
    }
}
