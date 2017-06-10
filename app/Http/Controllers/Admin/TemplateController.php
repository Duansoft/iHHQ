<?php

namespace App\Http\Controllers\Admin;

use App\File_Extension;
use App\Template;
use App\Template_Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return View('admin.pages.templates');
    }

    public function getTemplatesAjax()
    {
        $templates = DB::table('templates')
            ->select('template_id', 'file_extensions.icon AS extension', 'path', 'templates.name', 'template_categories.name AS category', 'templates.created_at', 'users.name AS created_by')
            ->leftJoin('users', 'users.id', 'templates.created_by')
            ->leftJoin('template_categories', 'template_categories.category_id', 'templates.category_id')
            ->leftJoin('file_extensions', 'file_extensions.id', 'templates.extension_id');

        return Datatables::of($templates)
            ->editColumn('created_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $created_at)->diffForHumans() !!}')
            ->make(true);
    }

    public function getCreateTemplate()
    {
        $template_categories = Template_Category::all();
        $file_extensions = File_Extension::all();

        return View('admin.pages.addEditTemplate', compact('template_categories', 'file_extensions'));
    }

    public function createTemplate(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'file' => 'required|file',//|mimes:pdf,docx,xls',
            'category_id' => 'required',
            'name' => 'required|max:255',
            'extension_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->messages());
        }

        $file = $request->file('file');
        $fileName = $request->get('name') . '.' . $file->getClientOriginalExtension();

        DB::beginTransaction();
        try {
            $path = $request->file('file')->storeAs('templates', $fileName);

            $template = New Template();
            $template->extension_id = $request->extension_id;
            $template->category_id = $request->category_id;
            $template->created_by = Auth::user()->id;
            $template->name = $fileName;
            $template->path = $path;
            $template->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to upload legal template']);
        }

        return redirect()->back()->with('flash_message', 'Template have been created successfully');
    }

    public function getTemplate($id)
    {
        $template = Template::findOrFail($id);
        $template_categories = Template_Category::all();
        $file_extensions = File_Extension::all();

        return View('admin.pages.addEditTemplate', compact('template', 'template_categories', 'file_extensions'));
    }

    public function postTemplate($id, Request $request)
    {
        $data = Input::all();
        $name = Input::get('name');

        $validator = Validator::make($data, [
            'category_id' => 'required|numeric',
            'name' => 'required|max:255',
            'extension_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with($validator->messages());
        }

        DB::beginTransaction();
        try {
            $template = Template::findOrFail($id);
            $template->extension_id = $request->extension_id;
            $template->category_id = $request->category_id;
            $template->created_by = Auth::user()->id;

            if (!$this->hasExtension($name)) {
                $fileName = $name . "." . $this->getExtension($template->name);
            } else {
                $fileName = $name;
            }

            if (Input::hasFile('file')) {
                Storage::delete($template->path);

                $path = $request->file('file')->storeAs('templates', $fileName);

                $template->name = $fileName;
                $template->path = $path;

            } else if ($name != $template->name) {
                $newPath = "templates/" . $fileName;
                Storage::move($template->path, $newPath);

                $template->name = $fileName;
                $template->path = $newPath;
            }

            $template->save();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['errors' => 'Failed to upload legal template']);
        }

        return redirect('admin/templates')->with('flash_message', 'The template have been updated successfully');
    }

    public function deleteTemplate($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return redirect('admin/templates')->with('flash_message', 'The Template have been deleted successfully');
    }

    public function download($id)
    {
        $template = Template::findOrFail($id);

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $template->path);
    }

}
