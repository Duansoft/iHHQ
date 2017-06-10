<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $isActive = true;
        $activeCount = DB::table('announcements')->where('status', true)->count();
        $inactiveCount = DB::table('announcements')->where('status', false)->count();

        return View('admin.pages.announcement', compact('activeCount', 'inactiveCount', 'isActive'));
    }

    public function getAnnouncementAjax()
    {
        $announcements = DB::table('announcements')
            ->select('announcement_id', 'title', 'content', 'created_at')
            ->where('status', 1);

        return Datatables::of($announcements)
            ->editColumn('created_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $created_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getClosedAnnouncement()
    {
        $activeCount = DB::table('announcements')->where('status', true)->count();
        $inactiveCount = DB::table('announcements')->where('status', false)->count();

        return View('admin.pages.announcement', compact('activeCount', 'inactiveCount'));

    }

    public function getClosedAnnouncementAjax()
    {
        $announcements = DB::table('announcements')
            ->select('announcement_id', 'title', 'content', 'created_at')
            ->where('status', 0);

        return Datatables::of($announcements)
            ->editColumn('created_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $created_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $activeCount = DB::table('announcements')->where('status', true)->count();
        $inactiveCount = DB::table('announcements')->where('status', false)->count();

        return View('admin.pages.addEditAnnouncement', compact('announcement', 'activeCount', 'inactiveCount'));
    }

    public function postAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->fill(Input::all());
        $announcement->save();

        return redirect('admin/announcements')->with('flash_message', 'The announcement have been updated successfully');
    }

    public function getCreateAnnouncement()
    {
        $activeCount = DB::table('announcements')->where('status', true)->count();
        $inactiveCount = DB::table('announcements')->where('status', false)->count();

        return View('admin.pages.addEditAnnouncement', compact('activeCount', 'inactiveCount'));
    }

    public function createAnnouncement()
    {
        $data = Input::all();

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'content' => 'required|max:10000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        $announcement = new Announcement();
        $announcement->title = $data['title'];
        $announcement->content = $data['content'];
        $announcement->save();

        return redirect('admin/announcements')->with('flash_message', 'The announcement have been created successfully');
    }

    public function updateAnnouncement($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required|max:2000',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->fillable($request->all());
        $announcement->save();

        Session::flash('flash_message', 'Announcement have been updated successfully');

        return redirect('admin/announcements');
    }

    public function closeAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->status = 0;
        $announcement->save();

        return redirect('admin/announcements/close')->with('flash_message', 'The announcement have been closed');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect('admin/announcements')->with('flash_message', 'The announcement have been deleted');
    }
}
