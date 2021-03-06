<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\File_Category;
use App\File_Document;
use App\File_Subcategory;
use App\File_Type;
use App\File_User;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
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
                ->select('file_id', 'file_ref', 'project_name', 'updated_at', 'outstanding_amount', 'paid_amount', 'percent');

            return Datatables::of($files)
                ->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->toFormattedDateString() !!}')
                ->addColumn('time_ago', function($file){
                    return \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->updated_at)->diffForHumans();
                })
                ->addColumn('billing', function($file){
                    return $file->outstanding_amount - $file->paid_amount;
                })
                ->addColumn('action', function ($file) {
                    return '<div class="btn-group btn-group-fade">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions<span class="caret pl-15"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="./files/' . $file->file_id . '/detail"> Detail</a></li>
                                <li><a href="./files/' . $file->file_id . '"> Edit</a></li>
                                <li><a href="./files/' . $file->file_id . '/close"> Close</a></li>
                            </ul>
                        </div>';
                })
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
        $file_types = File_Type::all();
        $categories = File_Category::all();
        $subcategories = File_Subcategory::where('category_id', $categories[0]->category_id)->get();

        if (!empty($file_id)) {
            $file = File::findOrFail($file_id);
            $subcategories = File_Subcategory::where('category_id', $file->subcategory_id)->get();
            $clients = DB::table('file_users')
                ->join('users', 'users.id', 'file_users.user_id')
                ->where('file_ref', $file->file_ref)
                ->where('file_users.role', 'client')
                ->get();
            $spectators = DB::table('file_users')
                ->join('users', 'users.id', 'file_users.user_id')
                ->where('file_ref', $file->file_ref)
                ->where('file_users.role', 'spectator')
                ->get();

            return View('admin.pages.addEditFile', compact('file', 'lawyers', 'staffs', 'file_types',  'categories', 'subcategories', 'clients', 'spectators'));
        }

        // Get Auto Primary Key
        $statement = DB::select("show table status like 'files'");
        $autoID = $statement[0]->Auto_increment;

        return View('admin.pages.addEditFile', compact('lawyers', 'staffs', 'file_types', 'subcategories', 'categories', 'subcategories', 'autoID'));
    }

    public function checkConflict()
    {
        $clients = Input::get('clients');
        if (sizeof($clients) > 0) {
            $files = DB::table('file_users')
                ->join('files', 'files.file_ref', 'file_users.file_ref')
                ->join('users', 'file_users.user_id', 'users.id')
                ->where('file_users.role', 'client')
                ->whereIn('file_users.user_id', $clients)
                ->orderBy('files.created_at', 'desc')
                ->get();

            return View('admin.pages.checkFileConflict', compact('files'));
        } else {
            return response()->json();
        }
    }

    /**
     * Create New File
     */
    public function postFile($id = null)
    {
        $data = Input::all();
        $file_id = $id;

        if (empty($id)) {
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
                'cases' => 'required',
            ]);
        } else {
            $validator = Validator::make($data, [
                'file_ref' => 'required',
                'project_name' => 'required',
                'department_id' => 'required',
                'subject_matter' => 'required|max:255',
                'subject_description' => 'required|max:2024',
                'lawyers' => 'required',
                'staffs' => 'required',
                'clients' => 'required',
                'contact' => 'nullable|max:255',
                'contact_name' => 'nullable|max:50',
                'contact_email' => 'nullable|email|max:50',
                'cases' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->messages());
        }

        if (Input::has('spectators')) {
            foreach(Input::get('clients') as $client)
            {
                foreach(Input::get('spectators') as $spectator)
                {
                    if ($client == $spectator) {
                        return redirect()->back()->withInput()->withErrors('Clients and Spectators cannot be same');
                    }
                }
            }
        }

        if (empty($file_id)) {
            $file = new File();
            $file->fill($data);
            $file->created_by = Auth::id();
            $message = 'The file have been created successfully';
            $file->save();

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

        } else {
            $file = File::findOrFail($file_id);
            $file->fill(Input::except('file_ref'));
            $file->updated_by = Auth::id();
            $message = 'The file have been updated successfully';

            $lawyers = Input::get('lawyers');
            foreach($lawyers as $lawyer) {
                $old = File_User::where('file_ref', $file->file_ref)
                    ->where('user_id', $lawyer)
                    ->first();
                if (empty($old)) {
                    $file_user = new File_User();
                    $file_user->file_ref = $data['file_ref'];
                    $file_user->user_id = $lawyer;
                    $file_user->role = 'lawyer';
                    $file_user->save();
                }
            }

            $staffs = Input::get('staffs');
            foreach($staffs as $staff) {
                $old1 = File_User::where('file_ref', $file->file_ref)
                    ->where('user_id', $staff)
                    ->first();
                if (empty($old1)) {
                    $file_user = new File_User();
                    $file_user->file_ref = $data['file_ref'];
                    $file_user->user_id = $staff;
                    $file_user->role = 'staff';
                    $file_user->save();
                }
            }

            $clients = Input::get('clients');
            foreach($clients as $client) {
                $old2 = File_User::where('file_ref', $file->file_ref)
                    ->where('user_id', $client)
                    ->first();
                if (empty($old2)) {
                    $file_user = new File_User();
                    $file_user->file_ref = $data['file_ref'];
                    $file_user->user_id = $client;
                    $file_user->role = 'client';
                    $file_user->save();
                }
            }

            if (Input::has('spectators')) {
                $spectators = Input::get('spectators');
                foreach($spectators as $spectator) {
                    $old3 = File_User::where('file_ref', $file->file_ref)
                        ->where('user_id', $spectator)
                        ->first();

                    if (empty($old3)) {
                        $file_user = new File_User();
                        $file_user->file_ref = $data['file_ref'];
                        $file_user->user_id = $spectator;
                        $file_user->role = 'spectator';
                        $file_user->save();
                    }
                }
            }
        }
        //$file->outstanding_amount = $this->calculateTotalOutstandingAmount($data['cases']);
        $file->percent = $this->calculateCompletionPercent(Input::get('cases'));
        $file->save();

        return redirect('admin/files')->with('flash_message', $message);

    }

    public function closeFile($id)
    {
        $file = File::findOrFail($id);

        $isNoConflict = true;
        // check uncompleted cases
        $cases = json_decode($file->cases);
        foreach($cases as $case) {
            if ($case->status != 'Completed') {
                $isNoConflict = false;
                Session::push('status', "NOT COMPLETE : " . $case->activity);
            }
        }

        // check unreceived milestones
        $payments = Payment::where('file_ref', $file->file_ref)->get();
        foreach($payments as $payment) {
            if ($payment->status != "RECEIVED") { // received
                $isNoConflict = false;
                Session::push('status', "NOT RECEIVED : " . $payment->purpose);
            }
        }

        if ($isNoConflict) {
            $file->status = 1;
            $file->save();
            Session::flash('flash_message', "The File (" . $file->file_ref . ") is closed successfully");
        }

        return redirect()->back();
    }

    /**
     * Ajax Functions
     */
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
     * Search files with key using ajax
     */
    public function seekFileAjax(Request $request)
    {
        $page = Input::get('page');
        $resultCount = 50;
        $offset = ($page - 1) * $resultCount;

        $data = [];

        if (!$request->has('q')) {
            $results = array(
                "results" => $data,
                "total_count" => 0,
                "pagination" => array(
                    "more" => false
                )
            );

            return response()->json($results);
        }

        $keyword = $request->q;
        $data = DB::table("files")
            ->select('files.file_ref')
            ->where('file_ref', 'LIKE', "%$keyword%")
            ->orderBy('file_ref')
            ->skip($offset)
            ->take($resultCount)
            ->get();

        $count = DB::table("files")
            ->select('files.file_ref')
            ->where('file_ref', 'LIKE', "%$keyword%")
            ->count();

        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = [
            "total_count" => $count,
            "pagination" => ["more" => $morePages],
            "results" => $data,
        ];

        return response()->json($results);
    }

    public function getFileClientsAjax()
    {
        $file_ref = Input::get('file_ref');

        $participants = DB::table('file_users')
            ->select('name AS text', 'id')
            ->join('users', 'file_users.user_id', 'users.id')
            ->where('file_ref',$file_ref)
            ->where(function($query){
                $query->where('role', 'client');
                $query->orWhere('role', 'spectator');
            })
            ->get();

        return response()->json($participants);
    }

    public function getSubCategoriesAjax()
    {
        $id = Input::get('id');
        $subCategories = File_Subcategory::where('category_id', $id)->get();

        $values = [];
        foreach ($subCategories as $subcategory) {
            $values[] = ['id' => $subcategory->subcategory_id, 'text' => $subcategory->name, 'data' => $subcategory->template];
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
     * Upload Case Document
     */
    public function postCaseDocument($id)
    {
        $data = Input::all();
        $index = Input::get('index');
        $file = File::findOrFail($id);

        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'name' => 'required|max:100',
            'file' => 'required|file',
            'index' => 'required|numeric'
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

            $cases = json_decode($file->cases);
            $case = $cases[$index];
            $case->status = "Completed";
            $file->cases = json_encode($cases);
            $file->percent = $this->calculateCompletionPercent(json_encode($cases));
            $file->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to upload document']);
        }

        return redirect()->back()->with('flash_message', 'Document have been uploaded successfully');
    }

    /**
     * Get Milestone Dialog
     */
    public function getMilestone($id)
    {
        $file = File::findOrFail($id);

        if ($file->status == 1) { // closed
            return redirect()->back()->withErrors('errors', 'The file was already closed. Not allowed to create new milestone');
        }

        return View('admin.pages.milestone-dialog', compact('file'));
    }

    public function createMilestone($id)
    {
        $file = File::findOrFail($id);

        if ($file->status == 1) { // closed
            return redirect()->back()->withErrors('errors', 'The file was already closed. Not allowed to create new milestone');
        }

        $data = Input::all();
        $validator = Validator::make($data, [
            'file_ref' => 'required',
            'activity' => 'required',
//            'milestone' => 'required',
//            'duration' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages())->withInput();
        }

        $case = [];
        $case["no"] = sizeof(json_decode($file->cases)) + 1;
        $case["activity"] = $data["activity"];
        $case["status"] = "In Progress";
//        $case["duration"] = $data["duration"];
//        $case["milestone"] = $data["milestone"];

        $cases = json_decode($file->cases);
        $cases[] = $case;
        $file->cases = json_encode($cases);
//        $file->outstanding_amount = $this->calculateTotalOutstandingAmount(json_encode($cases));
        $file->percent = $this->calculateCompletionPercent($file->cases);
        $file->save();

        return redirect()->back()->with('flash_message', 'New Milestone has been created successfully');
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
            'purpose' => 'required',
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }


        DB::beginTransaction();
        try {
            $invoice = Input::file('file');
            $file_ref = $file->file_ref;
            $fileName = $file_ref . "(" . str_random(5) . ")-" . Carbon::now()->toDateString() . '-invoice.pdf';
            $filePath = 'files/' . $file_ref . '/payments';

            $path = $invoice->storeAs($filePath, $fileName);

            $payment = new Payment();
            $payment->fill($data);
            $payment->invoice = $path;
            $payment->created_by = Auth::id();
            $payment->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to upload document']);
        }

        $file->outstanding_amount = Payment::where('file_ref', $file->file_ref)
            ->where('status', 0)
            ->sum('amount');
        $file->save();

        return redirect()->back()->with('flash_message', 'Payment have been created successfully');
    }

    /**
     * confirm Payment
     */
    public function verifiedPayment($id, $pid)
    {
        $file = File::findOrFail($id);
        $payment = Payment::findOrFail($pid);
        $payment->status = 1;
        $payment->save();

        $file->paid_amount = Payment::where('file_ref', $file->file_ref)
            ->where('status', 1)
            ->sum('amount');
        $file->save();

        return redirect()->back()->with('flash_message', 'The payment is confirmed');
    }


    /**
     * Private Methods
     */
    private function calculateTotalOutstandingAmount($data)
    {
        $cases = json_decode($data);

        $amount = 0;
        foreach($cases as $case) {
            $amount += $case->milestone;
        }

        return $amount;
    }

    private function calculateCompletionPercent($data)
    {
        $cases = json_decode($data);

        $total = sizeof($cases);
        $count = 0;
        foreach($cases as $case) {
            if ($case->status == "Completed") {
                $count++;
            }
        }

        return $count / $total * 100;
    }
}
