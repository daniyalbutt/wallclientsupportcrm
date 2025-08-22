<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500 - Server Error</title>
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('newglobal/css/lite-purple.min.css') }}" rel="stylesheet">
    <style>
        img {
    width: 256px;
    height: 225px;
  }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<div class="not-found-wrap text-center">
    <img src="https://i.imgur.com/qIufhof.png" />
    <h1 class="text-60">500</h1>
    <p class="text-36 subheading mb-3">Internal server error!</p>
    <p class="mb-5 text-muted text-18">Sorry! We are currently trying to fix the problem.</p>
    <a class="btn btn-lg btn-primary btn-rounded" style="color: #fff;" id="backButton" >Go Back</a>
    
    <script>
    $(document).ready(function() {
        // Function to redirect back to the previous URL
        function redirectToPrevious() {
            var previousUrl = document.referrer;
            if (previousUrl !== "") {
                window.location.href = previousUrl;
            } else {
                // If there's no previous URL, redirect to a default URL
                window.location.href = "/default-url";
            }
        }
    
        // Call the function to redirect back when needed
        // For example, on a button click or any other event
        $("#backButton").click(function() {
            redirectToPrevious();
        });
    });
    </script>
</div>