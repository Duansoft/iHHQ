@extends("admin/admin_app")


@section("css")
@endsection


@section("js")
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/users.js') }}"></script>
@endsection


@section("page-header")
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content col-lg-11">
            <div class="page-title">
                <h2>Users</h2>
            </div>

            <div class="heading-elements">
                <a href="{{url('admin/users')}}"><button type="button" class="btn btn-default heading-btn"><i class="icon-circle-left2 position-left"></i> BACK</button></a>
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
            <div class="panel-heading">
                @if(isset($user))
                <h5 class="panel-title text-capitalize">Edit {{$role->display_name}}</h5>
                @else
                <h5 class="panel-title text-capitalize">Create {{$role->display_name}}</h5>
                @endif
            </div>

            <div class="panel-body">
                <form class="form-horizontal" action="{{url('/admin/users')}}" enctype="multipart/form-data" method="post">
                    {{csrf_field()}}

                    <input type="hidden" name="role" value="{{$role->name}}">

                    @if(isset($user))
                    <input type="hidden" name="id" value="{{$user->id}}">
                    @endif

                    <fieldset class="content-group">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label col-lg-2">Email</label>
                            <div class="col-lg-10">
                                <input type="email" name="email" class="form-control" placeholder="Email address" value="{{ isset($user) ? $user->email : old('email')}}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">Name<small class="text-grey"> (as per NRIC/Passport)</small></label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" value="{{ isset($user) ? $user->name : old('name')}}" required>
                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('passport_no') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">NRIC/Passport No.</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="passport_no" placeholder="000000-0000" pattern="^\d{6}-\d{4}$" value="{{ isset($user) ? $user->passport_no :old('passport_no')}}" required>
                                @if ($errors->has('passport_no'))
                                    <span class="help-block">{{ $errors->first('passport_no') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password" placeholder="Your Password" {{isset($user) ? '' : 'required'}}>
                                @if ($errors->has('password'))
                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                        </div>

                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">Confirm</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation" {{isset($user) ? '' : 'required'}}>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">Country</label>
                            <div class="col-lg-10">
                                <select class="select form-control" name="country_id">
                                    @foreach($countries as $country)
                                        @if (isset($user))
                                        <option value="{{$country->country_id}}" {{ $user->country_id == $country->country_id ? "selected" : ""}}>{{$country->country_name}} ({{$country->phone_code}})</option>
                                        @else
                                        <option value="{{$country->country_id}}" >{{$country->country_name}} ({{$country->phone_code}})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('country_id'))
                                    <span class="help-block">{{ $errors->first('country_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label class="control-label col-lg-2">Mobile Number</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="mobile" placeholder="0123456789" value="{{isset($user) ? $user->mobile : old('mobile')}}" required>
                                @if ($errors->has('mobile'))
                                    <span class="help-block">{{ $errors->first('mobile') }}</span>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Submit<i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
        </div>
        </div>
    </div>
    <!-- /content area -->
@endsection
