<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\File_Category;
use App\File_Subcategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class MilestoneTemplate extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $categories = File_Category::all();
        if (sizeof($categories) > 0) {
            $category = $categories->first();
            $subcategories = File_Subcategory::where('category_id', $category->category_id)->get();

            return View('admin.pages.milestoneTemplates', compact('categories', 'subcategories'));
        }

        return View('admin.pages.milestoneTemplates', compact('categories'));
    }

    public function postMilestone()
    {
        $data = Input::all();
        $validator = Validator::make($data, [
            'category_id' => 'required|numeric',
            'subcategory_id' => 'required|numeric',
            'template' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $subcategory_id = Input::get('subcategory_id');

        $subcategory = File_Subcategory::findOrFail($subcategory_id);
        $subcategory->template = Input::get('template');
        $subcategory->save();

        return redirect()->back()->with(['The milestone template has been updated']);
    }
}
