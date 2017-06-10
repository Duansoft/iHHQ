@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/announcement.js') }}"></script>
    @endsection


    @section("page-header")
            <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2><span>Announcements</span></h2>
            </div>
        </div>

        <div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb">
                <li><a href="{{ url('admin/announcements') }}"><i class="icon-home2 position-left"></i> Announcements</a></li>
                <li class="active">{{isset($announcement) ? "Edit" : "Create" }}</li>
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
                        <a href="{{ url('admin/announcements/') }}" class="list-group-item"><i class="icon-lifebuoy"></i> Active Announcements<span class="badge badge-success pull-right">{{ $activeCount }}</span></a>
                        <a href="{{ url('admin/announcements/close') }}" class="list-group-item"><i class="icon-close2"></i> Closed Announcements<span class="badge badge-default pull-right">{{ $inactiveCount }}</span></a>
                    </div>

                    @if (isset($announcement))
                    <div class="panel-body">
                        <div class="btn-group btn-group-justified">
                            <a href="{{ url('admin/announcements/' . $announcement->announcement_id . '/close') }}" class="btn btn-success">Close</a>
                            <a href="{{ url('admin/announcements/' . $announcement->announcement_id . '/delete') }}" class="btn btn-default" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /navigation -->
            </div>

            <div class="col-lg-9">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Create Announcement</h5>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ isset($announcement) ? url('admin/announcements/' . $announcement->announcement_id) : url('admin/announcements/create') }}" method="post">
                            {{ csrf_field() }}

                            <fieldset class="content-group">
                                <div class="form-group">
                                    <label class="control-label col-lg-2">Title</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" name="title" placeholder="title"
                                               value="{{ isset($announcement) ? $announcement->title : old('title') }}" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-2">Content</label>
                                    <div class="col-lg-10">
                                        <textarea rows="5" class="form-control" name="content" placeholder="content..." required>{{ isset($announcement) ? $announcement->content : old('content') }}</textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary"> {{isset($announcement) ? 'Save Changes' : 'Submit'}} <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
@endsection

