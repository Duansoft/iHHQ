<?php

namespace App\Http\Controllers\Admin;

use App\Dispatch;
use App\Payment;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $users = User::where('is_review', false)
            ->count();
        $payments = Payment::where('status', 2)
            ->whereNull('receipt')
            ->count();
        $dispatches = Dispatch::where('status', '!=', 0)
            ->count();
        $tickets = Ticket::where('status_id', 1)
            ->where('file_ref', '')
            ->count();


        $usersData = DB::table('users')
            ->select('*', DB::raw('"user" AS dashboard_title'))
            ->where('is_review', false)
            ->get();
        $paymentsData = DB::table('payments')
            ->select('*', DB::raw('"payment" AS dashboard_title'))
            ->join('users', 'users.id', 'payments.paid_by')
            ->join('files', 'payments.file_ref', 'files.file_ref')
            ->where('payments.status', 2)
            ->get();
        $dispatchesData = DB::table('dispatches')
            ->select('*', DB::raw('"dispatch" AS dashboard_title'))
            ->join('users', 'users.id', 'dispatches.client_id')
            ->where('status', '!=', 0)
            ->get();
        $ticketsData = DB::table('tickets')
            ->select('*', 'ticket_categories.name AS category',DB::raw('"ticket" AS dashboard_title'))
            ->join('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->join('users', 'users.id', 'tickets.client_id')
            ->where('file_ref', '')
            ->where('status_id', 1)
            ->get();

        $logs = $usersData->merge($paymentsData)
            ->merge($dispatchesData)
            ->merge($ticketsData)
            ->sortBy('updated_at');

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets', 'users', 'logs'));
    }

    public function getDispatches()
    {
        $users = User::where('is_review', false)
            ->count();
        $payments = Payment::where('status', 2)
            ->whereNull('receipt')
            ->count();
        $dispatches = Dispatch::where('status', '!=', 0)
            ->count();
        $tickets = Ticket::where('status_id', 1)
            ->where('file_ref', '')
            ->count();

        $dispatchesData = DB::table('dispatches')
            ->select('*', DB::raw('"dispatch" AS dashboard_title'))
            ->join('users', 'users.id', 'dispatches.client_id')
            ->where('status', '!=', 0)
            ->get();

        $logs = $dispatchesData->sortBy('updated_at');

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets', 'users', 'logs'));
    }

    public function getPayments()
    {
        $users = User::where('is_review', false)
            ->count();
        $payments = Payment::where('status', 2)
            ->whereNull('receipt')
            ->count();
        $dispatches = Dispatch::where('status', '!=', 0)
            ->count();
        $tickets = Ticket::where('status_id', 1)
            ->where('file_ref', '')
            ->count();

        $paymentsData = DB::table('payments')
            ->select('*', DB::raw('"payment" AS dashboard_title'))
            ->join('users', 'users.id', 'payments.paid_by')
            ->join('files', 'payments.file_ref', 'files.file_ref')
            ->where('payments.status', 2)
            ->get();

        $logs = $paymentsData->sortBy('updated_at');

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets', 'users', 'logs'));
    }

    public function getUsers()
    {
        $users = User::where('is_review', false)
            ->count();
        $payments = Payment::where('status', 2)
            ->whereNull('receipt')
            ->count();
        $dispatches = Dispatch::where('status', '!=', 0)
            ->count();
        $tickets = Ticket::where('status_id', 1)
            ->where('file_ref', '')
            ->count();

        $usersData = DB::table('users')
            ->select('*', DB::raw('"user" AS dashboard_title'))
            ->where('is_review', false)
            ->get();

        $logs = $usersData->sortBy('updated_at');

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets', 'users', 'logs'));
    }

    public function getTickets()
    {
        $users = User::where('is_review', false)
            ->count();
        $payments = Payment::where('status', 2)
            ->whereNull('receipt')
            ->count();
        $dispatches = Dispatch::where('status', '!=', 0)
            ->count();
        $tickets = Ticket::where('status_id', 1)
            ->where('file_ref', '')
            ->count();

        $ticketsData = DB::table('tickets')
            ->select('*', 'ticket_categories.name AS category',DB::raw('"ticket" AS dashboard_title'))
            ->join('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->join('users', 'users.id', 'tickets.client_id')
            ->where('file_ref', '')
            ->where('status_id', 1)
            ->get();

        $logs = $ticketsData->sortBy('updated_at');

        return View('admin.pages.dashboard', compact('payments', 'dispatches', 'users', 'tickets', 'users', 'logs'));
    }

}
