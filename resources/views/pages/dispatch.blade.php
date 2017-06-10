@extends("app")

@section("css")
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
                <h3><span>Dispatch</span></h3>
                <div>
                </div>
                <!-- /header content -->
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section("content")
    <!-- Content area -->
    <div class="content">
        <!-- Highlighted tabs -->
        <div class="row">
            <div class="col-lg-11">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tracking<small class="ml-20 pl-20 border-left text-grey">3</small></h3>
                    </div>

                    <table class="table datatable-basic">
                        <thead class="active alpha-grey">
                        <tr>
                            <th>Delivery By</th>
                            <th>Receiver & Description</th>
                            <th>Last Update</th>
                            <th>File Ref</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/brands/youtube.png') }}" class="img-lg, img-rounded" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">YouTube<small class="display-block text-muted text-size-small">Ground</small></h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">Gillian Kerr<small class="display-block text-muted text-size-small">Invoice</small></span>
                            </td>
                            <td>
                                <span class="no-margin">07 May 2017</span>
                            </td>
                            <td>
                                <span class="no-margin">38444738</span>
                            </td>
                            <td>
                                <span class="label label-success">Received</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-fade">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-toggle="modal" data-target="#modal_make_payment">Create</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#modal_request_payment">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/brands/youtube.png') }}" class="img-lg, img-rounded" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">YouTube<small class="display-block text-muted text-size-small">Ground</small></h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">Gillian Kerr<small class="display-block text-muted text-size-small">Invoice</small></span>
                            </td>
                            <td>
                                <span class="no-margin">07 May 2017</span>
                            </td>
                            <td>
                                <span class="no-margin">38444738</span>
                            </td>
                            <td>
                                <span class="label label-success">Received</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-fade">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" data-toggle="modal" data-target="#modal_make_payment">Create</a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#modal_request_payment">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="media-left media-middle">
                                    <a href="#"><img src="{{ asset('admin_assets/images/brands/youtube.png') }}" class="img-lg, img-rounded" alt=""></a>
                                </div>
                                <div class="media-left">
                                    <h6 class="no-margin">YouTube<small class="display-block text-muted text-size-small">Ground</small></h6>
                                </div>
                            </td>
                            <td>
                                <span class="no-margin">Gillian Kerr<small class="display-block text-muted text-size-small">Invoice</small></span>
                            </td>
                            <td>
                                <span class="no-margin">07 May 2017</span>
                            </td>
                            <td>
                                <span class="no-margin">38444738</span>
                            </td>
                            <td>
                                <span class="label label-success">Received</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-fade">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">  Actions <span class="caret pl-15"></span></button>
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
        <!-- /highlighted tabs -->
        <span class="text-grey text-italic pl-10">Note: All pacakges received more than 30 days ago will not be displayed in results.</span>
    </div>
    <!-- /content area -->
@endsection

