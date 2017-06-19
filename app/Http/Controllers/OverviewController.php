<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\File;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\File_Document;
use App\Department;

class OverviewController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $myID = Auth::id();
        $announcements = Announcement::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $files = File::whereHas('participants', function ($query) use($myID) {
                $query->where('user_id', '=', $myID);
            })
            ->where('status', 0)
            ->get();
        $departments = Department::get();

        return View('pages.overview', compact('announcements', 'files', 'departments'));
    }

    public function viewFileDetail()
    {
        $id = Input::get('id');
        $file = File::findOrFail($id);

        if (empty($file)) {
            return redirect()->back()->withErrors(['The file is not available anymore']);
        }

        $documents = DB::table('file_documents')
            ->select('file_documents.*', 'users.name AS owner')
            ->join('users', 'file_documents.created_by', 'users.id')
            ->where('file_ref', $file->file_ref)
            ->get();
        $participants = DB::table('files')
            ->join('file_users', 'file_users.file_ref', 'files.file_ref')
            ->join('users', 'users.id', 'file_users.user_id')
            ->where('files.file_ref', $file->file_ref)
            ->get();
        $tickets = Ticket::where('file_ref', $file->file_ref)
            ->orderBy('created_at')
            ->get();

        return View('pages.fileDetail', compact('file', 'documents', 'participants', 'tickets'));
    }

    /**
     * Download uploaded Documents
     */
    public function download($id)
    {
        $document = File_Document::findOrFail($id);

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $document->path);
    }
}
