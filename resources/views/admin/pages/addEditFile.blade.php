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

        String.prototype.replaceAll = function(search, replacement) {
            var target = this;
            return target.split(search).join(replacement);
        };

        $(function () {
            /*
             * SubCategory
             */
            var hot_checks_values_data = [];
            var subCategoriesData;
            var defaultData;

            var cellTable;
            var tableContainer = document.getElementById('activity-table');


            @if (isset($file))
                var rawString = "{{ $file->cases }}";
                var str = rawString.replaceAll('&quot;', '"')
                hot_checks_values_data = JSON.parse(str);
                defaultData = str;
                create_template_table();
            @else
                initialSubcategoryLoad();
                create_template_table();

                function initialSubcategoryLoad() {
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
            @endif


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
                            editor: 'select',
                            selectOptions: ['In Progress', 'Completed']
                        }
                    ]
                });

                $('#case').val(JSON.stringify(hot_checks_values_data));
            }

            /**
             * Button Event
             */
            $('#btn_add').on('click', function () {
                hot_checks_values_data.push({status: "", select: true});
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


            /**
             * Set File Ref
             */
            var file_ref = $('#file_ref').val();
            if (file_ref) {
                var refArr = file_ref.split('/');
                $('#file_ref1').val(refArr[0]);
                $('#file_ref2').val(refArr[1]);
                $('#file_ref3').val(refArr[2]);
            }

            // Select with search
            $('.select-search').select2({
                placeholder: "select staff",
            });

            $('.select-lawyer').select2({
                placeholder: "select lawyer",
            });


            //
            // Loading remote data
            //

            // Format displayed data
            function formatRepo (repo) {
                if (repo.loading) return repo.text;

                var markup = repo.name + " (" + repo.passport_no + ")";

                return markup;
            }

            // Format selection
            function formatRepoSelection (repo) {
                return repo.name || repo.passport_no;
            }

            function initSelectClient(values) {
                var selected = [];
                var initials = [];

                for (var s in values) {
                    var id = parseInt(values[s].id);
                    initials.push({id: id, name: values[s].name});
                    selected.push(id);
                }

                $('.select-remote-clients').select2({
                    data: initials,
                    ajax: {
                        url: $('meta[name="_searchUser"]').attr('content'),
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            }
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 50) < data.total_count
                                }
                            };
                        },
                        cache: true,
                    },
                    placeholder: "search user",
                    escapeMarkup: function (markup) { return markup; },
                    minimumInputLength: 1,
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection,
                });

                $('.select-remote-clients').val(selected).trigger('change');
            }

            function initSelectSpectator(values) {
                var selected = [];
                var initials = [];

                for (var s in values) {
                    var id = parseInt(values[s].id);
                    initials.push({id: id, name: values[s].name});
                    selected.push(id);
                }

                console.log(initials);

                $('.select-remote-spectators').select2({
                    data: initials,
                    ajax: {
                        url: $('meta[name="_searchUser"]').attr('content'),
                        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            }
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;

                            return {
                                results: data.results,
                                pagination: {
                                    more: (params.page * 50) < data.total_count
                                }
                            };
                        },
                        cache: true,
                    },
                    placeholder: "search user",
                    escapeMarkup: function (markup) { return markup; },
                    minimumInputLength: 1,
                    templateResult: formatRepo,
                    templateSelection: formatRepoSelection,
                });

                $('.select-remote-spectators').val(selected).trigger('change');
            }

            var clients = [];
            var spectators = [];

            @if (isset($file))
                @foreach($clients as $client)
                    clients.push({id: "{{ $client->id }}", name: "{{ $client->name }}"})
                @endforeach
                @foreach($spectators as $spectator)
                    spectators.push({id: "{{ $spectator->id }}", name: "{{ $spectator->name }}"})
                @endforeach
                initSelectClient(clients);
                initSelectSpectator(spectators);
            @else
                initSelectClient([]);
                initSelectSpectator([]);
            @endif

            /**
             * For Submit
             */
            $('#form_file').on('submit', function () {
                if (hot_checks_values_data.length == 0) {
                    alert("You must add at least one activity");
                    return false;
                }

                var data = cellTable.getData();
                var template = [];
                $.each(data, function(index, value) {
                    var dic = {no: index, activity: value[1], status: value[2]};
                    template.push(dic);
                });
                $('#template').val(JSON.stringify(template));

                // Set File Ref
                var file_ref = $('#file_ref1').val() + '/' + $('#file_ref2').val() + '/' + $('#file_ref3').val();
                $('#file_ref').val(file_ref);
                return true;
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
                <a href="{{url('admin/files')}}">
                    <button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button>
                </a>
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
                <div class="heading-elements">
                    <a href="{{ url('admin/users/clients/create') }}"><button type="button" class="btn btn-default heading-btn">Add New User</button></a>
                </div>
            </div>
            <div class="panel-body">
                <form id="form_file" class="form-horizontal" action="{{ isset($file) ? url('admin/files/' . $file->file_id) : url('admin/files/create') }}" method="post">
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
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <input id="file_ref1" type="text" class="form-control" required>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <input id="file_ref2" type="text" class="form-control" value="{{ isset($autoID) ? $autoID : "" }}" readonly required>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <input id="file_ref3" type="text" class="form-control" required>
                                                    </div>
                                                </div>
                                                @if (isset($file))
                                                    <input id="file_ref" type="hidden" class="form-control" name="file_ref" value="{{ $file->file_ref }}" readonly required>
                                                @else
                                                    <input id="file_ref" type="hidden" class="form-control" name="file_ref" value="{{ old('file_ref') }}" readonly required>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">File Name</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="project_name" value="{{ isset($file) ? $file->project_name : old('project_name') }}" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">File Type</label>
                                            <div class="col-lg-10">
                                                <select class="select1" name="department_id">
                                                    @foreach($file_types as $file_type)
                                                        @if (isset($file))
                                                            <option value="{{ $file_type->type_id }}" {{ $file_type->type_id == $file->type_id ? 'selected' : '' }}>{{ $file_type->name }}</option>
                                                        @else
                                                            <option value="{{ $file_type->type_id }}">{{ $file_type->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="content-group">
                                        <legend class="text-bold">Subject Matter</legend>
                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Matter</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="subject_matter" value="{{ isset($file) ? $file->subject_matter : old('subject_matter') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Description</label>
                                            <div class="col-lg-10">
                                                <textarea rows="3" cols="5" class="form-control"
                                                          name="subject_description">{{ isset($file) ? $file->subject_description : old('subject_description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Tags</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="tags" value="{{ isset($file) ? $file->tags : old('tags') }}" class="tagsinput-custom-tag-class">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Residential Address</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="residential_address" value="{{ isset($file) ? $file->residential_address : old('residential_address') }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Mailing Address</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="mailing_address" value="{{ isset($file) ? $file->mailing_address : old('mailing_address') }}">
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
                                                <select class="select-lawyer" name="lawyers[]" multiple>
                                                    @foreach($lawyers as $lawyer)
                                                        @if (isset($file))
                                                            <option value="{{ $lawyer->id }}"
                                                                @foreach($file->participants as $participant)
                                                                    @if ($participant->user_id == $lawyer->id)
                                                                        selected
                                                                    @endif
                                                                @endforeach
                                                             >{{ $lawyer->name }}</option>
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
                                                            <option value="{{ $staff->id }}"
                                                                @foreach($file->participants as $participant)
                                                                    @if ($participant->user_id == $staff->id)
                                                                    selected
                                                                    @endif
                                                                @endforeach
                                                            >{{ $staff->name }}</option>
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
                                                <select class="select-remote-spectators" name="spectators[]" multiple></select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-lg-2">Introducer</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control" name="introducer" value="{{ isset($file) ? $file->introducer : old('introducer') }}">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="content-group">
                                        <legend class="text-bold">Contact Person <span class="text-muted"> (optional)</span></legend>
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

                    <!-- /milestone area -->
                    @if(isset($file))
                    <div class="row no-margin">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold"> Milestone</legend>

                                    <div class="form-group">
                                        <div class="col-lg-10 col-lg-offset-1 mt-20">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button id="btn_add" type="button" class="btn btn-default">Add</button>
                                                </div>

                                                <div class="btn-group">
                                                    <button id="btn_select_all" type="button" class="btn btn-default">Select all</button>
                                                </div>

                                                <div class="btn-group">
                                                    <button id="btn_deselect_all" type="button" class="btn btn-default">Deselect all</button>
                                                </div>

                                                <div class="btn-group">
                                                    <button id="btn_delete" type="button" class="btn btn-default">Del</button>
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

                                <div class="hot-container">
                                    <div id="activity-table"></div>
                                </div>

                                <input type="hidden" id="case" name="cases" value="">
                            </div>
                        </div>
                    </div>
                    @else
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
                                            <select id="subcategory" class="select" name="subcategory_id">
                                                @foreach($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->subcategory_id }}">{{ $subcategory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

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

                                <div class="hot-container">
                                    <div id="activity-table"></div>
                                </div>

                                <input type="hidden" id="case" name="cases" value="">
                            </div>
                        </div>
                    </div>
                    @endif
                    <input type="hidden" id="template" name="cases" value="{{ isset($file) ? $file->cases : "" }}">
                    <!-- /milestone area -->

                    <div class="text-right mt-20">
                        <button type="submit" class="btn btn-primary"> {{isset($file->file_id) ? 'Save Changes' : 'Open New File'}} <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /content area -->

@endsection