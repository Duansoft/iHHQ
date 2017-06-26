<?php

namespace App\Http\Controllers;

use App\Department;
use App\File;
use App\Ticket;
use App\Ticket_Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        // Create Ticket
        $ticket = new Ticket();
        $ticket = $ticket->fill($data);
        $ticket->client_id = $user->id;
        if (Input::has('file_ref') && !empty(Input::get('file_ref'))) {
            $ticket->file_ref = Input::get('file_ref');
            $ticket->status_id = 2; //active
        } else {
            $ticket->status_id = 1; //pending
        }
        $ticket->save();

        // Create Ticket message
        $message = new Ticket_Message();
        $message->ticket_id = $ticket->ticket_id;
        $message->client_id = $user->id;
        $message->sender_id = $user->id;

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

        return redirect('support');
    }

    public function getTicket($id)
    {
        $myID = Auth::id();
        $ticket = Ticket::findOrFail($id);
        $departments = Department::get();
        $files = File::whereHas('participants', function ($query) use($myID) {
                $query->where('user_id', '=', $myID);
            })
            ->where('status', 0)
            ->get();
        $tickets = Ticket::where('client_id', $myID)
            ->where('status_id', '!=', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        $messages = Ticket_Message::where('ticket_id', $ticket->ticket_id)
            ->select('ticket_messages.*', 'users.name', 'users.photo')
            ->join('users', 'users.id', 'ticket_messages.sender_id')
            ->orderBy('ticket_messages.created_at')
            ->get();

        return View('pages.support', compact('departments', 'files', 'tickets', 'messages', 'ticket'));
    }

    public function postMessage($id)
    {
        $ticket = Ticket::findOrFail($id);
        $message = Input::get('message');

        if (!$ticket->isActive()) {
            return redirect()->back()->withErrors(['The Ticket is not active yet']);
        }

        $validator = Validator::make([
            'message' => $message
        ],[
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

        $ticket_message = new Ticket_Message();
        $ticket_message->ticket_id = $ticket->ticket_id;
        $ticket_message->message = json_encode($content);
        $ticket_message->sender_id = Auth::id();
        $ticket_message->client_id = $ticket->client_id;
        $ticket_message->save();

        return redirect()->back();
    }

    public function download()
    {
        $path = Input::get('path');
        $name = Input::get('name');

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $path, $name);
    }
}
