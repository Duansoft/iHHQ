<?php

namespace App\Http\Controllers\Admin;

use App\Ticket;
use App\Http\Controllers\Controller;
use App\Ticket_Category;
use App\Ticket_Message;
use App\Ticket_Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Department;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $activeTickets = Ticket::where('status_id', 2)->count();
            $pendingTickets = Ticket::where('status_id', 1)->count();
            $completedTickets = Ticket::where('status_id', 0)->count();

            return View('admin.pages.tickets', compact('activeTickets', 'completedTickets', 'pendingTickets'));

        } else {
            $myID = Auth::id();
            $tickets = DB::table('tickets')
                ->select('tickets.*', 'client.photo as photo', 'client.name as name', 'ticket_categories.name as category')
                ->leftJoin('file_users', 'tickets.file_ref', 'file_users.file_ref')
                ->leftJoin('users AS client', 'tickets.client_id', 'client.id')
                ->leftJoin('ticket_categories', 'tickets.category_id', 'ticket_categories.category_id')
                ->where('file_users.user_id', $myID)
                ->orWhere('staff_id', $myID)
                ->orderBy('updated_at', 'desc')
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

            return View('admin.pages.support', compact('tickets', 'messages', 'ticket'));
        }
    }

    public function getActiveTicketsAjax()
    {
        $tickets = DB::table('tickets')
            ->select('ticket_id', 'file_ref', 'subject', 'ticket_statuses.name AS status', 'tickets.updated_at', 'owners.name AS owner', 'agents.name AS agent', 'ticket_categories.name AS category')
            ->leftJoin('users AS owners', 'owners.id', 'tickets.client_id')
            ->leftJoin('users AS agents', 'agents.id', 'tickets.staff_id')
            ->leftJoin('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->leftJoin('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
            ->where('status_id', 2);

        return Datatables::of($tickets)
            ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getCompletedTickets()
    {
        $isCompletedTickets = true;
        $activeTickets = Ticket::where('status_id', 2)->count();
        $pendingTickets = Ticket::where('status_id', 1)->count();
        $completedTickets = Ticket::where('status_id', 0)->count();

        return View('admin.pages.tickets', compact('activeTickets', 'completedTickets', 'isCompletedTickets', 'pendingTickets'));
    }

    public function getCompletedTicketsAjax()
    {
        $tickets = DB::table('tickets')
            ->select('ticket_id', 'file_ref', 'subject', 'ticket_statuses.name AS status', 'tickets.updated_at', 'owners.name AS owner', 'agents.name AS agent', 'ticket_categories.name AS category')
            ->leftJoin('users AS owners', 'owners.id', 'tickets.client_id')
            ->leftJoin('users AS agents', 'agents.id', 'tickets.staff_id')
            ->leftJoin('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->leftJoin('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
            ->where('status_id', 0);

        return Datatables::of($tickets)
            ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getPendingTickets()
    {
        $isPendingTickets = true;
        $activeTickets = Ticket::where('status_id', 2)->count();
        $pendingTickets = Ticket::where('status_id', 1)->count();
        $completedTickets = Ticket::where('status_id', 0)->count();

        return View('admin.pages.tickets', compact('activeTickets', 'completedTickets', 'isPendingTickets', 'pendingTickets'));
    }

    public function getPendingTicketsAjax()
    {
        $tickets = DB::table('tickets')
            ->select('ticket_id', 'file_ref', 'subject', 'ticket_statuses.name AS status', 'tickets.updated_at', 'owners.name AS owner', 'agents.name AS agent', 'ticket_categories.name AS category')
            ->leftJoin('users AS owners', 'owners.id', 'tickets.client_id')
            ->leftJoin('users AS agents', 'agents.id', 'tickets.staff_id')
            ->leftJoin('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->leftJoin('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
            ->where('status_id', 1);

        return Datatables::of($tickets)
            ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getCreateTicket()
    {
        $user = Auth::user();
        $files = File::where('created_by', $user->id)->where('status', '0')->get();
        $ticket_categories = Ticket_Category::all();

        $activeTickets = Ticket::where('status_id', 2)->count();
        $pendingTickets = Ticket::where('status_id', 1)->count();
        $completedTickets = Ticket::where('status_id', 0)->count();

        return View('admin.pages.addTicket', compact('ticket_categories', 'files', 'activeTickets', 'completedTickets', 'pendingTickets'));
    }

    public function postCreateTicket(Request $request)
    {
        $data = $request::all();

        $validator = Validator::make($data, [
            'category_id' => 'required',
            'subject' => 'required|max:2000',
            'client_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        $ticket = new Ticket();
        $ticket->client_id = Input::get('client_id');
        $ticket->subject = Input::get('subject');
        if (Input::has('file_ref')) {
            $ticket->file_ref = Input::get('file_ref');
            $ticket->status_id = 2;
        } else {
            $ticket->status_id = 1;
        }
        $ticket->save();

        // Create Ticket message
        $message = new Ticket_Message();
        $message->ticket_id = $ticket->ticket_id;
        $message->client_id = $data['client_id'];
        $message->sender_id = Auth::id();

        $content = [];
        if (Input::hasFile('attachments')) {
            $attachments = Input::file('attachments');
            $files = [];

            foreach ($attachments as $attachment) {
                $fileName = $attachment->getClientOriginalName();// . $attachment->getClientOriginalExtension();
                $directory = 'tickets/'.$ticket->ticket_id;
                $path = $attachment->storeAs($directory, Carbon::now()->toDateString() . '-' . str_random(5) . '-' . $fileName);
                $size = $this->formatBytes($attachment->getClientSize());

                $files[] = ['name' => $fileName, 'size' => $size, 'path' => $path];
            }
            $content['attachments'] = $files;
        }
        $content['text'] =  Input::get('message');

        $message->message = json_encode($content);
        $message->save();

        return redirect('admin/tickets/pending')->with('flash_message', 'Ticket have been created successfully');
    }

    public function getTicket($id)
    {
        $user = Auth::user();

        if (Auth::user()->hasRole('admin')) {
            $files = File::where('created_by', $user->id)->where('status', '0')->get();
            $ticket_categories = Ticket_Category::all();

            $activeTickets = Ticket::where('status_id', 2)->count();
            $pendingTickets = Ticket::where('status_id', 1)->count();
            $completedTickets = Ticket::where('status_id', 0)->count();

            $ticket = DB::table('tickets')
                ->select('tickets.*', 'ticket_statuses.name AS status', 'owners.name AS owner', 'agents.name AS agent', 'ticket_categories.name AS category')
                ->leftJoin('users AS owners', 'owners.id', 'tickets.client_id')
                ->leftJoin('users AS agents', 'agents.id', 'tickets.staff_id')
                ->leftJoin('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
                ->leftJoin('ticket_statuses', 'ticket_statuses.id', 'tickets.status_id')
                ->where('tickets.ticket_id', $id)
                ->first();
            $messages = DB::table('ticket_messages')
                ->select('ticket_messages.*', 'sender.name AS sender_name', 'sender.photo AS sender_photo', 'receiver.name AS receiver_name', 'receiver.photo AS receiver.photo')
                ->leftJoin('users AS sender', 'sender.id', 'ticket_messages.sender_id')
                ->leftJoin('users AS receiver', 'receiver.id', 'ticket_messages.client_id')
                ->where('ticket_id', $id)
                ->orderBy('ticket_messages.updated_at')
                ->get();

            return View('admin.pages.editTicket', compact('ticket', 'messages', 'activeTickets', 'completedTickets', 'pendingTickets', 'files', 'ticket_categories'));

        } else {

            $ticket = Ticket::findOrFail($id);
            $files = File::whereHas('participants', function ($query) use($user) {
                    $query->where('user_id', '=', $user->id);
                })
                ->where('status', 0)
                ->get();
            $tickets = DB::table('tickets')
                ->select('tickets.*', 'client.photo as photo', 'client.name as name', 'ticket_categories.name as category')
                ->leftJoin('file_users', 'tickets.file_ref', 'file_users.file_ref')
                ->leftJoin('users AS client', 'tickets.client_id', 'client.id')
                ->leftJoin('ticket_categories', 'tickets.category_id', 'ticket_categories.category_id')
                ->where('file_users.user_id', $user->id)
                ->orWhere('staff_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();

            $messages = Ticket_Message::where('ticket_id', $ticket->ticket_id)
                ->select('ticket_messages.*', 'users.name', 'users.photo')
                ->join('users', 'users.id', 'ticket_messages.sender_id')
                ->orderBy('ticket_messages.created_at')
                ->get();

            return View('admin.pages.support', compact('files', 'tickets', 'messages', 'ticket'));
        }
    }

    public function sendMessage($id)
    {
        $data = Input::all();
        $me = Auth::user();
        $ticket = Ticket::findOrFail($id);

        $validator = Validator::make($data, [
            'message' => 'required|max:65536'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $content = [];
        if (Input::hasFile('attachments')) {
            $attachments = Input::file('attachments');
            $files = [];

            foreach ($attachments as $attachment) {
                $fileName = $attachment->getClientOriginalName();// . $attachment->getClientOriginalExtension();
                $directory = 'tickets/'.$ticket->ticket_id;
                $path = $attachment->storeAs($directory, Carbon::now()->toDateString() . '-' . str_random(5) . '-' . $fileName);
                $size = $this->formatBytes($attachment->getClientSize());

                $files[] = ['name' => $fileName, 'size' => $size, 'path' => $path];
            }
            $content['attachments'] = $files;
        }
        $content['text'] =  Input::get('message');

        $message = new Ticket_Message();
        $message->message = json_encode($content);
        $message->ticket_id = $ticket->ticket_id;
        $message->sender_id = $me->id;
        $message->client_id = $ticket->client_id;
        $message->save();

        return redirect()->back();
    }

    public function deleteTicket($id)
    {
        Ticket::findOrFail($id)->delete();
        Ticket_Message::where('ticket_id', $id)->delete();

        return redirect('admin/tickets')->with('flash_message', 'The ticket have been deleted successfully');
    }

    public function completeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status_id = 0;
        $ticket->save();

        return redirect('admin/tickets/complete')->with('flash_message', 'The ticket have been completed successfully');
    }

    public function reopenTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status_id = 2;
        $ticket->save();

        return redirect('admin/tickets')->with('flash_message', 'The ticket have been reopended successfully');
    }

    public function postTicket($id)
    {
        $data = Input::all();

        $validator = Validator::make($data, [
            'staff_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $ticket = Ticket::find($id);
        if (empty($ticket)) {
            return redirect('admins/tickets')->with('flash_message', 'The Ticket is not existed');
        }

        $ticket->staff_id = $data['staff_id'];
        $ticket->status_id = 2;
        $ticket->save();

        return redirect('admin/tickets/' . $id)->with('flash_message', 'The Ticket have been updated successfully');
    }

    public function download()
    {
        $path = Input::get('path');
        $name = Input::get('name');

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $path, $name);
    }

}
