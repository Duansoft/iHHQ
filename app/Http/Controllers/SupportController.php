<?php

namespace App\Http\Controllers;


use App\Department;
use App\File;
use App\Ticket;
use App\Ticket_Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $myID = Auth::id();
        $departments = Department::get();
        $files = File::whereHas('participants', function ($query) use($myID) {
                $query->where('user_id', '=', $myID);
            })
            ->where('status', 0)
            ->get();
        $tickets = Ticket::where('client_id', $myID)
            ->where('status_id', '!=', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $messages = [];
        if (sizeof($tickets) > 0) {
            $ticket = $tickets[0];
            $messages = Ticket_Message::where('ticket_id', $ticket->ticket_id)
                ->select('ticket_messages.*', 'users.name', 'users.photo')
                ->join('users', 'users.id', 'ticket_messages.sender_id')
                ->orderBy('ticket_messages.created_at')
                ->get();
        }

        return View('pages.support', compact('departments', 'files', 'tickets', 'messages', 'ticket'));
    }

    public function createTicket()
    {
        $user = Auth::user();
        $data = Input::all();

        $validator = Validator::make($data, [
            'department_id' => 'required|max:255',
            'subject' => 'required|max:500',
            'message' => 'required|max:4048',
            'file_ref' => 'max:50'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        $ticket = new Ticket();
        $ticket = $ticket->fill($data);
        $ticket->client_id = $user->id;
        $ticket->save();

        $message = new Ticket_Message();
        $message->fill($data);
        $message->ticket_id = $ticket->ticket_id;
        $message->client_id = $user->id;
        $message->sender_id = $user->id;
        $message->save();

        return redirect()->back();
    }
}