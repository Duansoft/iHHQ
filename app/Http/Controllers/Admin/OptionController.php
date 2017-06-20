<?php

namespace App\Http\Controllers\Admin;

use App\Courier;
use App\Department;
use App\File_Category;
use App\File_Type;
use App\Office;
use App\Template_Category;
use App\Ticket_Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class OptionController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $offices = Office::all();
        $file_types = File_Type::all();
        $categories = File_Category::all();
        $couriers = Courier::all();
        $ticket_categories = Ticket_Category::all();
        $template_categories = Template_Category::all();
        $file_categories = File_Category::all();

        return View('admin.pages.options', compact('offices', 'file_types', 'couriers', 'categories', 'ticket_categories', 'template_categories', 'file_categories'));
    }

    public function postOffice($id = null)
    {
        if (empty($id)) {
            $office = new Office();
            $message = ["flash_message" => "The Office have been created successfully"];
        } else {
            $office = Office::findOrFail($id);
            $message = ["flash_message" => "The Office have been updated successfully"];
        }

        $office->fill(Input::all());
        $office->save();

        return redirect()->back()->with($message);
    }

    public function deleteOffice($id)
    {
        $office = Office::findOrFail($id);
        $office->delete();

        return redirect()->back()->with(["flash_message" => "The Office have been deleted successfully"]);
    }
}
