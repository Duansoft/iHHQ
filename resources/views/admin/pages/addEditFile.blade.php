@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/handsontable/handsontable.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
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
    <script type="text/javascript">

        $(function() {
            // trigger after selecting from ajax
            var hot_checks_values_data = [];
            var selectedData;
            var cellTable;

            $('#category').on("select2:select", function(event) {
                $.ajax({
                    type: "GET",
                    url: '{{ url("admin/files/subcategories") }}',
                    data: {"id": event.currentTarget.value},
                    dataType: 'json',
                    success: function (data) {
                        initializeSubCategory(data);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            // Default select initialization
            var subCategorySelect = $('#subcategory').select2({
                minimumResultsForSearch: Infinity
            });

            function initializeSubCategory(data) {
                subCategorySelect.empty();
                subCategorySelect.select2({
                    minimumResultsForSearch: Infinity,
                    data: data,
                });
                selectedData = data[0].data;
                $("#subcategory > option").each(function(index) { // iterate through all options of selectbox
                    $(this).attr('data-id', data[index].data); // add attribute to option with value of i
                });
            }

            $('#btn-load-template').on('click', function(){
                hot_checks_values_data = JSON.parse(selectedData);
                cellTable.destroy();
                create_template_table();
            });



            // Handson Table Setup
            // ------------------------------

            // Define element
            var hot_checks_values = document.getElementById('activity-table');

            // Initialize with options
            function create_template_table() {
                 cellTable = new Handsontable(hot_checks_values, {
                    data: hot_checks_values_data,
                    rowHeaders: true,
                    colHeaders: ['Select', 'Activity Desc', 'Status', 'Price', 'Duration'],
                    manualColumnMove: true,
                    stretchH: 'all',
                    columns: [
                        {
                            data: 'select',
                            type: 'checkbox',
                            width: 30
                        },
                        {
                            data: 'activity'
                        },
                        {
                            data: 'status',
                            width: 40
                        },
                        {
                            data: 'milestone',
                            type: 'numeric',
                            format: '0,0.00',
                            width: 40
                        },
                        {
                            data: 'duration',
                            width: 30
                        },
                    ]
                });

                $('#case').val(JSON.stringify(hot_checks_values_data));
            }

            create_template_table();

            $('#btn_add').on('click', function(){
                hot_checks_values_data.push(
                        {no: "10", status: "In Progress", activity: "", duration: 0, milestone: 0, select: true}
                );
                cellTable.destroy();
                create_template_table();
            });
            $('#btn_delete').on('click', function(){
                var jsonArr = [];
                $.each(hot_checks_values_data, function(index, item) {
                    if (item.select == false) {
                        jsonArr.push(item);
                    }
                    hot_checks_values_data = jsonArr;
                    cellTable.destroy();
                    create_template_table();
                });
            });
            $('#btn_delete_all').on('click', function(){
                hot_checks_values_data = [];
                cellTable.destroy();
                create_template_table();
            });


            // When Submit
            $('form').on('submit', function(e) {
                e.preventDefault();
                if (hot_checks_values_data.length == 0) {
                    alert("You must add a activity at least");
                } else {
                    $(this).submit();
                }
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2><span>{{isset($file->file_id) ? 'Edit File' : 'New File'}}</span></h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/files')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_searchUser" content="{{ url('admin/users/clients/search') }}"/>


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
                                                    <input type="text" class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly required>
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

                    <div class="row no-margin">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold">Category & Milestone</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Category</label>
                                        <div class="col-lg-10">
                                            <select id="category" class="select" name="category_id">
                                                @foreach($categories as $category)
                                                    @if (isset($file))
                                                        <option value="{{ $category->category_id }}" {{ $category->category_id == $file->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @else
                                                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Sub Category</label>
                                        <div class="col-lg-10">
                                            <select id="subcategory" class="select" name="subcategory_id"></select>
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <button id="btn-load-template" type="button" class="btn btn-default mr-10"><i class="icon-download7 position-left"></i> Load Milestone Template</button>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-8 col-lg-offset-2">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button id="btn_add" type="button" class="btn btn-default">Add</button>
                                                </div>

                                                <div class="btn-group">
                                                    <button id="btn_delete" type="button" class="btn btn-default">Del</button>
                                                </div>

                                                <div class="btn-group">
                                                    <button id="btn_delete_all" type="button" class="btn btn-default">Delete All</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="hot-container">
                                    <div id="activity-table"></div>
                                </div>

                                <input type="hidden" id="case" name="cases" value="">
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-20">
                        <button type="submit" class="btn btn-primary"> {{isset($file->file_id) ? 'Save Changes' : 'Open New File'}} <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /content area -->

    <!-- New Ticket Modal Dialog -->
    <div id="modal_select_template" class="modal fade modal-full">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Select Activities</h5>
                </div>

                <form id="create_ticket_form" class="form-horizontal" action="{{url('support/tickets/create')}}" method="post">
                    {{ csrf_field() }}

                    <fieldset class="ml-20 mr-20 p-10">
                        <div class="form-group">
                            <div class="hot-container">
                                <div id="activity_templates"></div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group bg-grey-F8FAFC no-margin p-10 text-grey-300">
                        <label class="control-label col-md-8">Messages are kept confidential</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success form-control">Create Ticket</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /New Ticket Modal Dialog -->
@endsection