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
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript" src="{{ URL::asset('admin_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

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

            <div class="text-center" style="position: relative; top: 25px; z-index: 100;">
                <img src="{{ asset('\admin_assets\images\lock.png') }}" style="width: 70px; height: 70px;">
            </div>

            <!-- Content area -->
            <div class="content">
                <div class="panel panel-body login-form no-padding">
                    <div class="p-20 mt-15">
                        <!-- Error Message -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger no-border">
                                @foreach ($errors->all() as $error)
                                    <span class="text-semibold">{{ $error }}</span>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ url('/login') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group has-feedback">
                                <label class="control-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email')}}" required>
                            </div>

                            <div class="form-group has-feedback">
                                <label class="control-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>

                            <div class="form-group login-options">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="styled" name="remember">
                                            Remember
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {!! app('captcha')->display(["style" => "transform:scale(1.12);-webkit-transform:scale(1.12);transform-origin:0 0;-webkit-transform-origin:0 0; margin-bottom:20px;"]) !!}
                            {{--<div class="g-recaptcha mb-10" data-sitekey="6LdhSyUUAAAAAMxKiz6M0W7KYUpZLDguUWH1cPHJ"></div>--}}

                            <div class="form-group no-margin">
                                <button type="submit" class="btn bg-success btn-block">Login</button>
                            </div>
                        </form>
                    </div>

                    {{--<div class="content-divider text-muted form-group no-margin no-padding"><span></span></div>--}}

                    <div class="text-center p-15 bg-grey-F8FAFC text-grey-300 border-top">
                        <a href="{{ url('/password/reset') }}"><span class="text-muted">Forgot Password?</span></a>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ url('/register') }}"><span class="text-white">Don't have an account yet? Create an account</span></a>
                </div>


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
