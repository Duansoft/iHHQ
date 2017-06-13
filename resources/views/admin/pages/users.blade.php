@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/users.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            // Table setup
            // ------------------------------
            $('.datatable-basic').DataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                columnDefs: [{
                    width: '80px',
                    targets: [0]
                },{
                    render: function ( data, type, row ) {
                        if (row.is_allow == 1) {
                            return '<span class="label label-success"> YES</span>';
                        } else {
                            return '<span class="label bg-dashboard-user"> NO</span>';
                        }
                    },
                    targets: 5,
                }, {
                    orderable: false,
                    width: '100px',
                    targets: [6]
                }],
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                ajax: '{{ url('admin/users') }}',
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'display_name', name: 'roles.display_name'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'passport_no', name: 'passport_no'},
                    {data: 'is_allow', name: 'is_allow', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            /**
             * Add New User
             */
            $('#add_lawyer').on('click', function (event) {
                event.preventDefault();
                showNewUserCreationDialog('./client');
            });
            $('#add_staff').on('click', function (event) {
                event.preventDefault();
                showNewUserCreationDialog('./client');
            });
            $('#add_client').on('click', function (event) {
                event.preventDefault();
                showNewUserCreationDialog('./client');
            });
            function showNewUserCreationDialog(url) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'text',
                    success: function (data) {
                        $('#modal_user').html(data);
                        $('#modal_user').show();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }

            // process the form
            $('form').submit(function (event) {
                // get the form data
                // there are many ways to get this data using jQuery (you can use the class or id also)
                var formData = {
                    'name': $('input[name=name]').val(),
                    'email': $('input[name=email]').val(),
                    'superheroAlias': $('input[name=superheroAlias]').val()
                };

                // process the form
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: 'process', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true
                    })
                    .done(function (data) {
                        if (data.success) {
                            alert('success');
                        } else {
                            $('#modal_user').html(data);
                            $('#modal_user').show();
                        }
                    });
                event.preventDefault();
            });
        });
    </script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Users</h2>
            </div>

            <div class="heading-elements">
                <div class="btn-group heading-btn">
                    <button class="btn btn-default btn-icon dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-add position-left"></i>
                        Add User
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ url('admin/users/admins/create/') }}"> Super Admin</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('admin/users/lawyers/create/') }}"> Lawyer</a></li>
                        <li><a href="{{ url('admin/users/staffs/create/') }}"> Legal Staff</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('admin/users/clients/create/') }}"> Client</a></li>
                    </ul>
                </div>
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
            <table class="table datatable-basic">
                <thead class="active alpha-grey">
                <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Passport</th>
                    <th>Approve</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /content area -->

    <!-- Add Edit User Modal Dialog -->
    <div id="modal_user" class="modal fade">
    </div>
    <!-- /Add Edit User Modal Dialog -->
@endsection

