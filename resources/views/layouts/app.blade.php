<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content>
    <meta name="author" content>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('assets/css/vendor.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet">

    @stack('css')
</head>

<body>

<div id="app" class="app app-sidebar-collapsed">

    <div id="header" class="app-header">


        <div class="brand">
            <a href="{{url('/')}}" class="brand-logo">
                crossenergy
            </a>
        </div>


    @include('parts.menu')

    </div>


    <button class="app-sidebar-mobile-backdrop" data-toggle-target=".app"
            data-toggle-class="app-sidebar-mobile-toggled"></button>


    @yield('content')


    @include('parts.theme-panel')


    <a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>

</div>

<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/app.min.js')}}"></script>

@stack('js')

</body>

</html>
