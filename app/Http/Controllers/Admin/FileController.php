<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\File_Document;
use App\File_Subcategory;
use App\File_User;
use App\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use App\File;
use Illuminate\Support\Facades\Storage;


class FileController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $files = DB::table('files')
                ->select('file_id', 'file_ref', 'project_name', 'created_at');
            return Datatables::of($files)
                ->editColumn('created_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $created_at)->toFormattedDateString() !!}')
                ->make(true);
        }

        return View('admin.pages.file');
    }

    public function getFile($id = null)
    {
        $file_id = $id;

        $lawyers = DB::table("users")
            ->select('users.id', 'users.name')
            ->leftJoin('role_user', 'role_user.user_id', 'users.id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.name', 'lawyer')
            ->orderBy('users.name')
            ->get();
        $staffs = DB::table("users")
            ->select('users.id', 'users.name')
            ->leftJoin('role_user', 'role_user.user_id', 'users.id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->where('roles.name', 'staff')
            ->orderBy('users.name')
            ->get();
        $departments = Department::all();
        $subcategories = File_Subcategory::all();

        if (!empty($file_id)) {
            $file = File::findOrFail($file_id);
            return View('admin.pages.addEditFile', compact('file', 'lawyers', 'staffs', 'departments', 'subcategories'));
        }

        return View('admin.pages.addEditFile', compact('lawyers', 'staffs', 'departments', 'subcategories'));
    }

    /**
     * Create New File
     */
    public function postFile($id = null)
    {
        $data = Input::all();
        $file_id = $id;

        $validator = Validator::make($data, [
           'file_ref' => 'required|unique:files',
            'project_name' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'subject_matter' => 'required|max:255',
            'subject_description' => 'required|max:2024',
            'lawyers' => 'required',
            'staffs' => 'required',
            'clients' => 'required',
            'contact' => 'nullable|max:255',
            'contact_name' => 'nullable|max:50',
            'contact_email' => 'nullable|email|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        if (empty($file_id)) {
            $file = new File();
            $file->fill($data);
            $file->created_by = Auth::id();
            $message = 'The file have been created successfully';
        } else {
            $file = File::findOrFail($file_id);
            $file->fill(Input::except('file_ref'));
            $file->updated_by = Auth::id();
            $message = 'The file have been updated successfully';
        }

        $lawyers = Input::get('lawyers');
        foreach($lawyers as $lawyer) {
            $file_user = new File_User();
            $file_user->file_ref = $data['file_ref'];
            $file_user->user_id = $lawyer;
            $file_user->role = 'lawyer';
            $file_user->save();
        }

        $staffs = Input::get('staffs');
        foreach($staffs as $staff) {
            $file_user = new File_User();
            $file_user->file_ref = $data['file_ref'];
            $file_user->user_id = $staff;
            $file_user->role = 'staff';
            $file_user->save();
        }

        $clients = Input::get('clients');
        foreach($clients as $client) {
            $file_user = new File_User();
            $file_user->file_ref = $data['file_ref'];
            $file_user->user_id = $client;
            $file_user->role = 'client';
            $file_user->save();
        }

        if (Input::has('spectators')) {
            $spectators = Input::get('spectators');
            foreach($spectators as $spectator) {
                $file_user = new File_User();
                $file_user->file_ref = $data['file_ref'];
                $file_user->user_id = $spectator;
                $file_user->role = 'spectator';
                $file_user->save();
            }
        }

        $file->save();

        return redirect('admin/files')->with('flash_message', $message);

    }

    public function searchFileAjax()
    {
        $user_id = Input::get('id');

        $files = DB::table('file_users')
            ->select('files.file_ref')
            ->join('files', 'files.file_ref', 'file_users.file_ref')
            ->where('user_id', $user_id)
            ->where('role', 'client')
            ->where('status', 0) // opened file
            ->get();

        $values = [];
        foreach ($files as $file) {
            $values[] = $file->file_ref;
        }

        return response()->json($values);
    }

    /**
     * File Detail
     */
    public function getFileDetail($id)
    {
        $file = File::findOrFail($id);
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

        return View('admin.pages.fileDetail', compact('file', 'documents', 'participants'));
    }

    /**
     * Upload New Document
     */
    public function postDocument($id)
    {
        $data = Input::all();
        $file = File::findOrFail($id);

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'name' => 'required|max:100',
            'file' => 'file'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
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
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to upload document']);
        }

        return redirect()->back()->with('flash_message', 'Document have been uploaded successfully');
    }

    /**
     * Download uploaded Documents
     */
    public function download($id)
    {
        $document = File_Document::findOrFail($id);

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $document->path);
    }

    /**
     * Create Payment
     */
    public function createPayment($id)
    {
        $data = Input::all();
        $file = File::findOrFail($id);

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'amount' => 'required',
            'purpose' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $payment = new Payment();
        $payment->fill($data);
        $payment->created_by = Auth::id();
        $payment->save();

        $file->outstanding_amount = Payment::where('file_ref', $file->file_ref)
            ->where('status', 0)
            ->sum('amount');
        $file->save();

        return redirect()->back()->with('flash_message', 'Payment have been created successfully');
    }
}
