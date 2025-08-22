<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title> 
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
            padding-right: 10px;
            margin-right: 9px;
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
    </style>
</head>
<body class="text-left">
    <div class="app-admin-wrap layout-sidebar-large">
        @include('inc.support-nav')
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
    @yield('script')

    @stack('scripts')
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
    </script>
</body>
</html>