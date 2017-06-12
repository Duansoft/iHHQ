@extends("app")

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
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/logistics.js') }}"></script>
@endsection


@section("page-header")
<!-- Page header -->
<div class="page-header">

    <!-- Header content -->
    <div class="page-header-content">
        <div class="page-title">
            <h2>Logistics</h2>
        <div>
    </div>
    <!-- /header content -->
</div>
    </div>
</div>
<!-- /page header -->
@endsection


@section("content")

    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_publicURL" content="{{ url('') }}"/>
    <meta name="_search" content="{{ url('logistics/get') }}"/>

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

        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title">Tracking
                    {{--<small class="ml-20 pl-20 border-left text-grey">3</small>--}}
                </h3>
            </div>
            <table class="table datatable-client">
                <thead class="active alpha-grey">
                <tr>
                    <th>Delivery By</th>
                    <th>hidden</th>
                    <th>hidden</th>
                    <th>Receiver & Description</th>
                    <th>hidden</th>
                    <th>File Ref</th>
                    <th>Last Update</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- /highlighted tabs -->
        <span class="text-grey text-italic pl-10">Note: All packages received more than 30 days ago will not be displayed in results.</span>
    </div>
@endsection

