<?php

namespace App\Http\Controllers\Admin;

use App\Courier;
use App\Department;
use App\File_Category;
use App\Office;
use App\Template_Category;
use App\Ticket_Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class OptionController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $offices = Office::all();
        $departments = Department::all();
        $categories = File_Category::all();
        $couriers = Courier::all();
        $ticket_categories = Ticket_Category::all();
        $template_categories = Template_Category::all();

        return View('admin.pages.options', compact('offices', 'departments', 'couriers', 'categories', 'ticket_categories', 'template_categories'));
    }
}
