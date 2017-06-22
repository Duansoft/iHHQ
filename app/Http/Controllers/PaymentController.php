<?php

namespace App\Http\Controllers;

use App\Payment;
use Billplz\Three\Collection;
use Illuminate\Support\Facades\Auth;
use App\File;
use Money\Money;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\File_Document;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $myID = Auth::id();
        $files = File::whereHas('participants', function ($query) use ($myID) {
            $query->where('user_id', '=', $myID);
        })
            ->where('status', 0)
            ->get();

        return View('pages.payment', compact('files'));
    }

    public function proceedPay()
    {
        $data = Input::all();

        $user = Auth::user();
        $payment_id = Input::get('payment_id');
        $method = Input::get('method');

        $payment = Payment::findOrFail($payment_id);
        $fileRef = $payment->file_ref;
        $file = File::where('file_ref', $fileRef)->first();
        if (empty($file)) {
            abort(402);
        }

        if ($method == "bank") {
            $roles = [
                'amount' => 'required|numeric',
                'password' => 'required',
                'method' => 'required',
                'receipt' => 'required|file'
            ];
        } else {
            $roles = [
                'amount' => 'required|numeric',
                'password' => 'required',
                'method' => 'required'
            ];
        }

        $validator = Validator::make($data, $roles);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        if (!Hash::check(Input::get('password'), $user->password)) {
            return redirect()->back()->withErrors(['Password is not matched']);
        }


        // Proceed billplz payment
        if ($method == 'billplz') {
            // Create Collection
            $billplz = $this->createBillPlz();
            $collection = $billplz->collection();

            $title = $file->project_name . '(File Ref-' . $fileRef . ')';
            $response = $collection->create($title);

            // Create Bill
            if (!empty($response[id])) {
                $bill = $billplz->bill();
                $response1 = $bill->create(
                    $response['id'],
                    'duansoft555@gmail.com',
                    null,
                    'Michael API V3',
                    Money::MYR($payment->amount),
                    'http://example.com/webhook/billplz',
                    $payment->remarks
                );
            }

            if ($response1->getStatusCode() !== 200) {
                throw new SomethingHasGoneReallyBadException();
            }

            return Redirect::to($response1['url']);

        } else if ($method == "bank") {

            $receipt = Input::file('receipt');
            $file_ref = $file->file_ref;
            $fileName = $payment_id . '_bank_receipt.pdf';
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

                $payment->status = 2;
                $payment->paid_by = $user->id;
                $payment->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to proceed']);
            }

            return back()->with('flash_message', 'Thanks, Our staff will be confirm the receipt');
        }
    }

    public function downloadReceipt($id)
    {
        $payment = Payment::findOrFail($id);
        if (empty($payment->receipt)) {
            return redirect()->back()->withErrors(['No uploaded receipt']);
        }

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $payment->receipt);
    }

    public function downloadInvoice($id)
    {
        $payment = Payment::findOrFail($id);
        if (empty($payment->receipt)) {
            return redirect()->back()->withErrors(['No uploaded Invoice']);
        }

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $payment->invoice);
    }

}
