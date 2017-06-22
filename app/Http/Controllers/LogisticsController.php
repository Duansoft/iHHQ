<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class LogisticsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return View('pages.logistics');
    }

    public function getDispatches()
    {
        $id = Auth::id();

        $dispatches = DB::table('dispatches')
            ->select('couriers.logo', 'couriers.name AS courier', 'dispatches.file_ref', 'users.name', 'description', 'dispatches.updated_at', 'dispatches.status')
            ->leftJoin('files', 'files.file_ref', 'dispatches.file_ref')
            ->leftJoin('couriers', 'couriers.courier_id', 'dispatches.courier_id')
            ->leftJoin('users', 'users.id', 'dispatches.client_id')
            ->where('dispatches.client_id', $id);

        return Datatables::of($dispatches)
            ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->toFormattedDateString() !!}')
            ->make(true);
    }
}
