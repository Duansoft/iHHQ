<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use App\File;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        return View('admin.pages.overview', compact('announcements', 'files'));
    }
}

//::where('expire_date', '>=', date('Y-m-d H:i:s'))
