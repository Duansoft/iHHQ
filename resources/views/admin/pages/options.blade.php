@extends("admin.admin_app")


@section("css")
    <style>
        .datatable-header {
            display:none;
        }
    </style>
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/handsontable/handsontable.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/logistics.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            var target = "Office";
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                target = $(e.target).text();  // activated tab
                $('.panel-title').text(target);
            });

            $('#btn_add').on('click', function(){
                if (target.trim() == 'Office') {
                    $('#office_name').val("");
                    $('#office_location').val("");
                    $('#submit_office').text("Create");
                    $('#modal_office form').attr('action', "{{url('admin/options/offices')}}");
                    $('#modal_office').modal('show');
                } else if (target.trim() == 'File Type') {
                    $('#file_type').val("");
                    $('#modal_file_type .btn').text("Create");
                    $('#modal_file_type form').attr('action', "{{url('admin/options/file_types')}}");
                    $('#modal_file_type').modal('show');
                } else if (target.trim() == "Category") {
                    $('#modal_category input').val("");
                    $('#modal_category .btn-success').text("Create");
                    $('#modal_category form').attr('action', "{{url('admin/options/categories')}}");
                    $('#modal_category').modal('show');
                } else if (target.trim() == "Sub Category") {
                    $('#modal_subcategory input').val("");
                    $('#modal_subcategory .btn-success').text("Create");
                    $('#modal_subcategory form').attr('action', "{{url('admin/options/subcategories')}}");
                    $('#modal_subcategory').modal('show');
                } else if (target.trim() == "Courier") {
                    $('#modal_courier input').val("");
                    $('#modal_courier .btn-success').text("Create");
                    $('#modal_courier form').attr('action', "{{url('admin/options/ticket_categories')}}");
                    $('#modal_courier').modal('show');
                } else if (target.trim() == "Ticket Category") {
                    $('#file_type').val("");
                    $('#modal_ticket_category input').val("");
                    $('#modal_ticket_category .btn').text("Create");
                    $('#modal_ticket_category form').attr('action', "{{url('admin/options/ticket_categories')}}");
                    $('#modal_ticket_category').modal('show');
                } else if (target.trim() == "Legal Template Category") {
                    $('#file_type').val("");
                    $('#modal_template_category input').val("");
                    $('#modal_template_category .btn').text("Create");
                    $('#modal_template_category form').attr('action', "{{url('admin/options/template_categories')}}");
                    $('#modal_template_category').modal('show');
                }
            });

            // Basic File Input
            $('.file-input').fileinput({
                browseLabel: 'Browse',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                browseClass: 'btn btn-default',
                showUpload: false,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No file selected"
            });

            // Edit Office
            $('.btn_office_edit').on('click', function(e){
                e.preventDefault();
                $('#office_name').val($(this).data('name'));
                $('#office_location').val($(this).data('location'));
                $('#submit_office').text("Update");
                $('#modal_office form').attr('action', $(this).data('url'));
                $('#modal_office').modal('show');
            });

            // Delete Office
            $('.btn_office_delete').on('click', function(e){
                e.preventDefault();
                $('#office_name').val($(this).data('name'));
                $('#office_location').val($(this).data('location'));
                $('#submit_office').text("Delete");
                $('#modal_office form').attr('action', $(this).data('url'));
                $('#modal_office').modal('show');
            });

            // Edit File Type
            $('.btn_file_type_edit').on('click', function(e){
                e.preventDefault();
                $('#file_type').val($(this).data('name'));
                $('#modal_file_type .btn').text("Update");
                $('#modal_file_type form').attr('action', $(this).data('url'));
                $('#modal_file_type').modal('show');
            });
            // Delete File Type
            $('.btn_file_type_delete').on('click', function(e){
                e.preventDefault();
                $('#file_type').val($(this).data('name'));
                $('#modal_file_type .btn').text("Delete");
                $('#modal_file_type form').attr('action', $(this).data('url'));
                $('#modal_file_type').modal('show');
            });

            // Edit File Category
            $('.btn_category_edit').on('click', function(e){
                e.preventDefault();
                $('#modal_category #company_name').val($(this).data('name'));
                $('#modal_category .btn-success').text("Update");
                $('#modal_category form').attr('action', $(this).data('url'));
                $('#modal_category').modal('show');
            });
            // Delete File Category
            $('.btn_category_delete').on('click', function(e){
                e.preventDefault();
                $('#modal_category #company_name').val($(this).data('name'));
                $('#modal_category .btn-success').text("Delete");
                $('#modal_category form').attr('action', $(this).data('url'));
                $('#modal_category').modal('show');
            });

            // Edit File Subcategory
            $('.btn_subcategory_edit').on('click', function(e){
                e.preventDefault();
                $('#modal_subcategory #company_name').val($(this).data('name'));
                $('#modal_subcategory .btn-success').text("Update");
                $('#modal_subcategory form').attr('action', $(this).data('url'));
                $('#modal_subcategory').modal('show');
            });
            // Delete File Category
            $('.btn_subcategory_delete').on('click', function(e){
                e.preventDefault();
                $('#modal_subcategory #company_name').val($(this).data('name'));
                $('#modal_subcategory .btn-success').text("Delete");
                $('#modal_subcategory form').attr('action', $(this).data('url'));
                $('#modal_subcategory').modal('show');
            });

            // Edit Ticket Category
            $('.btn_ticket_category_edit').on('click', function(e){
                e.preventDefault();
                $('#modal_ticket_category input').val($(this).data('name'));
                $('#modal_ticket_category .btn').text("Update");
                $('#modal_ticket_category form').attr('action', $(this).data('url'));
                $('#modal_ticket_category').modal('show');
            });
            // Delete Ticket Category
            $('.btn_ticket_category_delete').on('click', function(e){
                e.preventDefault();
                $('#modal_ticket_category input').val($(this).data('name'));
                $('#modal_ticket_category .btn').text("Delete");
                $('#modal_ticket_category form').attr('action', $(this).data('url'));
                $('#modal_ticket_category').modal('show');
            });

            // Edit Template Category
            $('.btn_template_category_edit').on('click', function(e){
                e.preventDefault();
                $('#modal_template_category input').val($(this).data('name'));
                $('#modal_template_category .btn').text("Update");
                $('#modal_template_category form').attr('action', $(this).data('url'));
                $('#modal_template_category').modal('show');
            });
            // Delete Ticket Category
            $('.btn_template_category_delete').on('click', function(e){
                e.preventDefault();
                $('#modal_template_category input').val($(this).data('name'));
                $('#modal_template_category .btn').text("Delete");
                $('#modal_template_category form').attr('action', $(this).data('url'));
                $('#modal_ticket_category').modal('show');
            });

            // Edit Courier
            $('.btn_courier_edit').on('click', function(e){
                e.preventDefault();
                $('#modal_courier #company_name').val($(this).data('name'));
                $('#modal_courier .btn-success').text("Update");
                $('#modal_courier form').attr('action', $(this).data('url'));
                $('#modal_courier').modal('show');
            });
            // Delete Courier
            $('.btn_courier_delete').on('click', function(e){
                e.preventDefault();
                $('#modal_courier #company_name').val($(this).data('name'));
                $('#modal_courier .btn-success').text("Delete");
                $('#modal_courier form').attr('action', $(this).data('url'));
                $('#modal_courier').modal('show');
            });

            /*
             * SubCategory
             */
            var hot_checks_values_data = [];
            var selectedData;
            var cellTable;

            $('#category').on("select2:select", function (event) {
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

            function initializeSubCategory(data) {
                subCategorySelect.empty();
                subCategorySelect.select2({
                    minimumResultsForSearch: Infinity,
                    data: data,
                });
                selectedData = data[0].data;
                $("#subcategory > option").each(function (index) { // iterate through all options of selectbox
                    $(this).attr('data-id', data[index].data); // add attribute to option with value of i
                });

                hot_checks_values_data = JSON.parse(selectedData);
                cellTable.destroy();
                create_template_table();
            }


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
                        },
                        {
                            data: 'activity'
                        },
                        {
                            data: 'status',
                        },
                        {
                            data: 'milestone',
                            type: 'numeric',
                            format: '0,0.00',
                        },
                        {
                            data: 'duration',
                        },
                    ]
                });

                $('#case').val(JSON.stringify(hot_checks_values_data));
            }

            create_template_table();

            $('#btn_add').on('click', function () {
                hot_checks_values_data.push(
                        {no: "10", status: "In Progress", activity: "", duration: 0, milestone: 0, select: true}
                );
                cellTable.destroy();
                create_template_table();
            });
            $('#btn_delete').on('click', function () {
                var jsonArr = [];
                $.each(hot_checks_values_data, function (index, item) {
                    if (item.select == false) {
                        jsonArr.push(item);
                    }
                    hot_checks_values_data = jsonArr;
                    cellTable.destroy();
                    create_template_table();
                });
            });
            $('#btn_delete_all').on('click', function () {
                hot_checks_values_data = [];
                cellTable.destroy();
                create_template_table();
            });

            // When Submit
            $('#form_file').on('submit', function (e) {
                e.preventDefault();
                $('#file_ref').val($('#file_ref1').val() + '/' + $('#file_ref2').val() + '/' + $('#file_ref3').val());
                if (hot_checks_values_data.length == 0) {
                    alert("You must add at least one activity");
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
                <h2>Options</h2>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")
<meta name="_token" content="{!! csrf_token() !!}"/>

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
            <h6 class="panel-title">Office</h6>
            <div class="heading-elements">
                <button id="btn_add" type="button" class="btn btn-default heading-btn">Add New</button>
            </div>
        </div>
        <div class="panel-body">
            <div class="tabbable nav-tabs-vertical nav-tabs-left">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="active"><a href="#tab-office" data-toggle="tab"> Office</a></li>
                    <li><a href="#tab-department" data-toggle="tab"> File Type</a></li>
                    <li><a href="#tab-category" data-toggle="tab"> Category</a></li>
                    <li><a href="#tab-sub-category" data-toggle="tab"> Sub Category</a></li>
                    <li><a href="#tab-courier" data-toggle="tab"> Courier</a></li>
                    <li><a href="#tab-ticket-category" data-toggle="tab"> Ticket Category</a></li>
                    <li><a href="#tab-template-category" data-toggle="tab"> Legal Template Category</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab-office">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px">ID</th>
                                    <th>Office Name</th>
                                    <th>Location</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($offices as $office)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$office->office_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{$office->name}}</span>
                                        </td>
                                        <td>
                                            <span>{{$office->location}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_office_edit" data-name="{{$office->name}}" data-location="{{$office->location}}" data-url="{{ url('admin/options/offices/' . $office->office_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_office_delete" data-name="{{$office->name}}" data-location="{{$office->location}}" data-url="{{ url('admin/options/offices/' . $office->office_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-department">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($file_types as $file_type)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$file_type->type_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{$file_type->name}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_file_type_edit" data-name="{{ $file_type->name }}" data-url="{{ url('admin/options/file_types/' . $file_type->type_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_file_type_delete" data-name="{{ $file_type->name }}" data-url="{{ url('admin/options/file_types/' . $file_type->type_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-category">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$category->category_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{$category->name}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_category_edit" data-name="{{$category->name}}" data-url="{{ url('admin/options/categories/' . $category->category_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_category_delete" data-name="{{$category->name}}" data-url="{{ url('admin/options/categories/' . $category->category_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-sub-category">
                        <div class="row mb-10">
                            <div class="form-group">
                                <label class="control-label col-lg-2">Category</label>
                                <div class="col-lg-10">
                                    <select id="category" class="select form-control" name="category_id">
                                        @foreach($categories as $category)
                                            <option value="{{$category->category_id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$category->category_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{$category->name}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_category_edit" data-name="{{$category->name}}" data-url="{{ url('admin/options/categories/' . $category->category_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_category_delete" data-name="{{$category->name}}" data-url="{{ url('admin/options/categories/' . $category->category_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-courier">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th style="width: 100px"> Logo</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($couriers as $courier)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$courier->courier_id}}</h6>
                                        </td>
                                        <td>
                                            <img class="img-lg img-rounded" src="{{url($courier->logo)}}">
                                        </td>
                                        <td>
                                            <span>{{ $courier->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_courier_edit" data-name="{{$courier->name}}" data-logo="{{url($courier->logo)}}" data-url="{{ url('admin/options/couriers/' . $courier->courier_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_courier_delete" data-name="{{$courier->name}}" data-logo="{{url($courier->logo)}}" data-url="{{ url('admin/options/couriers/' . $courier->courier_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-ticket-category">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ticket_categories as $ticket_category)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$ticket_category->category_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{ $ticket_category->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_ticket_category_edit" data-name="{{ $ticket_category->name }}" data-url="{{ url('admin/options/ticket_categories/' . $ticket_category->category_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_ticket_category_delete" data-name="{{ $ticket_category->name }}" data-url="{{ url('admin/options/ticket_categories/' . $ticket_category->category_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-template-category">
                        <div class="panel">
                            <table class="table text-nowrap">
                                <thead>
                                <tr class="active">
                                    <th style="width: 50px"> ID</th>
                                    <th> Name</th>
                                    <th class="text-center" style="width: 50px;">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($template_categories as $template_category)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$template_category->category_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{ $template_category->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="btn_template_category_edit" data-name="{{ $template_category->name }}" data-url="{{ url('admin/options/template_categories/' . $template_category->category_id) }}"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a class="btn_template_category_delete" data-name="{{ $template_category->name }}" data-url="{{ url('admin/options/template_categories/' . $template_category->category_id . '/delete') }}"><i class="icon-cross2 text-danger"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New office Modal Dialog -->
<div id="modal_office" class="modal fade modal-full">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Office</h5>
            </div>

            <form id="upload_form" action="{{ url('admin/options/offices') }}" method="post">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Office Name</label>
                        <input type="text" id="office_name" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Office Location</label>
                        <input type="text" id="office_location" placeholder="" name="location" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button id="submit_office" type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /New office Modal Dialog -->

<!-- New File Type modal -->
<div id="modal_file_type" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> File Type</h5>
            </div>

            <form id="upload_form" action="#" method="post">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>File Type</label>
                        <input id="file_type" type="text" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /New File Type modal -->

<!-- File Category modal -->
<div id="modal_category" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Category</h5>
            </div>

            <form id="upload_form" method="post">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /File Category modal -->

<!-- File Category modal -->
<div id="modal_subcategory" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Subcategory</h5>
            </div>

            <form id="upload_form" method="post">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Subcategory Name</label>
                        <input type="text" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /File Category modal -->

<!-- New Courier modal -->
<div id="modal_courier" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Service Provider</h5>
            </div>

            <form id="upload_form" action="#" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input id="company_name" type="text" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Company Logo</label>
                        <input type="file" class="file-input" name="logo" accept=".png, .jpg" data-show-caption="true">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /New Courier modal -->

<!-- Ticket Category modal -->
<div id="modal_ticket_category" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Ticket Category</h5>
            </div>

            <form id="upload_form" method="post">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" placeholder="" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control"> Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Ticket Category modal -->

<!-- Legal Template Category modal -->
<div id="modal_template_category" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-yellow-800">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"> Legal Template Category</h5>
            </div>

            <form id="upload_form" action="#" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" placeholder="" name="category" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success form-control">Create Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Legal Template Category modal -->

@endsection