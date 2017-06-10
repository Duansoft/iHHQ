<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <a href="#"><img src="{{ URL::asset('admin_assets/images/logo_iHHQ.png') }}" alt=""></a>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <li class="navigation-header"><span>Menu</span> <i class="icon-menu" title="" data-original-title="Main pages"></i></li>

                    <!-- Dashboard -->
                    <li class="{{ Request::is('admin/dashboard*')? 'active': '' }}">
                        <a href="{{URL::to('admin/dashboard')}}"><span>Overview</span></a>
                    </li>

                    <!-- Files -->
                    <li class="{{ Request::is('admin/users*')? 'active': '' }}">
                        <a href="{{URL::to('admin/users')}}"><span>Users</span></a>
                    </li>

                    <!-- Files -->
                    <li class="{{ Request::is('admin/files*')? 'active': '' }}">
                        <a href="{{URL::to('admin/files')}}"><span>Files</span></a>
                    </li>

                    <!-- Logistics Page -->
                    <li class="{{ Request::is('admin/logistics*')? 'active': '' }}">
                        <a href="{{URL::to('admin/logistics')}}"><span>Logistics</span></a>
                    </li>

                    <!-- Payment Page -->
                    <li class="{{ Request::is('admin/payment*')? 'active': '' }}">
                        <a href="{{URL::to('admin/payment')}}"><span>Billing & Payment</span></a>
                    </li>

                    <!-- Support Page -->
                    <li class="{{ Request::is('admin/tickets*')? 'active': '' }}">
                        <a href="{{URL::to('admin/tickets/')}}"><span>Tickets</span></a>
                        {{--<a href="{{URL::to('admin/tickets/')}}"><span>Tickets<span class="badge bg-warning">5</span></span></a>--}}
                    </li>

                    <!-- Announcements -->
                    <li class="{{ Request::is('admin/announcements*')? 'active': '' }}">
                        <a href="{{URL::to('admin/announcements')}}"><span>Announcement</span></a>
                    </li>

                    <!-- Templates -->
                    <li class="{{ Request::is('admin/templates*')? 'active': '' }}">
                        <a href="{{URL::to('admin/templates')}}"><span>Legal Templates</span></a>
                    </li>

                    <!-- Account Setting Page -->
                    <li class="{{ Request::is('admin/setting*')? 'active': '' }}">
                        <a href="{{URL::to('admin/setting')}}"><span>Account Settings</span></a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navigation -->

        <div class="sidebar-panel center-block btn mt-30">
            <div class="category-content no-padding">
                <a href="{{URL::to('/logout')}}"><span>Log Out</span></a>
            </div>
        </div>
    </div>
</div>