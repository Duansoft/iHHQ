<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\File;
use App\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\File_Document;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $files = File::where('status', 0)->get();

        return View('admin.pages.payment', compact('files'));
    }

    public function downloadReceipt($id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->receipt == null) {
            return redirect()->back()->withErrors(['No uploaded receipt']);
        }

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $payment->receipt);
    }

    public function uploadReceipt($id)
    {
        $data = Input::all();
        $payment = Payment::findOrFail($id);

        $validator = Validator::make($data, [
            'name' => 'required|max:50',
            'receipt' => 'required|file'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $receipt = Input::file('receipt');
        $file_ref = $payment->file_ref;
        $fileName = $data['name'] . '.pdf';
        $filePath = 'files/' . $file_ref . '/payments';

        DB::beginTransaction();
        try {
            $path = $receipt->storeAs($filePath, $fileName);

            $document = new File_Document();
            $document->fill($data);
            $document->path = $path;
            $document->created_by = Auth::id();
            $document->extension = $this->getExtensionType('pdf');
            $document->save();

            $payment->receipt = $path;
            $payment->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to proceed']);
        }

        return redirect()->back()->with('flash_message', 'Receipt is uploaded successfully');
    }
}
