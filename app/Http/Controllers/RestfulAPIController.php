<?php

namespace App\Http\Controllers;

use App\Dispatch;
use App\File;
use App\File_Document;
use App\File_User;
use App\Notification;
use App\Payment;
use App\Ticket;
use App\User;
use App\Ticket_Message;
use App\Ticket_Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class RestfulAPIController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {

    }

    /**
     * Users
     */
    public function login()
    {
        $data = Input::all();
        $email = Input::get('email');
        $password = Input::get('password');

        $validator = Validator::make($data, [
            'email' => 'required:email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator);
        }

        $user = User::where('email', $email)->first();
        if (empty($user)) {
            return $this->responseBadRequestError('Email or Password is incorrect');
        }

        $hashedPassword = $user->password;
        if (!Hash::check($password, $hashedPassword)) {
            return $this->responseBadRequestError('Email or Password is not correct');
        }

        if ($user->hasRole('admin')) {
            $role = 'admin';
        } elseif ($user->hasRole('lawyer')) {
            $role = 'lawyer';
        } elseif ($user->hasRole('staff')) {
            $role = 'staff';
        } elseif ($user->hasRole('billing')) {
            $role = 'billing';
        } elseif ($user->hasRole('logistic')) {
            $role = 'logistic';
        } elseif ($user->hasRole('client')) {
            $role = 'client';
        }

        // Create Token
        $token = JWTAuth::fromUser($user);
        $response = array_merge($user->toArray(), ['token' => $token, 'role' => $role]);

        return $this->responseSuccess($response);
    }

    public function forgotPassword()
    {
        $email = Input::get('email');
        $user = User::where('email', $email)->first();
        if (empty($user)) {
            return $this->responseBadRequestError('Email is not existed');
        }

        // send email to reset password

        return $this->responseSuccess();
    }

    public function postUserProfile()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $data = Input::all();
        $validator = Validator::make($data, [
            'id' => 'required',
            'address' => 'max:255'
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator);
        }

        $me->fill($data);
        $me->save();

        return $this->responseSuccess($me);
    }

    /**
     * File Module
     */
    public function getFiles()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $data = Input::all();
        $pageSize = empty(Input::get('per_page')) ? 60 : Input::get('per_page');

        if ($me->hasRole('admin')) {
            $files = File::where('status', 0)
                ->orderBy('created_at')
                ->paginate($pageSize)
                ->appends($data);
        } else {
            $files = File::whereHas('participants', function($query) use($me){
                    $query->where('user_id', $me->id);
                })
                ->where('status', 0)
                ->orderBy('created_at')
                ->paginate($pageSize)
                ->appends($data);
        }

        return $this->responseSuccess($files);
    }

    public function getFileDocuments($id)
    {
        $file = File::findOrFail($id);
        $documents = File_Document::with(['created_by' => function($query){
                $query->select('id', 'name');
            }])
            ->where('file_ref', $file->file_ref)
            ->orderBy('file_documents.created_at')
            ->get();

        return $this->responseSuccess($documents);
    }

    public function postFileDocuments($id)
    {
        $file = File::findOrFail($id);
        $data = Input::all();

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'name' => 'required|max:100',
            'file' => 'required|file'
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator);
        }

        $doc = Input::file('file');
        $file_ref = $file->file_ref;
        $extension = $doc->getClientOriginalExtension();
        $fileName = Input::get('name') . '.' . $extension;
        $filePath = 'files/' . $file_ref . '/documents';

        DB::beginTransaction();
        try {
            $path = $doc->storeAs($filePath, $fileName);

            $document = new File_Document();
            $document->fill($data);
            $document->path = $path;
            $document->created_by = Auth::id();
            $document->extension = $this->getExtensionType($extension);
            $document->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseInternalServerError();
        }

        return $this->responseSuccess($document);
    }

    public function getFilePayments($id)
    {
        $file = File::findOrFail($id);
        $payments = Payment::where('file_ref', $file->file_ref)
            ->orderBy('created_at')
            ->get();

        return $this->responseSuccess($payments);
    }

    public function getFileContacts($id)
    {
        $file = File::findOrFail($id);
        $contacts = File_User::select('id', 'email', 'mobile', 'name', 'role', 'country_id')
            ->where('file_ref', $file->file_ref)
            ->whereIn('role', ['lawyer', 'staff'])
            ->join('users', 'users.id', 'file_users.user_id')
            ->get();

        return $this->responseSuccess($contacts);
    }

    public function getFileTickets($id)
    {
        $file = File::findOrFail($id);
        $tickets = Ticket::select('tickets.*', 'ticket_categories.name AS category')
            ->where('file_ref', $file->file_ref)
            ->join('ticket_categories', 'ticket_categories.category_id', 'tickets.category_id')
            ->orderBy('created_at')
            ->get();

        return $this->responseSuccess($tickets);
    }

    /**
     * Dispatch Module
     */
    public function getDispatches()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $data = Input::all();
        $pageSize = empty(Input::get('per_page')) ? 60 : Input::get('per_page');
        $now = Carbon::now()->addMonth(-1)->format("Y-m-d H:i:s");

        if ($me->hasRole('admin')) {
            $dispatches = Dispatch::where('status', '!=', 1)
                ->orWhere(function($query) use($now){
                    $query->where('status', 1)
                        ->where('created_at', '>', $now);
                })
                ->orderBy('created_at')
                ->paginate($pageSize)
                ->appends($data);
        } else {
            $dispatches = Dispatch::select('dispatches.*')
                ->join('files', 'files.file_ref', 'dispatches.file_ref')
                ->join('file_users', 'file_users.file_ref', 'dispatches.file_ref')
                ->where('files.status', 0) // open
                ->where('user_id', $me->id)
                ->orderBy('created_at')
                ->paginate($pageSize)
                ->appends($data);
        }

        return response()->json($dispatches);
    }

    public function postQRCode($id)
    {
        $dispatch = Dispatch::findOrFail($id);

        $data = Input::all();
        $code = Input::get('qr_code');
        $file_ref = Input::get('file_ref');

        $validator = Validator::make($data, [
            'qr_code' => 'required',
            'file_ref' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator);
        }

        if ($code == $dispatch->qr_code && $file_ref == $dispatch->file_ref) {
            return $this->responseSuccess();
        }

        return $this->responseNotAcceptableError();
    }

    /**
     * Ticket
     */
    public function getTickets()
    {
        $me = JWTAuth::parseToken()->authenticate();

        $data = Input::all();
        $pageSize = empty(Input::get('per_page')) ? 60 : Input::get('per_page');

        if ($me->hasRole('admin')) {
            $tickets = Ticket::whereIn('status_id', [0, 1])
                ->orderBy('created_at', 'desc')
                ->paginate($pageSize)
                ->appends($data);
        } else if ($me->hasRole('client')) {
            $tickets = Ticket::whereIn('status_id', [0, 1])
                ->where('client_id', $me->id)
                ->orderBy('created_at', 'desc')
                ->paginate($pageSize)
                ->appends($data);
        } else {
            $tickets = Ticket::whereIn('status_id', [0, 1])
                ->where('staff_id', $me->id)
                ->orderBy('created_at', 'desc')
                ->paginate($pageSize)
                ->appends($data);
        }

        return $this->responseSuccess($tickets);
    }

    public function getTicketCategories()
    {
        $categories = Ticket_Category::all();

        return $this->responseSuccess($categories);
    }

    public function createTicket()
    {
        $me = JWTAuth::parseToken()->authenticate();
        $data = Input::all();

        if (!$me->hasRole('client')) {
            return $this->responseAccessDeniedError();
        }

        $validator = Validator::make($data, [
            'department_id' => 'required|max:255',
            'subject' => 'required|max:500',
            'message' => 'required|max:4048',
            'file_ref' => 'max:50'
        ]);
        if ($validator->fails()) {
            return $this->responseValidationError($validator);
        }

        // Create Ticket
        $ticket = new Ticket();
        $ticket = $ticket->fill($data);
        $ticket->client_id = $me->id;
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
        $message->client_id = $me->id;
        $message->sender_id = $me->id;

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

        return $this->responseSuccess($ticket);
    }

    public function getTicketMessages($id)
    {
        $ticket = Ticket::findOrFail($id);

        $data = Input::all();
        $pageSize = empty(Input::get('per_page')) ? 60 : Input::get('per_page');

        $messages = Ticket_Message::where('ticket_id', $ticket->ticket_id)
            ->select('ticket_messages.*', 'users.name', 'users.photo')
            ->join('users', 'users.id', 'ticket_messages.sender_id')
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize)
            ->appends($data);

        return $this->responseSuccess($messages);
    }

    public function postTicketMessages($id)
    {
        $me = JWTAuth::parseToken()->authenticate();
        $ticket = Ticket::findOrFail($id);

        $data = Input::all();

        $validator = Validator::make($data, [
            'message' => 'required|max:4048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        // Create Ticket message
        $message = new Ticket_Message();
        $message->ticket_id = $ticket->ticket_id;
        $message->client_id = $me->id;
        $message->sender_id = $me->id;

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

        return $this->responseSuccess($message);
    }

    /**
     * Notification Module
     */
    public function getNotifications()
    {
        $me = JWTAuth::parseToken()->authenticate();
        $data = Input::all();
        $pageSize = empty(Input::get('per_page')) ? 60 : Input::get('per_page');

        $notifications = Notification::where('user_id', $me->id)
            ->orderBy('created_at')
            ->paginate($pageSize)
            ->appends($data);

        return $this->responseSuccess($notifications);
    }
}
