<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><img src="{{URL::asset('admin_assets\images\logo_icon_light.png')}}" alt=""></a>
        <a class="navbar-brand" href="#"><B>LoanCompare</B></a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li>
                <a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a>
            </li>
        </ul>

        <p class="navbar-text"><span class="label bg-success">Online</span></p>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src=" {{ isset($user->photo) ? URL::asset($user->photo) : URL::asset('upload/avatar/default.jpg') }}" alt="">
                        <span>{{ $user->name }}</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ URL::to('/admin/profile/get') }}"><i class="icon-user-plus"></i> My profile</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('/admin/settings/get')  }}"><i class="icon-cog4"></i>Settings</a></li>
                        <li><a href="{{ URL::to('/logout') }}"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>