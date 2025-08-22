
@if(Auth::user()->email == 'development_manager@gmail.com')
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
      width: 130px;
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
        <a href="javascript:;" class="brands-list" style="margin-top: 3px;">
            @foreach(Auth::user()->brands as $brands)
            <span>{{ implode('', array_map(function($v) { return $v[0]; }, explode(' ', $brands->name))) }}</span>
            @endforeach 
        </a>
    </div>
    <div style="margin: auto"></div>
    <div class="header-part-right">
        <!-- Full screen toggle -->
        <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
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
                        <p class="text-small text-muted m-0">Name: {{$notifications->data['name']}}</p>
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
                        <i class="i-Lock-User mr-1"></i> {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                    </div>
                    <a class="dropdown-item" href="{{ route('manager.edit.profile') }}">Edit Profile</a>
                    <a class="dropdown-item" href="{{ route('manager.change.password') }}">Change Password</a>
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
            <li class="nav-item {{ (request()->routeIs('salemanager.dashboard'))? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ url('manager/dashboard') }}">
                    <!--<i class="nav-icon i-Bar-Chart"></i>-->
                    <span class="nav-icon material-icons">dashboard</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <div class="triangle"></div>
            </li>
            <!--<li class="nav-item {{ request()->routeIs('manager.message') || request()->routeIs('manager.message.show') ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('manager.message') }}">-->
            <!--        <i class="nav-icon i-Speach-Bubble-3"></i>-->
            <!--        <span class="nav-text">Messages</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <li class="nav-item {{ request()->routeIs('manager.notification') || request()->routeIs('manager.notification') ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('manager.notification') }}">
                    <!--<i class="nav-icon i-Bell"></i>-->
                    <span class="nav-icon material-icons">assistant_navigation</span>
                    <span class="nav-text">Notificaiton</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ ( request()->routeIs('salemanager.client.index') || request()->routeIs('salemanager.client.create') || request()->routeIs('manager.generate.payment') || request()->routeIs('manager.client.edit')) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('salemanager.client.index') }}">
                    <!--<i class="nav-icon i-Add-User"></i>-->
                    <span class="nav-icon material-icons">transcribe</span>
                    <span class="nav-text">Clients</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ (request()->routeIs('manager.invoice')) || (request()->routeIs('manager.single.invoice')) || (request()->routeIs('manager.invoice.edit')) || (request()->routeIs('manager.link')) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('manager.invoice') }}">
                    <!--<i class="nav-icon i-Credit-Card"></i>-->
                    <span class="nav-icon material-icons">receipt_long</span>
                    <span class="nav-text">Invoices</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item {{ (request()->routeIs('manager.my.invoices')) ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ url('manager/my-invoice') }}">
                    <!--<i class="nav-icon i-Line-Chart"></i>-->
                    <span class="nav-icon material-icons">insights</span>
                    <span class="nav-text">My Invoices</span>
                </a>
                <div class="triangle"></div>
            </li>
            <!--<li class="nav-item {{ (request()->routeIs('manager.brief.pending')) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('manager.brief.pending') }}">-->
            <!--        <i class="nav-icon i-Folder-Close"></i>-->
            <!--        <span class="nav-text">Brief Pending</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <!--<li class="nav-item {{ (request()->routeIs('manager.pending.project')) || (request()->routeIs('manager.pending.project.details') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('manager.pending.project') }}">-->
            <!--        <i class="nav-icon i-Folder-Loading"></i>-->
            <!--        <span class="nav-text">Pending Projects</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <!--<li class="nav-item {{ (request()->routeIs('manager.project.index') ) || (request()->routeIs('manager.project.show') ) || (request()->routeIs('manager.project.edit') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('manager.project.index') }}">-->
            <!--        <i class="nav-icon i-Suitcase"></i>-->
            <!--        <span class="nav-text">Projects</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
            <!--<li class="nav-item {{ (request()->routeIs('manager.task.index') ) || (request()->routeIs('manager.task.show') ) || (request()->routeIs('manager.task.edit') ) ? 'active' : '' }}">-->
            <!--    <a class="nav-item-hold" href="{{ route('manager.task.index') }}">-->
            <!--        <i class="nav-icon i-Receipt-4"></i>-->
            <!--        <span class="nav-text">Tasks</span>-->
            <!--    </a>-->
            <!--    <div class="triangle"></div>-->
            <!--</li>-->
        </ul>
    </div>
    <div class="sidebar-overlay"></div>
</div>