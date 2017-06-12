@extends("admin/admin_app")


@section("css")
    <style>
        .datatable-header {
            display:none;
        }
    </style>
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/announcement.js') }}"></script>

    <script type="text/javascript">

        $(function() {

            // Table setup
            // ------------------------------
            $('.datatable-basic').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                columnDefs: [{
                    render: function (data, type, row) {
                        return '<a href="../admin/files/' + row.file_id + '/detail"><h6 class="no-margin" style="color:#000000">' + row.project_name + '<small class="display-block text-muted text-size-small">File Ref.' + row.file_ref + '</small></h6></a>';
                    },
                    targets: 0,
                },{
                    render: function ( data, type, row ) {
                        return '<span class="no-margin">' + row.updated_at + '<small class="display-block text-muted text-size-small">' + row.time_ago + '</small></span>';
                    },
                    targets: 1,
                },{
                    render: function ( data, type, row ) {
                        return '<span class="no-margin">RM' + row.billing + '<small class="display-block text-warning text-size-small">Due</small></span>';
                    },
                    targets: 2,
                },{
                    render: function ( data, type, row ) {
                        if (row.percent == 100) {
                            return '<span class="no-margin">Completed</span><div><div class="progress progress-rounded progress-xxs"><div class="progress-bar progress-bar-success" style="width: 100%"></div> </div> <small>100% Complete</small> </div>';
                        } else {
                            return '<span class="no-margin">Progress</span><div><div class="progress progress-rounded progress-xxs"><div class="progress-bar progress-bar-success" style="width: ' + row.percent + '%"></div> </div> <small>' + row.percent + '% Complete</small> </div>';
                        }
                    },
                    targets: 3,
                },{
                    targets: 7,
                    width: '150px',
                    orderable: false,
                    searchable: false,
                },{
                    targets: [4, 5, 6],
                    visible: false,
                    orderable: false,
                    searchable: false,
                }],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                ajax: '{{ url('admin/files') }}',
                "columns": [
                    {data: 'file_id', name: 'file_id'},
                    {data: 'file_ref', name: 'file_ref'},
                    {data: 'project_name', name: 'project_name'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'time_ago', name: 'updated_at'},
                    {data: 'billing', name: 'outstanding_amount'},
                    {data: 'percent', name: 'percent'},
                    {data: 'action', name: 'action'}
                ]
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            // Search Bar
            $('#search').on('keyup click', function () {
                $('.datatable-basic').DataTable().search(
                    $('#search').val()
                ).draw();
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Files</h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/files/create')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-add position-left"></i> New File</button></a>
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

        @if(Session::has('status'))
            <div class="alert alert-danger no-border">
                <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                @foreach (Session::pull('status') as $status)
                    <li>
                        <span class="text-semibold">{{ $status }}</span>
                    </li>
                @endforeach
            </div>
        @endif

        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">All Files
                    {{--<small class="ml-20 pl-20 border-left text-grey"></small>--}}
                </h3>
                <div class="heading-elements">
                    <form class="heading-form" action="#">
                        <div class="form-group has-feedback">
                            <input id="search" type="search" class="form-control" placeholder="Search by file ref, name or tags">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-size-base text-muted"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table datatable-basic">
                <thead class="active alpha-grey">
                <tr>
                    <th>File</th>
                    <th>Updated</th>
                    <th>Billing</th>
                    <th>Status</th>
                    <th>hidden</th>
                    <th>hidden</th>
                    <th>hidden</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /content area -->
@endsection

