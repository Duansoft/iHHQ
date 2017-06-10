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
            @if(isset($isActive))
                var url = '{{ url('admin/announcements/get') }}';
            @else
                var url = '{{ url('admin/announcements/close/get') }}';
            @endif

            // Table setup
            // ------------------------------
            var table = $('.datatable-basic').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                columnDefs: [{
                    orderable: true,
                    width: '20px',
                    targets: [ 0 ]
                },{
                    "render": function ( data, type, row ) {
                        @if(isset($isActive))
                            return '<a href="./announcements/' + row.announcement_id + '">' + data + '</a>';
                        @else
                                return '<a href="../announcements/' + row.announcement_id + '">' + data + '</a>';
                        @endif
                    },
                    targets: 1,
                }],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                },
                ajax: {
                    url: url,
                },
                "columns": [
                    {data: 'announcement_id', name: 'announcement_id'},
                    {data: 'title', name: 'title'},
                    {data: 'content', name: 'content'},
                    {data: 'created_at', name: 'created_at'},
                ],
                "order": [[ 3, "desc" ]]
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
                <h2><span class="">Announcement</span></h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="#"><i class="icon-home2 position-left"></i> Announcements</a></li>
            </ul>

            <ul class="breadcrumb-elements">
                <li><a href="{{ url('admin/announcements/create') }}"><i class="icon-add position-left"></i> New Announcement</a></li>
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

        <!-- row area -->
        <div class="row">
            <div class="col-lg-3">
                <!-- Navigation -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Announcements</h6>
                    </div>

                    <div class="list-group no-border mb-5">
                        @if(isset($isActive))
                            <a href="{{ url('admin/announcements/') }}" class="list-group-item active"><i class="icon-lifebuoy"></i> Active Announcements<span class="badge badge-success pull-right">{{ $activeCount }}</span></a>
                            <a href="{{ url('admin/announcements/close') }}" class="list-group-item"><i class="icon-close2"></i> Closed Announcements<span class="badge badge-default pull-right">{{ $inactiveCount }}</span></a>
                        @else
                            <a href="{{ url('admin/announcements/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Announcements<span class="badge badge-success pull-right">{{ $activeCount }}</span></a>
                            <a href="{{ url('admin/announcements/close') }}" class="list-group-item active"><i class="icon-close2"></i> Closed Announcements<span class="badge badge-default pull-right">{{ $inactiveCount }}</span></a>
                        @endif
                    </div>
                </div>
                <!-- /navigation -->
            </div>

            <div class="col-lg-9">
                <div class="panel panel-flat">
                    <table class="table datatable-basic">
                        <thead class="active alpha-grey">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Create Date</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
@endsection

