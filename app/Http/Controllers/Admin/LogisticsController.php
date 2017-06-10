<?php

namespace App\Http\Controllers\Admin;

use App\Courier;
use App\Dispatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;

class LogisticsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return View('admin.pages.logistics');
    }

    public function getDispatches()
    {
        $dispatches = DB::table('dispatches')
            ->select('couriers.logo', 'couriers.name AS courier', 'delivery_by', 'dispatches.file_ref', 'users.name', 'description', 'dispatches.updated_at', 'dispatches.status', 'dispatch_id')
            ->leftJoin('files', 'files.file_ref', 'dispatches.file_ref')
            ->leftJoin('couriers', 'couriers.courier_id', 'dispatches.courier_id')
            ->leftJoin('users', 'users.id', 'dispatches.client_id')
            ->where('files.status', 0);

        return Datatables::of($dispatches)
            ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->toFormattedDateString() !!}')
            ->addColumn('action', function ($dispatch) {
                $url = url("admin/logistics/" . $dispatch->dispatch_id);
                return '<div class="btn-group btn-group-fade">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="'.$url.'">Edit</a>
                                </li>
                            </ul>
                        </div>';
            })
            ->make(true);
    }

    public function getCreate()
    {
        $couriers = Courier::all();
        $code = 'iHHQ' . str_random(64) . Carbon::now()->timestamp;

        return View('admin.pages.addEditDispatch', compact('couriers', 'code'));
    }

    public function postCreate(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'client_id' => 'required',
            'description' => 'required',
            'courier_id' => 'required',
            'qr_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $dispatch = new Dispatch();
        $dispatch->fill($data);
        $dispatch->created_by = Auth::user()->id;
        $dispatch->save();

        return redirect('admin/logistics')->with('flash_message', 'Dispatch has been created');
    }

    public function getDispatch($id)
    {
        $dispatch = Dispatch::findOrFail($id);
        $user = User::findOrFail($dispatch->client_id);
        $val = $user->name . " (" . $user->passport_no . ")";
        $couriers = Courier::all();

        return View('admin.pages.addEditDispatch', compact('couriers', 'dispatch', 'val'));
    }

    public function postDispatch($id)
    {
        $data = Input::all();

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'client_id' => 'required',
            'description' => 'required',
            'courier_id' => 'required',
            'qr_code' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $dispatch = Dispatch::findOrFail($id);
        $dispatch->fill($data);
        $dispatch->updated_by = Auth::user()->id;
        $dispatch->save();

        return redirect('admin/logistics')->with('flash_message', 'Dispatch has been updated successfully');
    }

    public function deleteDispatch($id)
    {
        $dispatch = Dispatch::findOrFail($id);
        $dispatch->delete();

        return redirect('admin/logistics')->with('flash_message', 'The dispatch have been deleted successfully');
    }

}
