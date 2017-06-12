<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


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
}
