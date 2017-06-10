@extends("app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/templates.js') }}"></script>
@endsection



@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2><span class="">Legal Templates</span></h2>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_search" content="{{ url('templates/get') }}"/>
    <meta name="_publicURL" content="{{ url('') }}"/>

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
            <table class="table datatable-client">
                <thead class="active alpha-grey">
                    <tr>
                        <th>ID</th>
                        <th>Download</th>
                        <th>Path</th>
                        <th>Category</th>
                        <th>Name</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /content area -->
@endsection