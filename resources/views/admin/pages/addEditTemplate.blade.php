@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/uploader_bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/tickets.js') }}"></script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span class="text-semibold">Legal Templates</span></h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/templates')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <!-- Content area -->
    <div class="content col-lg-11">

        <!-- Error Message -->
        @if (count($errors) > 0)
        <div class="alert alert-danger no-border">
            <ul>
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                @foreach ($errors->all() as $error)
                    <li>
                        <span class="text-semibold">{{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Success Message -->
        @if(Session::has('flash_message'))
            <div class="alert alert-success no-border">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ Session::get('flash_message') }}</span>
            </div>
        @endif

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">{{ isset($template) ? "Edit Template" : "Create Template" }}</h5>
            </div>
            <div class="panel-body">
                @if(isset($template))
                    <form class="form-horizontal" action="{{url('admin/templates/' . $template->template_id)}}" method="post" enctype="multipart/form-data">
                @else
                    <form class="form-horizontal" action="{{url('admin/templates/create')}}" method="post" enctype="multipart/form-data">
                @endif
                    {{ csrf_field() }}

                    <fieldset class="content-group">
                        <div class="form-group">
                            <label class="control-label col-lg-2">Category</label>
                            <div class="col-lg-10">
                                <select class="select" name="category_id">
                                    @foreach($template_categories as $template_category)
                                        @if (isset($template))
                                        <option value="{{$template_category->category_id}}" {{ $template_category->category_id == $template->category_id ? 'selected' : '' }}>{{$template_category->name}}</option>
                                        @else
                                        <option value="{{$template_category->category_id}}">{{$template_category->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">File Type</label>
                            <div class="col-lg-10">
                                <select class="select" name="extension_id">
                                    @foreach($file_extensions as $file_extension)
                                        @if (isset($template))
                                        <option value="{{$file_extension->id}}" {{ $template->extension_id == $file_extension->id ? 'selected' : '' }}>{{$file_extension->name}}</option>
                                        @else
                                        <option value="{{$file_extension->id}}">{{$file_extension->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">File Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name" placeholder="file name" maxlength="100"
                                       value="{{ isset($template->name) ? $template->name : old('name') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Template File</label>
                            <div class="col-lg-10">
                                <input type="file" class="file-input" name="file" accept=".xlsx, .xls, .doc, .docx, .ppt, .pptx, .pdf" {{ isset($template) ? '' : 'required' }}>
                                <span>Format: pdf, doc, xls, ppt</span>
                            </div>
                        </div>
                    </fieldset>

                    <div class="text-right">
                        @if (isset($template))
                            <a href="{{ url('admin/templates/' . $template->template_id . '/delete') }}" onclick=""><button type="button" class="btn btn-danger">Delete<i class="icon-arrow-right14 position-right"></i></button></a>
                            <button type="submit" class="btn btn-primary">Save Changes<i class="icon-arrow-right14 position-right"></i></button>
                        @else
                            <button type="submit" class="btn btn-primary">Create Template<i class="icon-arrow-right14 position-right"></i></button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /content area -->
@endsection
