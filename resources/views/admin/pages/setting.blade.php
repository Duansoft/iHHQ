@extends("admin.admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            // Basic example
            $('.file-input').fileinput({
                browseLabel: 'Browse',
                browseIcon: '<i class="icon-file-plus"></i>',
                uploadIcon: '<i class="icon-file-upload2"></i>',
                removeIcon: '<i class="icon-cross3"></i>',
                browseClass: 'btn btn-default',
                showUpload: false,
                layoutTemplates: {
                    icon: '<i class="icon-file-check"></i>'
                },
                initialCaption: "No file selected"
            });

            // Initialize multiple switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));

            elems.forEach(function(html) {
                var switchery = new Switchery(html);
            });
        });
    </script>
@endsection



@section("page-header")
<!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h2><span class="">Account Setting</span></h2>
        </div>
    </div>
    <!-- /header content -->
</div>
<!-- /page header -->
@endsection



@section("content")

<!-- Content area -->
<div class="content">

    <!-- Error Message -->
    @if (count($errors) > 0)
        <div class="alert alert-danger no-border col-md-11">
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
        <div class="alert alert-success no-border col-md-11">
            <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
            <span class="text-semibold">{{ Session::get('flash_message') }}</span>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12, col-lg-11 no-padding">
            <div class="col-md-7 col-lg-7">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title"><span>General Info</span>
                        </h6>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ url('admin/setting') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <fieldset class="content-group">
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control no-border" name="name" placeholder="Your Name" value="{{ $me->name }}" readonly required>
                                    </div>
                                </div>
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Email</label>
                                    <div class="col-lg-10">
                                        <input type="email" class="form-control no-border" name="email" placeholder="Email" value="{{ $me->email }}" readonly required>
                                    </div>
                                </div>
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Avatar</label>
                                    <div class="col-lg-10">
                                        <div style="width: 100px; height: 100px" class="thumb thumb-rounded no-margin-top content-group pull-left">
                                            @if (isset($me->photo))
                                            <img src="{{ asset('upload/avatars/' . $me->photo) }}" alt="">
                                            @else
                                            <img src="{{ asset('admin_assets/images/avatars/avatar.png') }}" alt="">
                                            @endif

                                            {{--<div class="caption-overflow">--}}
                                                {{--<span>--}}
                                                    {{--<a href="#" class="btn border-white text-white btn-flat btn-icon btn-rounded btn-xs"><i class="icon-camera"></i></a>--}}
                                                {{--</span>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-lg-offset-2 mb-20">
                                        <input type="file" class="file-input" name="photo" accept=".png, .jpg" data-allowed-file-extensions='["png", "jpg"]' data-show-caption="true">
                                    </div>
                                </div>
                                <div class="form-group mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Country</label>
                                    <div class="col-lg-10">
                                        <select class="select" name="country_id">
                                            <option value="{{ $country->country_id }}" {{$me->country_id == $country->country_id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">IC/Passport</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control no-border" name="passport_no" placeholder="123456-7890" pattern="^\d{6}-\d{4}$" value="{{ $me->passport_no }}" readonly required>
                                    </div>
                                </div>
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Mobile No.</label>
                                    <div class="col-lg-10">
                                        <input type="tel" class="form-control no-border" placeholder="123456789" value="{{ $me->mobile }}" readonly required>
                                    </div>
                                    {{--pattern="^\d{4}-\d{3}-\d{4}$"--}}
                                </div>
                                <div class="form-group border-bottom mr-5 ml-5">
                                    <label class="control-label col-lg-2 no-padding-left">Preferred Mailing Address</label>
                                    <div class="col-lg-10">
                                        <textarea rows="2" class="form-control no-border" placeholder="118 Heritage Lane, Jalan PJU 8/8A, Damansara Perdanna, PJ 48720, Selangor, Malaysia." name="address">{{ $me->address }}</textarea>
                                        <span class="help-block pl-10 text-italic">All corresponsences, courier, parcels will be directed to this address.</span>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="content-group">
                                <div class="form-group">
                                    <label class="control-label col-lg-2">Current Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" name="current_password" placeholder="Current Password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-2">New Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" name="password" placeholder="New Password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-2">Confirm Password</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation">
                                    </div>
                                </div>
                            </fieldset>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save changes<i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-5">
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h6 class="panel-title"><span>Setting</span>
                        </h6>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="{{ url('admin/setting/notification') }}" method="post">
                            {{ csrf_field() }}

                            <fieldset class="content-group no-margin">
                                <div class="form-group no-margin">
                                    <div class="checkbox checkbox-right checkbox-switchery">
                                        <label class="display-block">
                                            <input type="checkbox" class="switchery" name="is_enable_email" {{ $me->is_enable_email ? 'checked="checked"' : ''}}>
                                            Email Notification
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary mt-20">Save changes<i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->
@endsection

