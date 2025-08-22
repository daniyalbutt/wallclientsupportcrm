<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title> 
    <link rel="icon" href="https://projectwall.net/public/favicon.ico" type="image/png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('global/img/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/lite-purple.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/perfect-scrollbar.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/toastr.css') }}" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('newglobal/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <!--TOASTIFY-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('styles')
    <style>
        .select2-container .select2-selection--single{
            height: 34px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            color: #444;
            line-height: 31px;
            background-color: #f8f9fa;
        }
        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        a.brands-list {
            margin-left: 20px;
        }

        a.brands-list {
            font-size: 18px;
            font-weight: bold;
            color: #0076c2;
        }

        a.brands-list span {
            border-right: 2px solid rgba(102, 51, 153, 0.1);
            padding-right: 5px;
          margin-right: 0;
          font-size: 12px;
        }
        a.brands-list span:last-child {border: 0;}
        #show_image_popup {
          width: fit-content;
          height: fit-content;
          /*border: 1px solid #22A2C0;*/
          box-sizing: border-box;
          padding: 5px;
          text-align: center;
          position: absolute;
          top: 100%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.3));
          display: none;
          z-index: 2;
        }
        #show_image_popup img{
          max-width: 90%;
          height: auto;
        }
        
        #all-images .active{
          filter: blur(5px);
        }
        .close-btn-area{
          width: 100%;
          text-align: right;
          margin-bottom: 5px;
          
        }
        .close-btn-area button{
          cursor: pointer;
        }
        
    </style>
</head>
<body class="text-left">
    @if(Session::get('valid_user'))
    <div class="app-admin-wrap layout-sidebar-large">
        @include('inc.manager-nav')
        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <div class="main-content">
                @yield('content')
            </div>
            <div class="flex-grow-1"></div>
            <div class="app-footer">
                <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
                    <span class="flex-grow-1"></span>
                    <div class="d-flex align-items-center">
                        <img class="logo" src="{{ asset('global/img/sidebarlogo.png') }}" alt="">
                        <div>
                            <p class="m-0">&copy; <?php echo date("Y"); ?> {{ config('app.name') }}</p>
                            <p class="m-0">All rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--IMAGE VIEW POPUP    -->
    <div id="show_image_popup">
      <div class="close-btn-area">
        <button id="close-btn" class="btn btn-danger">X</button> 
      </div>
      <div id="image-show-area">
        <img id="large-image" src="" alt="">
      </div>
    </div>
    @else
    <div class="auth-layout-wrap">
        <div class="auth-content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card o-hidden">
                        <div class="p-5">
                            <div class="auth-logo text-center mb-4">
                                <img src="{{ asset('global/img/logo.png') }}" alt="{{ config('app.name') }}">
                            </div>
                            <h1 class="mb-3 text-18" style="line-height: 25px;">Verification Code has been send to Email Address: s4s.m*****@gmail.com</h1>
                            <span>Current Ip: {{ Session::get('ip_address') }}</span>
                            <form class="form-horizontal form-simple"  action="{{ route('verify.code') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="code">Verification code</label>
                                    <input type="text" class="form-control form-control-rounded @error('code') is-invalid @enderror" name="code" id="code" required  value="{{ old('code') }}" autofocus>
                                    @if (\Session::has('code'))
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{!! \Session::get('error') !!}</strong>
                                    </span>
                                    @endif
                                </div>
                                <button class="btn btn-rounded btn-primary btn-block mt-2" type="submit">Verify it's you</button>
                                <div class="mt-3 text-center">
                                    <a href="{{ route('logout') }}" class="text-muted" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        <u>Sign out</u>
                                    </a>
                                </div>
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <script src="{{ asset('newglobal/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/script.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/sidebar.large.script.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/echarts.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/echart.options.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/datatables.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/toastr.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/select2.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/Chart.min.js') }}"></script>
    <script src="{{ asset('newglobal/js/sweetalert2.min.js') }}"></script>
    
    
    <!--PUSHER TOAST & CDN-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @yield('script')

    @stack('scripts')
    
    <script>
        $(document).ready(function(){
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                Notification.requestPermission().then(function(permission) {
                  if (permission === 'granted') {
                    console.log('Notification permission granted.');
                  } else {
                    console.log('Notification permission denied.');
                  }
                });
            }
        })
    </script>
    
    @if(session()->has('success'))
    <script>
        var timerInterval;
        swal({
            type: 'success',
            title: 'Success!',
            text: "{{ session()->get('success') }}",
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-lg btn-success',
            timer: 2000
        });
    </script>
    @endif
    <script>
        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}", {
                    timeOut: "50000"
                });
            @endforeach
        @endif
    </script>
    <script>
        if($('#zero_configuration_table').length != 0){
            $('#zero_configuration_table').DataTable({
                order: [[0, "desc"]],
                responsive: true,
            });
        }

        if($('.select2').length != 0){
            $('.select2').select2();
        }
    </script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        setInterval(() => {
            $.ajax({
                type:'POST',
                url:"{{ url('keep-alive') }}",
                success:function(data){
                    console.log(data);
                }
            });
        }, 1200000)
    </script>
    <script>
        $(document).ready(function(){
            $('#dropdownNotification').click(function(){
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    type:'POST',
                    url:"{{ route('mark.notification') }}",
                    success:function(data){
                        console.log(data);
                    }
                });
            });
        });
    </script>
    
    <!--PUSHER NOTIFY TOAST-->
    <script type="text/javascript">
      var currentNotification = null;
    
      // Enable pusher logging - don't include this in production
      // Pusher.logToConsole = true;
      var pusher = new Pusher('1f6aa5564fc8baaca37c', {
        encrypted: true,
        cluster: "mt1",
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
      });
    
      // Subscribe to the channel
      var channel = pusher.subscribe('realtime-notify');
    
      // Bind a function to an Event
      channel.bind('notify-event', function (data) {
        var invoice_detail = "{{ route('manager.link', "id") }}";
        invoice_detail = invoice_detail.replace('id', data.invoice_id);
    
        // Check if there is an existing notification
        if (currentNotification) {
          // Close the existing notification
          currentNotification.close();
        }
    
        sendTestNotification(data.message, invoice_detail);
        currentNotification = Toastify({
          text: data.message,
          duration: 3000,
          destination: invoice_detail,
          newWindow: true,
          close: true,
          gravity: "top",
          position: "right",
          stopOnFocus: true,
          style: {
            background: "#fff",
            color: "#000",
            border: "1px solid #61D5E4"
          },
          onClick: function () {}
        }).showToast();
      });
    </script>
    
    <script>
      function sendTestNotification(message, red_url) {
        if (Notification.permission === 'granted') {
          // Create and show the notification with a URL
          const notification = new Notification('ProjectWall', {
            body: message,
            data: {
              url: red_url, // Replace with your desired URL
            },
          });
    
          // Handle notification click event
          notification.onclick = function () {
            // Open the URL when the notification is clicked
            const url = notification.data.url;
            if (url) {
              window.open(url, '_blank');
            }
          };
        } else if (Notification.permission !== 'denied') {
          // Ask the user for permission
          Notification.requestPermission().then(function (permission) {
            if (permission === 'granted') {
              sendTestNotification();
            } else {
              console.log('Notification permission denied by the user.');
            }
          });
        } else {
          console.log('Notification permission is denied by default.');
        }
      }
    </script>
    
    <!--NEW LEAD PUSHER SCRIPT-->
    @if(Auth::user()->email == "development_manager@gmail.com")
    <script>
        // Initialize Pusher
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }
        });

        // Ensure Notification Permissions
        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission().then(function(permission) {
                if (permission === 'granted') {
                    console.log('Notification permission granted.');
                } else {
                    console.log('Notification permission denied.');
                }
            });
        }

        // Subscribe to the private user channel
        var channel = pusher.subscribe('private-user.' + {{ Auth::user()->id }});

        // Bind to the LeadAssigned event
        channel.bind('LeadAssignManager', function(data) {
            // Display Notification
            if (Notification.permission === 'granted') {
                const notification = new Notification('New Lead Assigned', {
                    body: `New Lead Has Been Assigned!`,
                    data: {
                        lead: data.lead
                    }
                });

            } else {
                swal({
                    icon: 'info',
                    title: 'New Lead Has Been Assigned!',
                    text: "Please enable desktop notifications to get notified more effectively.",
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-info enable-notifications',
                    showCancelButton: true,
                    confirmButtonText: 'Enable Notifications',
                    cancelButtonText: 'Cancel',
                    cancelButtonClass: 'btn btn-lg btn-danger'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('.enable-notifications').trigger('click');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swal({
                            icon: 'error',
                            title: 'Permission Denied!',
                            text: "You have denied permission for desktop notifications.",
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-lg btn-danger'
                        });
                    }
                });
            }
        });
    </script>
    @endif
    
    <!--NEW LEAD STATUS SCRIPT-->
    @if(Auth::user()->email == "development_manager@gmail.com")
    <script>
        $(document).on('click', '.enable-notifications', function() {
            if (!("Notification" in window)) {
                swal({
                    icon: 'error',
                    title: "Your Browser Doesn't Support Desktop Notifications!",
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-danger'
                });
                return;
            }
            if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function(permission) {
                    if (permission === "granted") {
                        new Notification("Notifications enabled!", {
                            body: "You will now receive notifications from https://projectwall.net.",
                        });
                    }
                });
            } else {
                swal({
                    icon: 'error',
                    title: 'You Have Denied Permission To Show Desktop Notifications!',
                    text: "Please enable desktop notifications to get notified more effectively.",
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-danger'
                });
            }
        });
    </script>
    <script>

        // Initialize Pusher
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }
        });

        // Ensure Notification Permissions
        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission().then(function(permission) {
                if (permission === 'granted') {
                    console.log('Notification permission granted.');
                } else {
                    console.log('Notification permission denied.');
                }
            });
        }

        // Subscribe to the private user channel
        var channel = pusher.subscribe('private-user.' + {{ Auth::user()->id }});

        // Bind to the LeadAssigned event
        channel.bind('LeadStatusAgent', function(data) {
            // Display Notification
            if (Notification.permission === 'granted') {
                const notification = new Notification('Lead Status Updated', {
                    body: `Lead ${data.lead.name} Status Has Been Updated To ${data.lead.status}`,
                    data: {
                        lead: data.lead
                    }
                });

            } else {
                swal({
                    icon: 'info',
                    title: `Lead ${data.lead.name} Status Has Been Updated To ${data.lead.status}`,
                    text: "Please enable desktop notifications to get notified more effectively.",
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-lg btn-info enable-notifications',
                    showCancelButton: true,
                    confirmButtonText: 'Enable Notifications',
                    cancelButtonText: 'Cancel',
                    cancelButtonClass: 'btn btn-lg btn-danger'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('.enable-notifications').trigger('click');
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swal({
                            icon: 'error',
                            title: 'Permission Denied!',
                            text: "You have denied permission for desktop notifications.",
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-lg btn-danger'
                        });
                    }
                });
            }
        });
    </script>
    @endif
    
    
    <!--IMAGE VIEW SCRIPT-->
    <script>
        $("#close-btn").click(function(){
             
           // remove active class from all images
          $(".small-image").removeClass('active');
          $("#show_image_popup").slideUp();
          $('.main-content-wrap.sidenav-open').css('filter', 'none');
        })

        $(".small-image").click(function(){
            // remove active class from all images
           $(".small-image").removeClass('active');
          // add active class
           $(this).addClass('active');
           $('.main-content-wrap.sidenav-open').css('filter', 'blur(5px)');
            
          var image_path = $(this).attr('src'); 
          $("#show_image_popup").fadeOut();
          // now st this path to our popup image src
          $("#show_image_popup").fadeIn();
          $("#large-image").attr('src',image_path);
        });
    </script>
</body>
</html>