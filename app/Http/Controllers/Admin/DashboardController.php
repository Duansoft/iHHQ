<?php

namespace App\Http\Controllers\Admin;

use App\Dispatch;
use App\Payment;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $payments = Payment::where('status', 2)->count();
        $dispatches = Dispatch::where('status', 2)->count();
        $users = User::where('is_allow', 1)->count();
        $tickets = Ticket::where('status_id', 1)->count();

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets'));
    }
}
