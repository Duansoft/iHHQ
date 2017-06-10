<?php

namespace App\Http\Controllers;


use App\Payment;
use Illuminate\Support\Facades\Auth;
use App\File;
use Money\Money;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


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
        $method = Input::get('method');
        $fileRef = Input::get('file_ref');
        $payment_id = Input::get('payment_id');

        $validator = Validator::make($data, [
            'amount' => 'required|numeric',
            'password' => 'required',
            'method' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        if (!Hash::check(Input::get('password'), $user->password)) {
            return redirect()->back()->withErrors('error', 'Password is not matched');
        }

        $payment = Payment::findOrFail($payment_id);

        $file = File::where('file_ref', $fileRef)->first();
        if (empty($file)) {
            abort(402);
        }
        if ($file->status != 0) {
            $message = $file->file_ref . ' - ' . ' is not opened.';
            return redirect()->back()->withErrors('error', $message);
        }

        // Proceed billplz payment
        if ($method == 'billplz') {
            // Create Collection
            $billplz = $this->createBillPlz();
            $collection = $billplz->collection();

            $title = $file->project_name . '(File Ref-' . $fileRef . ')';
            $response = $collection->create($title, $file->remarks, Money::MYR($payment->amount));

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

        } else if ($message = "bank") {

            return redirect()->back()->with('flash_message', "Please upload bank's receipt for verification of payment");
        }
    }

}
