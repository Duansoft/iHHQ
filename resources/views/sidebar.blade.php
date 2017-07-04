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

                    <!-- Main Page -->
                    <li class="{{ Request::is('overview*')? 'active': '' }}">
                        <a href="{{URL::to('overview')}}"><span>Main</span></a>
                    </li>

                    <!-- Logistics Page -->
                    <li class="{{ Request::is('logistics*')? 'active': '' }}">
                        <a href="{{URL::to('logistics')}}"><span>Logistics</span></a>
                    </li>

                    <!-- Payment Page -->
                    <li class="{{ Request::is('payment*')? 'active': '' }}">
                        <a href="{{URL::to('payment')}}"><span>Billing & Payment</span></a>
                    </li>

                    <!-- Support Page -->
                    <li class="{{ Request::is('support*')? 'active': '' }}">
                        {{--<a href="{{URL::to('support')}}"><span>Support<span class="badge bg-warning">5</span></span></a>--}}
                        <a href="{{URL::to('support')}}"><span>Correspondence</span></a>
                    </li>

                    <!-- Legal Tempates Page -->
                    <li class="{{ Request::is('templates*')? 'active': '' }}">
                        <a href="{{URL::to('templates')}}"><span>Legal Templates</span></a>
                    </li>

                    <!-- Account Setting Page -->
                    <li class="{{ Request::is('setting*')? 'active': '' }}">
                        <a href="{{URL::to('setting')}}"><span>Account Settings</span></a>
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