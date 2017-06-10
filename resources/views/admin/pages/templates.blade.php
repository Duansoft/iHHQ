@extends("admin/admin_app")


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

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="{{ url('admin/templates') }}"><i class="icon-home2 position-left"></i> Legal Templates</a></li>
            </ul>

            <ul class="breadcrumb-elements">
                <li><a href="{{ url('admin/templates/create') }}"><i class="icon-add position-left"></i> New Template</a></li>
            </ul>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section("content")
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta name="_search" content="{{ url('admin/templates/get') }}"/>
    <meta name="_publicURL" content="{{ url('') }}"/>

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
            <table class="table datatable">
                <thead class="active alpha-grey">
                <tr>
                    <th>ID</th>
                    <th>Download</th>
                    <th>Path</th>
                    <th>File Name</th>
                    <th>Category</th>
                    <th>Created By</th>
                    <th>Date</th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
    <!-- /content area -->

    <!-- make payment modal -->
    <div id="modal_template" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-yellow-800">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Ticket</h5>
                </div>

                <div class="modal-body">
                    <form class="form" action="{{ url('admin/tickets/')}}" method="post">
                        {{ csrf_field() }}
                        <fieldset>

                        </fieldset>
                        <div class="text-right pt-10">
                            <button type="submit" class="btn btn-primary"> Save Changes<i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /make payment modal -->
@endsection