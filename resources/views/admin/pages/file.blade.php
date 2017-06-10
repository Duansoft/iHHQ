@extends("admin/admin_app")


@section("css")
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
                    width: '50px',
                    targets: [ 0 ]
                }, {
                    render: function ( data, type, row ) {
                        return '<a href="./files/' + row.file_id + '/detail">' + data + '</a>';
                    },
                    targets: 1,
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
                    {data: 'created_at', name: 'created_at'},
                ]
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>Files</h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="#"><i class="icon-home2 position-left"></i> Files</a></li>
            </ul>

            <ul class="breadcrumb-elements">
                <li><a href="{{ url('admin/files/create') }}"><i class="icon-add position-left"></i> New File</a></li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section("content")

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
            <table class="table datatable-basic">
                <thead class="active alpha-grey">
                <tr>
                    <th>ID</th>
                    <th>File Ref</th>
                    <th>Project Name</th>
                    <th>Date</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /content area -->
@endsection

