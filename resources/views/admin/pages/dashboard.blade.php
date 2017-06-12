@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/progressbar.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/admin_dashboard.js') }}"></script>
    @endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <!-- Header content -->
        <div class="page-header-content">
            <div class="page-title">
                <h2>Hi, <span class="text-warning">Superadmin</span></h2>
            </div>
        </div>
        <!-- /header content -->
    </div>
    <!-- /page header -->
@endsection


@section("content")
    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-lg-11 no-padding">
                <div class="col-md-3">
                    <div class="panel bg-dashboard-dispatch">
                        <div class="panel-body">
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a class="icon-arrow-up52"></a></li>
                                </ul>
                            </div>
                            <span class="text-white-opacity-70">DISPATCH</span>
                            <h1 class="no-margin" style="font-size: 40px;">{{$dispatches}}</h1>
                            <div class="border-top border-white-30 mt-10">
                                <span class="mt-10 display-block text-white-opacity-80">RESPONSE REQUIRED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel bg-dashboard-payment">
                        <div class="panel-body">
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a class="icon-arrow-up52"></a></li>
                                </ul>
                            </div>
                            <span class="text-white-opacity-70">PAYMENTS(S)</span>
                            <h1 class="no-margin" style="font-size: 40px;">{{ $payments }}</h1>
                            <div class="border-top border-white-30 mt-10">
                                <span class="mt-10 display-block text-white-opacity-80">VERIFICATION REQUIRED</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel bg-dashboard-ticket">
                        <div class="panel-body">
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a class="icon-arrow-up52"></a></li>
                                </ul>
                            </div>
                            <span class="text-white-opacity-70">NEW USERS(S)</span>
                            <h1 class="no-margin" style="font-size: 40px;">5</h1>
                            <div class="border-top border-white-30 mt-10">
                                <span class="mt-10 display-block text-white-opacity-80">PLEASE REVIEW</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel bg-dashboard-user">
                        <div class="panel-body">
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a class="icon-arrow-up52"></a></li>
                                </ul>
                            </div>
                            <span class="text-white-opacity-70">TICKETS</span>
                            <h1 class="no-margin" style="font-size: 40px;">{{ $tickets }}</h1>
                            <div class="border-top border-white-30 mt-10">
                                <span class="mt-10 display-block text-white-opacity-80">RESPONSE REQUIRED</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-11">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h3 class="panel-title">Superadmin
                            <small class="ml-20 pl-20 border-left text-grey">32 Entries</small>
                        </h3>
                        <div class="heading-elements">
                            <form class="heading-form" action="#">
                                <div class="form-group has-feedback">
                                    <input type="search" class="form-control" placeholder="Search by file ref, name or tags">
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
                            <th>Type</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>More Information</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <span class="no-margin">BillPlz Payment1<small class="display-block text-muted text-size-small">File Ref 394843</small></span>
                            </td>
                            <td>
                                <span class="no-margin">17th Oct, 15<small class="display-block text-muted text-size-small">6 days ago</small></span>
                            </td>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/avatars/jacky.png') }}" class="img-lg, img-circle" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">Norman Hammond
                                        <small class="display-block text-muted text-size-small">Axiata Group</small>
                                    </h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">File Ref 34847<small class="display-block text-muted text-size-small">Edgar Kennedy(Associate)</small></span>
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
                        <tr>
                            <td>
                                <span class="no-margin">BillPlz Payment2<small class="display-block text-muted text-size-small">File Ref 394843</small></span>
                            </td>
                            <td>
                                <span class="no-margin">17th Oct, 15<small class="display-block text-muted text-size-small">6 days ago</small></span>
                            </td>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/avatars/jacky.png') }}" class="img-lg, img-circle" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">Norman Hammond
                                        <small class="display-block text-muted text-size-small">Axiata Group</small>
                                    </h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">File Ref 34847<small class="display-block text-muted text-size-small">Edgar Kennedy(Associate)</small></span>
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
                        <tr>
                            <td>
                                <span class="no-margin">BillPlz Payment3<small class="display-block text-muted text-size-small">File Ref 394843</small></span>
                            </td>
                            <td>
                                <span class="no-margin">17th Oct, 15<small class="display-block text-muted text-size-small">6 days ago</small></span>
                            </td>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/avatars/jacky.png') }}" class="img-lg, img-circle" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">Norman Hammond
                                        <small class="display-block text-muted text-size-small">Axiata Group</small>
                                    </h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">File Ref 34847<small class="display-block text-muted text-size-small">Edgar Kennedy(Associate)</small></span>
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
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- Modal Dialog -->
        <div id="modal_make_payment1" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-yellow-800">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Casa Desa (File Ref. 1039448)</h5>
                    </div>

                    <div class="row m-10" style="display: flex">
                        <div class="panel m-5 no-padding col-sm-6 col-md-6">
                            <div class="panel-body no-padding">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified no-margin">
                                        <li class="active"><a href="#basic-justified-tab1" data-toggle="tab">Active</a></li>
                                        <li><a href="#basic-justified-tab2" data-toggle="tab">Inactive</a></li>
                                        <li><a href="#basic-justified-tab3" data-toggle="tab">Inactive</a></li>
                                    </ul>

                                    <div class="tab-content p-10">
                                        <div class="tab-pane active" id="basic-justified-tab1">
                                            Easily make tabs equal widths of their parent with <code>.nav-justified</code> class.
                                        </div>

                                        <div class="tab-pane" id="basic-justified-tab2">
                                            Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid laeggin.
                                        </div>

                                        <div class="tab-pane" id="basic-justified-tab3">
                                            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default m-5 no-padding col-sm-6 col-md-6">
                            <div class="panel-heading">
                                <h6 class="panel-title">File Information</h6>
                            </div>
                            <div class="panel-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modal Dialog -->
    </div>
    <!-- /content area -->
@endsection

