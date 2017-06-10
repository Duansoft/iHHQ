<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispatchController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return View('pages.dispatch');
    }
}
