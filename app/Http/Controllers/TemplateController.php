<?php

namespace App\Http\Controllers;

use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        return View('pages.templates');
    }

    public function getTemplatesAjax()
    {
        $templates = DB::table('templates')
            ->select('template_id', 'file_extensions.icon AS extension', 'path', 'templates.name', 'template_categories.name AS category')
            ->leftJoin('template_categories', 'template_categories.category_id', 'templates.category_id')
            ->leftJoin('file_extensions', 'file_extensions.id', 'templates.extension_id');

        return Datatables::of($templates)
            ->make(true);
    }

    public function download($id)
    {
        $template = Template::findOrFail($id);

        return response()->download(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $template->path);
    }
}