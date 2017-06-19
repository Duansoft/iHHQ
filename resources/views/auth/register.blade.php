<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iHHQ</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('admin_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('admin_assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('admin_assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('admin_assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('admin_assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/pages/login.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body class="login-container bg-slate-800">

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">
                <!-- Registration form -->
                <div class="row no-margin">
                    <div class="col-lg-6 col-lg-offset-3">
                        <div class="panel no-border registration-form">
                            <div class="panel-body no-padding">
                                <div class="row no-margin" style="display: flex">
                                    <div class="col-lg-5 bg-yellow-800 p-20" style="border-bottom-left-radius: 3px; border-top-left-radius: 3px;">
                                        <h1 class="text-black-555">Welcome to iHHQ</h1>
                                        <span class="text-grey-700">Signup to get access to the iHHQ portal</span>
                                        <h2 class="text-black-555">Benefits</h2>
                                        <ul class="text-grey-700">
                                            <li><span>Access to 100's of legal templates</span></li>
                                            <li><span>Receive case updates (existing clients)</span></li>
                                            <li><span>Pay online</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-7 no-padding">
                                        <div class="m-20">
                                            <form action="{{url('/register')}}" enctype="multipart/form-data" method="post">

                                                {{csrf_field()}}

                                                <div class="text-center">
                                                    <h1 class="content-group-lg text-grey-600">Create an Account</h1>
                                                </div>

                                                <!-- Error Message -->
                                                @if ($errors->has('msg'))
                                                    <div class="alert alert-danger no-border">
                                                        <span class="text-semibold text-center">{{ $errors->first('msg') }}</span>
                                                    </div>
                                                @endif

                                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <label for="email" class="control-label">Email</label>
                                                    <input type="email" name="email" class="form-control" placeholder="Email address" value="{{old('email')}}" required>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            {{ $errors->first('email') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                                            <label class="control-label">
                                                                Name
                                                                <small class="text-grey">(as per NRIC/Passport)</small>
                                                            </label>
                                                            <input type="text" class="form-control" name="name" placeholder="Your Name" value="{{old('name')}}" required>
                                                            @if ($errors->has('name'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('name') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('passport_no') ? ' has-error' : '' }}">
                                                            <label class="control-label">NRIC/Passport No.</label>
                                                            <input type="text" class="form-control" name="passport_no" placeholder="123456-7890" pattern="^\d{6}-\d{4}$"
                                                                   value="{{old('passport_no')}}" required>
                                                            @if ($errors->has('passport_no'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('passport_no') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                                            <label class="control-label">Password</label>
                                                            <input type="password" class="form-control" name="password" placeholder="Your Password" required>
                                                            @if ($errors->has('password'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('password') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                                            <label class="control-label">Confirm</label>
                                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmation" required>
                                                            @if ($errors->has('password_confirmation'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('password_confirmation') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }}">
                                                            <label class="control-label">Country</label>
                                                            <select class="select form-control" name="country_id">
                                                                @foreach($countries as $country)
                                                                    <option value="{{$country->country_id}}" {{$country->phone_code == 60 ? "selected" : ""}}>{{$country->country_name}} (+{{$country->phone_code}})</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('country_id'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('country_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                                            <label class="control-label">Mobile Number</label>
                                                            <input type="text" class="form-control" name="mobile" placeholder="3xxxxxxxx"
                                                                   value="{{old('mobile')}}" required>
                                                            @if ($errors->has('mobile'))
                                                                <span class="help-block">
                                                                    {{ $errors->first('mobile') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group no-margin">
                                                    <button type="submit" class="btn bg-success btn-block">Continue</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="text-center p-15 bg-grey-F8FAFC text-grey-300 border-top">
                                            <a href="{{ url('/login') }}"><span class="text-muted">Already have an account?</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /registration form -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->

</body>
</html>
