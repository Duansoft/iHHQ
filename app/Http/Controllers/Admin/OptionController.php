<?php

namespace App\Http\Controllers\Admin;

use App\Courier;
use App\Department;
use App\File_Category;
use App\File_Subcategory;
use App\File_Type;
use App\Office;
use App\Template_Category;
use App\Ticket_Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
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
        $subcategories = File_Subcategory::all();

        return View('admin.pages.options', compact('offices', 'file_types', 'couriers', 'categories', 'subcategories', 'ticket_categories', 'template_categories'));
    }

    public function postOffice($id = null)
    {
        if (empty($id)) {
            $office = new Office();
            $message = ["flash_message" => "The office have been created successfully"];
        } else {
            $office = Office::findOrFail($id);
            $message = ["flash_message" => "The office have been updated successfully"];
        }

        $office->fill(Input::all());
        $office->save();

        return redirect()->back()->with($message);
    }

    public function deleteOffice($id)
    {
        $office = Office::findOrFail($id);
        $office->delete();

        return redirect()->back()->with(["flash_message" => "The office have been deleted successfully"]);
    }

    public function postFileType($id = null)
    {
        if (empty($id)) {
            $type = new File_Type();
            $message = ["flash_message" => "The file type have been created successfully"];
        } else {
            $type = File_Type::findOrFail($id);
            $message = ["flash_message" => "The file type have been updated successfully"];
        }

        $type->fill(Input::all());
        $type->save();

        return redirect()->back()->with($message);
    }

    public function deleteFileType($id)
    {
        $type = File_Type::findOrFail($id);
        $type->delete();

        return redirect()->back()->with(["flash_message" => "The file type have been deleted successfully"]);
    }

    public function postTicketCategory($id = null)
    {
        if (empty($id)) {
            $category = new Ticket_Category();
            $message = ["flash_message" => "The ticket category have been created successfully"];
        } else {
            $category = Ticket_Category::findOrFail($id);
            $message = ["flash_message" => "The ticket category have been updated successfully"];
        }

        $category->fill(Input::all());
        $category->save();

        return redirect()->back()->with($message);
    }

    public function deleteTicketCategory($id)
    {
        $category = Ticket_Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with(["flash_message" => "The ticket category have been deleted successfully"]);
    }

    public function postTemplateCategory($id = null)
    {
        if (empty($id)) {
            $category = new Template_Category();
            $message = ["flash_message" => "The ticket category have been created successfully"];
        } else {
            $category = Template_Category::findOrFail($id);
            $message = ["flash_message" => "The ticket category have been updated successfully"];
        }

        $category->fill(Input::all());
        $category->save();

        return redirect()->back()->with($message);
    }

    public function deleteTemplateCategory($id)
    {
        $category = Template_Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with(["flash_message" => "The ticket category have been deleted successfully"]);
    }

    public function postCourier($id = null)
    {
        if (empty($id)) {
            $courier = new Courier();
            $message = ["flash_message" => "The service provider have been created successfully"];
        } else {
            $courier = Courier::findOrFail($id);
            $message = ["flash_message" => "The service provider have been updated successfully"];
        }

        $courier->fill(Input::all());
        $courier->save();

        return redirect()->back()->with($message);
    }

    public function deleteCourier($id)
    {
        $courier = Courier::findOrFail($id);
        $courier->delete();

        return redirect()->back()->with(["flash_message" => "The service provider have been deleted successfully"]);
    }

    public function postCategory($id = null)
    {
        if (empty($id)) {
            $category = new File_Category();
            $message = ["flash_message" => "The category have been created successfully"];
        } else {
            $category = File_Category::findOrFail($id);
            $message = ["flash_message" => "The category have been updated successfully"];
        }

        $category->fill(Input::all());
        $category->save();

        return redirect()->back()->with($message);
    }

    public function deleteCategory($id)
    {
        $category = File_Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with(["flash_message" => "The category have been deleted successfully"]);
    }

    public function postSubCategory($id = null)
    {
        $data = Input::all();
        $validator = Validator::make($data, [
            'category_id' => 'required|numeric',
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        if (empty($id)) {
            $category = new File_Subcategory();
            $message = ["flash_message" => "The sub category have been created successfully"];
        } else {
            $category = File_Subcategory::findOrFail($id);
            $message = ["flash_message" => "The sub category have been updated successfully"];
        }

        $category->fill(Input::all());
        $category->save();

        return redirect()->back()->with($message);
    }

    public function deleteSubCategory($id)
    {
        $category = File_Subcategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with(["flash_message" => "The sub category have been deleted successfully"]);
    }

}
