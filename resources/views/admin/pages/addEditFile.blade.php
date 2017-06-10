@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/anytime.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/tags/tagsinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/tags/tokenfield.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/ui/prism.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/file.js') }}"></script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2><span>{{isset($file->file_id) ? 'Edit File' : 'New File'}}</span></h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="{{ url('admin/files') }}"><i class="icon-home2 position-left"></i> Files</a></li>
                <li class="active">{{isset($file->file_id) ? 'Edit File' : 'New File'}}</li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_searchUser" content="{{ url('admin/users/clients/search') }}"/>


    <!-- Content area -->
    <div class="content">

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
                <h4>File Information</h4>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="{{ url('admin/files/create') }}" method="post">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <fieldset class="content-group">
                                        <legend class="text-bold">General</legend>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">File Ref</label>
                                            <div class="col-lg-10">
                                                @if (isset($file))
                                                    <input type="text" class="form-control" name="file_ref" value="{{ $file->file_ref }}" disabled required>
                                                @else
                                                    <input type="text" class="form-control" name="file_ref" value="{{ old('file_ref') }}" required>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Project Name</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="project_name" value="{{ isset($file) ? $file->project_name : old('project_name') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">File Type</label>
                                            <div class="col-lg-10">
                                                <select class="select1" name="department_id">
                                                    @foreach($departments as $department)
                                                        @if (isset($file))
                                                        <option value="{{ $department->department_id }}" {{ $department->department_id == $file->department_id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                                        @else
                                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" name="category_id" value="4" hidden>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Sub Category</label>
                                            <div class="col-lg-10">
                                                <select class="select" name="subcategory_id">
                                                    @foreach($subcategories as $subcategory)
                                                        @if (isset($file))
                                                        <option value="{{ $subcategory->subcategory_id }}" {{ $subcategory->subcategory_id == $file->subcategory_id ? 'selected' : '' }}>{{ $subcategory->title }}</option>
                                                        @else
                                                        <option value="{{ $subcategory->subcategory_id }}">{{ $subcategory->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-lg-2">Date</label>--}}
                                            {{--<div class="col-lg-10">--}}
                                                {{--<input type="text" class="form-control daterange-basic">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}

                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-lg-2">Status</label>--}}
                                            {{--<div class="col-lg-10">--}}
                                                {{--<select class="select">--}}
                                                    {{--<option value="0">Active</option>--}}
                                                    {{--<option value="1">Close</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </fieldset>

                                    <fieldset class="content-group">
                                        <legend class="text-bold">Subject Matter</legend>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Matter</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="subject_matter" value="{{ isset($file) ? $file->subject_matter : old('subject_matter') }}" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Description</label>
                                            <div class="col-lg-10">
                                                <textarea rows="3" cols="5" class="form-control" name="subject_description">{{ isset($file) ? $file->subject_description : old('subject_description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Tags</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="tags" value="{{ isset($file) ? $file->tags : old('tags') }}" class="tagsinput-custom-tag-class">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <fieldset class="content-group">
                                        <legend class="text-bold">Particulars</legend>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2" data-placeholder="select lawyer">Lawyer</label>
                                            <div class="col-lg-10">
                                                <select class="select-search" name="lawyers[]" multiple>
                                                    @foreach($lawyers as $lawyer)
                                                        @if (isset($file))
                                                        <option value="{{ $lawyer->id }}" >{{ $lawyer->name }}</option>
                                                        @else
                                                        <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2" data-placeholder="select staff">staff</label>
                                            <div class="col-lg-10">
                                                <select class="select-search" name="staffs[]" multiple>
                                                    @foreach($staffs as $staff)
                                                        @if (isset($file))
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                        @else
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Clients</label>
                                            <div class="col-lg-10">
                                                <select class="select-remote-clients" name="clients[]" multiple></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Spectators</label>
                                            <div class="col-lg-10">
                                                <select class="select-remote-clients" name="spectators[]" multiple></select>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="content-group">
                                        <legend class="text-bold">Contact Person <span class="text-muted"> (optional)</legend>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Name</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="contact_name" value="{{ isset($file) ? $file->contact_name : old('contact_name') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Contact</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="contact" value="{{ isset($file) ? $file->contact : old('contact') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Email</label>
                                            <div class="col-lg-10">
                                                <input type="email" class="form-control" name="contact_email" value="{{ isset($file) ? $file->contact_email : old('contact_email') }}">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary"> {{isset($file->file_id) ? 'Save Changes' : 'Open New File'}} <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /content area -->
@endsection