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
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/pnotify.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            /*
             * SubCategory
             */
            var hot_checks_values_data = [];
            var subCategoriesData;
            var defaultData;
            var cellTable;

            function init() {
                var id = "{{ isset($subcategories) ? $subcategories[0]->subcategory_id : null }}";
                if (id) {
                    $.ajax({
                        type: "GET",
                        url: '{{ url("admin/files/subcategories") }}',
                        data: {"id": id},
                        dataType: 'json',
                        success: function (data) {
                            initializeSubCategory(data);
                            initTable(data, 0);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }
            init();

            $('#category').on("select2:select", function (event) {
                $.ajax({
                    type: "GET",
                    url: '{{ url("admin/files/subcategories") }}',
                    data: {"id": event.currentTarget.value},
                    dataType: 'json',
                    success: function (data) {
                        initializeSubCategory(data);
                        initTable(data, 0);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
            $('#subcategory').on("select2:select", function (event) {
                var id = event.currentTarget.value;
                $.each(subCategoriesData, function(index, value) {
                    if (id == value.id) {
                        initTable(subCategoriesData, index);
                    }
                });
            });

            function initializeSubCategory(data) {
                $('#subcategory').empty();
                $('#subcategory').select2({
                    minimumResultsForSearch: Infinity,
                    data: data,
                });
            }

            function initTable(data, index) {
                subCategoriesData = data;
                defaultData = data[index].data;
                $("#subcategory > option").each(function (index) { // iterate through all options of selectbox
                    $(this).attr('data-id', data[index].data); // add attribute to option with value of i
                });

                hot_checks_values_data = JSON.parse(defaultData);
                cellTable.destroy();
                create_template_table();
            }

            // Handson Table Setup
            // ------------------------------

            // Define element
            var tableContainer = document.getElementById('activity-table');

            // Initialize with options
            function create_template_table() {
                cellTable = new Handsontable(tableContainer, {
                    data: hot_checks_values_data,
                    rowHeaders: true,
                    colHeaders: ['Select', 'Activity Desc', 'Status', 'Price', 'Duration'],
                    manualColumnMove: true,
                    stretchH: 'all',
                    columns: [
                        {
                            data: 'select',
                            type: 'checkbox',
                            width: 20,
                        },
                        {
                            data: 'activity'
                        },
                        {
                            data: 'status',
                        }
                    ]
                });

                $('#case').val(JSON.stringify(hot_checks_values_data));
            }

            create_template_table();

            /**
             * Button Event
             */
            $('#btn_add').on('click', function () {
                hot_checks_values_data.push(
                        {status: "", select: true}
                );
                cellTable.destroy();
                create_template_table();
            });
            $('#btn_delete').on('click', function () {
                var jsonArr = [];
                $.each(hot_checks_values_data, function (index, item) {
                    if (!item.select) {
                        jsonArr.push(item);
                    }
                    hot_checks_values_data = jsonArr;
                    cellTable.destroy();
                    create_template_table();
                });
            });
            $('#btn_select_all').on('click', function(){
                $.each(hot_checks_values_data, function(index, item) {
                    item.select = true;
                    cellTable.destroy();
                    create_template_table();
                });
            });
            $('#btn_deselect_all').on('click', function(){
                $.each(hot_checks_values_data, function(index, item) {
                    item.select = false;
                    cellTable.destroy();
                    create_template_table();
                });
            });
            $('#btn_delete_all').on('click', function () {
                hot_checks_values_data = [];
                cellTable.destroy();
                create_template_table();
            });
            $('#btn_default').on('click', function(){
                hot_checks_values_data = JSON.parse(defaultData);
                cellTable.destroy();
                create_template_table();
            });


            // When Submit
            $('#form_template').on('submit', function (e) {
                e.preventDefault();
                if (hot_checks_values_data.length == 0) {
                    alert("You must add at least one activity");
                    return;
                }

                var data = cellTable.getData();
                var template = [];
                $.each(data, function(index, value) {
                    var dic = {no: index, activity: value[1], status: value[2]};
                    template.push(dic);
                });
                $('#template').val(JSON.stringify(template));

                var params = $(this).serialize();
                $.ajax({
                    url: "{{ url('admin/milestones') }}",
                    type: "POST",
                    data: params,
                    success: function(){
                        showNotify("Notice", "The milestone template is updated successfully");
                    },
                    error: function(){
                        showErrorNotify("Error", "Failed to update the milestone template");
                    }
                });
            });

            // Default select2 initialization
            $('.select').select2({
                minimumResultsForSearch: Infinity,
                placeholder: function(){
                    $(this).data('placeholder');
                }
            });

            function showNotify(title, text) {
                new PNotify({
                    title: title,
                    text: text,
                    addclass: 'bg-success'
                });
            }

            function showErrorNotify(title, text) {
                new PNotify({
                    title: title,
                    text: text,
                    addclass: 'bg-danger'
                });
            }
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Milestone Templates</h2>
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
            <div class="panel-body">
                <form id="form_template" class="form-horizontal" action="{{ url('admin/milestones') }}" method="post">
                    {{ csrf_field() }}

                    <fieldset class="content-group">
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
                                <select id="subcategory" class="select" name="subcategory_id">
                                    @if (isset($subcategories))
                                        @foreach($subcategories as $subcategory)
                                            <option value="{{ $subcategory->subcategory_id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="template" name="template">

                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-1 mt-20">
                                <div class="btn-group btn-group-justified">
                                    <div class="btn-group">
                                        <button id="btn_add" type="button" class="btn btn-default">Add</button>
                                    </div>

                                    <div class="btn-group">
                                        <button id="btn_delete" type="button" class="btn btn-default">Del</button>
                                    </div>

                                    <div class="btn-group">
                                        <button id="btn_select_all" type="button" class="btn btn-default">Select all</button>
                                    </div>

                                    <div class="btn-group">
                                        <button id="btn_deselect_all" type="button" class="btn btn-default">Deselect all</button>
                                    </div>

                                    <div class="btn-group">
                                        <button id="btn_delete_all" type="button" class="btn btn-default">Delete all</button>
                                    </div>

                                    <div class="btn-group">
                                        <button id="btn_default" type="button" class="btn btn-default">Default</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="hot-container" {{ isset($subcategory) ? "" : "hidden" }}>
                        <div id="activity-table"></div>
                    </div>

                    <div class="text-right mt-20">
                        <button type="submit" class="btn btn-primary">Save changes<i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection