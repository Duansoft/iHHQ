@extends("admin.admin_app")

@section("css")
    <style>
        .dataTables_filter {
            display:none;
        }
    </style>
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/velocity/velocity.ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/spin.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/buttons/ladda.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/overview.js') }}"></script>
@endsection


@section("page-header")
@endsection


@section("content")
    <!-- Content area -->
    <div class="content no-padding-top">

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

        <!-- Highlighted tabs -->
        <div class="row">
            <div class="col-md-9">
                <div class="page-title">
                    <h2>Hi,<span class="text-warning text-capitalize"> {{ Auth::user()->name }}</span></h2>
                </div>
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h3 class="panel-title">My Files
                            <small class="ml-20 pl-20 border-left text-grey">{{sizeof($files)}} active</small>
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

                    <table class="table table-overview">
                        <thead class="active alpha-grey">
                        <tr>
                            <th>File</th>
                            <th>Updated</th>
                            <th>Billing</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>
                                    <h6 class="no-margin">{{$file->project_name}}
                                        <small class="display-block text-muted text-size-small">File Ref. {{$file->file_ref}}</small>
                                    </h6>
                                </td>
                                <td>
                                    <span class="no-margin">
                                        {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->updated_at)->toFormattedDateString() !!}
                                        <small class="display-block text-muted text-size-small">
                                            {!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $file->updated_at)->diffForHumans() !!}
                                        </small>
                                    </span>
                                </td>
                                <td>
                                    <span class="no-margin">RM{{$file->outstanding_amount - $file->paid_amount}}<small class="display-block text-warning text-size-small">Due</small></span>
                                </td>
                                <td>
                                    @if ($file->percent == 100)
                                        <span class="no-margin">Completed</span>
                                        <div>
                                            <div class="progress progress-rounded progress-xxs">
                                                <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                                            </div>
                                            <small>100% Complete</small>
                                        </div>
                                    @else
                                        <span class="no-margin">Progress</span>
                                        <div>
                                            <div class="progress progress-rounded progress-xxs">
                                                <div class="progress-bar progress-bar-success" style="width: {!! $file->percent !!}%"></div>
                                            </div>
                                            <small>{{ $file->percent }}% Complete</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-fade">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Actions <span class="caret pl-15"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#" data-toggle="modal" data-target="#modal_make_payment">Create</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#modal_request_payment">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="col-md-3">
                <div class="page-title">
                    <h3>News & Announcement</h3>
                </div>
                @if(count($announcements) > 0)
                    <div class="panel">
                        <div class="panel-body no-padding">
                            <div class="list-group no-padding no-border">
                                @foreach($announcements as $announcement)
                                    <a class="list-group-item">
                                        <div class="list-group-item-heading">
                                            <h6><i class="icon-mail-read text-success"></i><span class="pl-20">{{ $announcement->title }}</span></h6>
                                        </div>
                                        <div class="list-group-item-text">
                                            <span class="text-grey">{{ $announcement->content }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            {{-- FAG Announcement --}}
                            {{--<div class="list-group-divider"></div>--}}
                            {{--<div class = "list-group no-padding no-border">--}}
                            {{--<a class = "list-group-item">--}}
                            {{--<div class="list-group-item-heading">--}}
                            {{--<h6>Are there any fees for online payment?</h6>--}}
                            {{--</div>--}}
                            {{--<div class="list-group-item-text">--}}
                            {{--<span class = "text-grey">If I change my avatar will I need to do the same on my phone?</span>--}}
                            {{--<h6 class="text-success">Visit FAQ Page</h6>--}}
                            {{--</div>--}}
                            {{--</a>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- /highlighted tabs -->

    </div>
    <!-- /content area -->
@endsection

