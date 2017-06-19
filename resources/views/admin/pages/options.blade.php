@extends("admin.admin_app")


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
    <script type="text/javascript">
        $(function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).text();  // activated tab
                $('.panel-title').text(target);
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
                                                    <li><a href="#"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a href="#"><i class="icon-cross2 text-danger"></i> Delete</a></li>
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
                                @foreach($departments as $department)
                                    <tr>
                                        <td class="text-center">
                                            <h6 class="no-margin">{{$department->department_id}}</h6>
                                        </td>
                                        <td>
                                            <span>{{$department->department_name}}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-fade">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a href="#"><i class="icon-cross2 text-danger"></i> Delete</a></li>
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
                                                    <li><a href="#"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a href="#"><i class="icon-cross2 text-danger"></i> Delete</a></li>
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
                                                    <li><a href="#"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a href="#"><i class="icon-cross2 text-danger"></i> Delete</a></li>
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
                                                    <li><a href="#"><i class="icon-checkmark3 text-success"></i> Edit</a></li>
                                                    <li><a href="#"><i class="icon-cross2 text-danger"></i> Delete</a></li>
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

@endsection