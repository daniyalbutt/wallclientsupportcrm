<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <title>{{ config('app.name', 'Kamay Backoffice') }}</title> -->
    <title>{{ config('app.name') }} - @yield('title')</title> 
    <!-- Scripts -->
    <!--<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('global/img/apple-icon-57x57.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('global/img/apple-icon-60x60.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('global/img/apple-icon-72x72.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('global/img/apple-icon-76x76.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('global/img/apple-icon-114x114.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('global/img/apple-icon-120x120.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('global/img/apple-icon-144x144.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('global/img/apple-icon-152x152.png') }}">-->
    <!--<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('global/img/apple-icon-180x180.png') }}">-->
    <!--<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('global/img/android-icon-192x192.png') }}">-->
    <!--<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('global/img/favicon-32x32.png') }}">-->
    <!--<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('global/img/favicon-96x96.png') }}">-->
    <!--<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('global/img/favicon-16x16.png') }}">-->
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
    <!--TOASTIFY-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style type="text/css">
        .file {
              visibility: hidden;
              position: absolute;
            }
            .checked {
              color: orange;
            }

            #chartdiv {
              width: 100%;
              height: 500px;
            }
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
        
        .layout-sidebar-large .sidebar-left .navigation-left{
            width: 140px;
        }
        
        .material-icons{
            font-size: 32px !important;
            height: 32px !important;
            width: 32px !important;
            display: block !important;
            margin: 0 auto 6px !important;
            color: #000;
        }
        
    </style>
    @stack('styles')
</head>
<body class="text-left">
    <div class="app-admin-wrap layout-sidebar-large">
        @include('inc.production-nav')
        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <div class="main-content">
                @if(Auth::user()->status == 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="alert-heading mb-2 font-weight-bold">Account is Under Review</h3>
                                <p>Your Account is Under Review! After Admin Approval you are enable to Start Posting...</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                @yield('content')
                @endif    
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
<!--     
    <script src="{{ asset('global/js/vendors.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('global/js/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/js/customizer.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/js/datatables.min.js') }}"></script>
    <script src="{{ asset('global/js/datatable-basic.min.js') }}"></script>
    <script src="{{ asset('global/js/form-select2.min.js') }}"></script>
    <script src="{{ asset('global/js/ajaxHelper.js') }}"></script>
    <script src="{{ asset('global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('global/js/highlight.js') }}"></script>
    <script src="{{ asset('global/js/toastr.min.js') }}"></script>
    <script src="{{ asset('global/js/custom_script.js') }}"></script> -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js" integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
        $(document).ready(function () {
            if($('.repeater').length != 0){
                $('.repeater').repeater({
                    // (Required if there is a nested repeater)
                    // Specify the configuration of the nested repeaters.
                    // Nested configuration follows the same format as the base configuration,
                    // supporting options "defaultValues", "show", "hide", etc.
                    // Nested repeaters additionally require a "selector" field.
                    repeaters: [{
                        // (Required)
                        // Specify the jQuery selector for this nested repeater
                        selector: '.inner-repeater'
                    }]
                });
            }
        });
    </script>
    @yield('script')

    @stack('scripts')
    <!--PUSHER TOAST & CDN-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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
    
    <!--PUSHER NOTIFY TOAST-->
    <script type="text/javascript">
      var currentNotification = null;
    
      // Enable pusher logging - don't include this in production
    //   Pusher.logToConsole = true;
      var pusher = new Pusher('1f6aa5564fc8baaca37c', {
        encrypted: true,
        cluster: "ap1",
      });
    
      // Subscribe to the channel
      var channel = pusher.subscribe('realtime-notify-task');
    
      // Bind a function to an Event
      channel.bind('notify-event-task', function (data) {
        var task_detail = "{{ route('production.task.show', "id") }}";
        task_detail = task_detail.replace('id', data.task_id);
    
        // Check if there is an existing notification
        if (currentNotification) {
          // Close the existing notification
          currentNotification.close();
        }
    
        sendTestNotification(data.message, task_detail);
        currentNotification = Toastify({
          text: data.message,
          duration: 3000,
          destination: task_detail,
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
        if($('.select2').length != 0){
            $('.select2').select2();
        }
        if($('.zero-configuration').length != 0){
            $('.zero-configuration').DataTable({
                order: [[0, "desc"]],
                responsive: true,
            });
        }
    </script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        setInterval(() => {
            $.ajax({
                type:'POST',
                url:"{{ url('keep-alive') }}",
                success:function(data){
                    if(data.ok == false){
                        document.getElementById('logout-form').submit();
                    }
                }
            });
        }, 1200000)
    </script>
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
        // $(document).ready(function(){
        //     $('#theme-mode').change(function(e){
        //         if(this.checked) {
        //             $.ajax({
        //                 url: "{{ route('production.theme') }}",
        //                 type: "POST", // Using POST method to send data
        //                 data: { theme: 1 }, // Send data along with the request
        //                 success: function(response){
        //                     // Process the response
        //                     console.log(response);
        //                 },
        //                 error: function(xhr, status, error){
        //                     // Handle errors
        //                     console.error(xhr, status, error);
        //                 }
        //             });
        //         }else{
        //             $.ajax({
        //                 url: "{{ route('production.theme') }}",
        //                 type: "POST", // Using POST method to send data
        //                 data: { theme: 0 }, // Send data along with the request
        //                 success: function(response){
        //                     // Process the response
        //                     console.log(response);
        //                 },
        //                 error: function(xhr, status, error){
        //                     // Handle errors
        //                     console.error(xhr, status, error);
        //                 }
        //             });
        //         }
        //     });
        // })
    </script>
</body>
</html>