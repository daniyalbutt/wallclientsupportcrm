@if(Auth::user()->email == 'admin@syncwavecrm.com')
@endif
<style>
    /* lite-purple.min.css | https://projectwall.net/public/newglobal/css/lite-purple.min.css */
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold {
      /* display: block; */
      /* display: inline flow-root list-item; */
      display: inline-flex;
    }
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold .nav-icon, .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold .feather {
      /* margin: 0 auto 6px; */
      margin: -10px 7px 6px;
    }
    
    .layout-sidebar-large .sidebar-left .navigation-left {
      /* width: 120px; */
      width: 135px;
    }
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold .nav-text {
      padding-right: 12px;
    }
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold {
    /*padding: 26px 0;*/
        padding: 35px 0;
    }
    
    /* Inline #12 | https://projectwall.net/public/manager/dashboard */
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold .nav-icon, .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold .feather {
      /* margin: -10px 7px 6px; */
      margin: -7px 7px 0;
    }


    /* lite-purple.min.css | https://projectwall.net/public/newglobal/css/lite-purple.min.css */
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item.active .nav-item-hold, .layout-sidebar-large .sidebar-left .navigation-left .nav-item:hover .nav-item-hold {
      /* color: #082528; */
      color: #78DFF7;
    }
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item.active .triangle, .layout-sidebar-large .sidebar-left .navigation-left .nav-item:hover .triangle {
      /* border-color: transparent transparent #082528 transparent; */
      border-color: transparent transparent #78DFF7 transparent;
    }
    
    /* Element | https://projectwall.net/public/manager/dashboard */
    
    .navigation-left {
      background: #2b2c2f;
      height: auto !important;
    }
    
    /* lite-purple.min.css | https://projectwall.net/public/newglobal/css/lite-purple.min.css */
    
    .layout-sidebar-large .sidebar-left .navigation-left .nav-item .nav-item-hold {
      /* color: #47404f; */
      color: #fff;
    }
    
    
    /* lite-purple.min.css | https://projectwall.net/public/newglobal/css/lite-purple.min.css */
    
    .layout-sidebar-large .main-header {
      /* background: #fff; */
      background: #04a9f5;
    }
    
    .layout-sidebar-large .main-header .menu-toggle div {
      /* background: #47404f; */
      background: #fff;
    }
    
    .layout-sidebar-large .main-header .header-icon {
      color: #fff !important;
    }
    
    .layout-sidebar-large .main-header .header-icon:hover {
      color: #000 !important;
    }
    
    /* Inline #10 | https://projectwall.net/public/manager/dashboard */
    
    a.brands-list {
      /* color: #0076c2; */
      color: #fff;
    }
    
    /* lite-purple.min.css | https://projectwall.net/newglobal/css/lite-purple.min.css */
    
    .card {
      /* background-color: #d7d7d7; */
      background-color: aliceblue;
    }


</style>
<div class="main-header">
    <div class="logo">
        <img src="{{ asset('global/img/sidebarlogo-light.png') }}" alt="{{ config('app.name') }}">
    </div>
    <div class="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="d-flex align-items-center">
        <!-- / Mega menu -->
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <i class="search-icon text-muted i-Magnifi-Glass1"></i>
        </div>
    </div>
    <div style="margin: auto"></div>
    <div class="header-part-right">
        <!-- Full screen toggle -->
        <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
        <a class="text-dark" href="{{ route('admin.project.settings') }}"><i class="fas fa-cog header-icon" title="Settings"></i></a>
        <!-- Grid menu Dropdown -->
        <!-- Notificaiton -->
        <div class="dropdown">
            <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="badge badge-primary">{{count(auth()->user()->unreadNotifications)}}</span>
                <i class="i-Bell text-muted header-icon"></i>
            </div>
            <!-- Lead Notification dropdown -->
            <div class="dropdown-menu dropdown-menu-right notification-dropdown rtl-ps-none" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">
                @php
                $k = 0;
                @endphp
                @foreach(auth()->user()->unreadnotifications as $notifications)
                @if($notifications->type == 'App\Notifications\LeadNotification')
                <a href="{{ route('admin.client.shownotification', ['client' => $notifications->data['id'], 'id' => $notifications->id] ) }}" class="dropdown-item d-flex">
                @elseif($notifications->type == 'App\Notifications\PaymentNotification')
                <a href="" class="dropdown-item d-flex">
                @else
                <a href="" class="dropdown-item d-flex">
                @endif
                    <div class="notification-icon">
                        @if($notifications->type == 'App\Notifications\LeadNotification')
                            <i class="i-Checked-User text-primary mr-1"></i>
                        @elseif($notifications->type == 'App\Notifications\PaymentNotification')
                            <i class="i-Money-Bag text-success mr-1"></i>
                        @else
                            <i class="i-Blinklist text-info mr-1"></i>
                        @endif
                    </div>
                    <div class="notification-details flex-grow-1">
                        <p class="m-0 d-flex align-items-center">
                            <span class="lead-heading">{{$notifications->data['text']}}</span>
                            <span class="flex-grow-1"></span>
                            <span class="text-small text-muted ml-3">{{ $notifications->created_at->diffForHumans() }}</span>
                        </p>
                        <p class="text-small text-muted m-0">{{$notifications->data['name']}}</p>
                    </div>
                </a>
                @if($loop->last)
                    
                @endif
                @php
                    $k++;
                @endphp
                @endforeach
            </div>
        </div>
        <!-- Notificaiton End -->
        <!-- User avatar dropdown -->
        <div class="dropdown">
            <div class="user col align-self-end">
                @if(Auth::user()->image != '')
                <img src="{{ asset(Auth::user()->image) }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @else
                <img src="{{ asset('global/img/user.png') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @endif
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="dropdown-header">
                        <i class="i-Lock-User mr-1"></i> {{ Auth::user()->name }}
                    </div>
                    <a class="dropdown-item" href="{{ route('admin.edit.profile') }}">Edit Profile</a>
                    <a class="dropdown-item" href="{{ route('admin.change.password') }}">Change Password</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Sign out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <li class="nav-item {{ (request()->routeIs('admin.home'))? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('admin.home') }}">
                    <span class="nav-icon material-icons">space_dashboard</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.merchant.index') || request()->routeIs('admin.merchant.edit') || request()->routeIs('admin.merchant.create') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('admin.merchant.index') }}">
                    <span class="nav-icon material-icons">credit_card</span>
                    <span class="nav-text">Merchant</span>
                </a>
                <div class="triangle"></div>
            </li>
            <!--<li class="nav-item {{ request()->routeIs('admin.message') || request()->routeIs('admin.message.show') ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.message') }}">-->
            <!--        <span class="nav-icon material-icons">question_answer</span>-->
            <!--        <span class="nav-text">Messages</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <li class="nav-item {{ (request()->routeIs('admin.link')) || (request()->routeIs('admin.invoice.index') ) || (request()->routeIs('admin.client.create') ) || (request()->routeIs('admin.client.index') ) || (request()->routeIs('admin.client.show') ) || (request()->routeIs('admin.client.edit') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('admin.client.index') }}">
                    <span class="nav-icon material-icons">transcribe</span>
                    <span class="nav-text">Clients</span>
                </a>
                <div class="triangle"></div>
            </li>

            <li class="nav-item {{ (request()->routeIs('admin.invoice')) || (request()->routeIs('admin.single.invoice') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('admin.invoice') }}">
                    <span class="nav-icon material-icons">receipt_long</span>
                    <span class="nav-text">Invoices</span>
                </a>
                <div class="triangle"></div>
            </li>

            <!--<li class="nav-item {{ (request()->routeIs('admin.brief.pending')) || (request()->routeIs('admin.brief.pending') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.brief.pending') }}">-->
            <!--        <i class="nav-icon fa-solid fa-hourglass-half"></i>-->
            <!--        <span class="nav-text">Brief Pending</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->

            <!--<li class="nav-item {{ (request()->routeIs('admin.pending.project')) || (request()->routeIs('admin.pending.project.details') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.pending.project') }}">-->
            <!--        <i class="nav-icon fa-solid fa-file-circle-xmark"></i>-->
            <!--        <span class="nav-text">Pending Projects</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->

            <!--<li class="nav-item {{ (request()->routeIs('admin.project.index') ) || (request()->routeIs('admin.project.show') ) || (request()->routeIs('admin.project.edit') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.project.index') }}">-->
            <!--        <i class="nav-icon fa-solid fa-briefcase"></i>-->
            <!--        <span class="nav-text">Projects</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <!--<li class="nav-item {{ (request()->routeIs('admin.task.index') ) || (request()->routeIs('admin.task.show') ) || (request()->routeIs('admin.task.edit') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.task.index') }}">-->
            <!--        <i class="nav-icon fa-solid fa-list-check"></i>-->
            <!--        <span class="nav-text">Tasks</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->

            <li class="nav-item {{ (request()->routeIs('service.index') ) || (request()->routeIs('service.edit') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('service.index') }}">
                    <span class="nav-icon material-icons">source</span>
                    <span class="nav-text">Services</span>
                </a>
                <div class="triangle"></div>
            </li>

            <!-- <li class="nav-item {{ (request()->routeIs('category.index') ) || (request()->routeIs('category.edit') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('category.index') }}">
                    <i class="nav-icon i-Library"></i>
                    <span class="nav-text">Category</span>
                </a>
                <div class="triangle"></div>
            </li> -->
            <li class="nav-item {{ (request()->routeIs('brand.index') ) || (request()->routeIs('brand.edit') ) || (request()->routeIs('brand.show') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('brand.index') }}">
                    <span class="nav-icon material-icons">auto_mode</span>
                    <span class="nav-text">Brand</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ (request()->routeIs('currency.index') ) || (request()->routeIs('currency.edit') ) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('currency.index') }}">
                    <i class="nav-icon material-icons">paid</i>
                    <span class="nav-text">Currency</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item" data-item="packages">
                <a class="nav-item-hold" href="#">
                    <span class="nav-icon material-icons">view_list</span>
                    <span class="nav-text">Package</span>
                </a>
                <div class="triangle"></div>
            </li>
            <!--<li class="nav-item {{ ( request()->routeIs('admin.user.production') ) || ( request()->routeIs('admin.user.production.edit') ) || ( request()->routeIs('admin.user.production.create') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('admin.user.production') }}">-->
            <!--        <span class="nav-icon material-icons">supervisor_account</span>-->
            <!--        <span class="nav-text">Production</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <li class="nav-item {{ ( request()->routeIs('admin.user.sales') ) || ( request()->routeIs('admin.user.sales.edit') ) || ( request()->routeIs('admin.user.sales.create') )  ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('admin.user.sales') }}">
                    <span class="nav-icon material-icons">stars</span>
                    <span class="nav-text">Sale Agent</span>
                </a>
                <div class="triangle"></div>
            </li>
        </ul>
    </div>
    <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar="" data-suppress-scroll-x="true">
        <!-- Submenu Dashboards-->
        <ul class="childNav" data-parent="packages">
            <li class="nav-item {{ (request()->routeIs('category.index') ) || (request()->routeIs('category.edit') ) ? 'active' : '' }}">
                <a href="{{ route('category.index') }}">
                    <span class="nav-icon material-icons">dns</span>
                    <span class="item-name">Category</span>
                </a>
            </li>
            <li class="nav-item dropdown-sidemenu">
                <a href="#">
                    <span class="nav-icon material-icons">rounded_corner</span>
                    <span class="item-name">Packages</span>
                    <i class="dd-arrow i-Arrow-Down"></i>
                </a>
                <ul class="submenu">
                    <li class="{{ (request()->routeIs('package.create') ) ? 'active' : '' }}">
                        <a href="{{ route('package.create') }}">Create Package</a>
                    </li>
                    <li class="{{ (request()->routeIs('package.index') ) || (request()->routeIs('package.edit') ) ? 'active' : '' }}">
                        <a href="{{ route('package.index') }}">Packages List</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- chartjs-->
    </div>
    <div class="sidebar-overlay"></div>
</div>