<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LEGO &#8212; @yield('pageTitle')</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,400,700i,900i&display=swap" rel="stylesheet">

    <link href="<?php echo url('/uikit-3.1.6/css/uikit.min.css') ?>" rel="stylesheet">
    <link href="<?php echo url('/css/main.css') ?>" rel="stylesheet">

    @yield('pageCSS')

</head>
<body>
    <div uk-height-viewport="expand: true" class="main-container">
        <div class="uk-grid-collapse" uk-grid>
            <div class="uk-width-1-6" id="content-left">
                <div class="home-button-wrapper">
                    <a href="<?php echo url('/') ?>" class="home-button">LEGO &#8212; Admin</a>
                </div>

                <div class="left-nav-wrapper">
                    <ul class="uk-nav uk-nav-default left-nav">
                        <li class="uk-nav-header">Class</li>
                        <li><a href="<?php echo url('teachers') ?>">Teachers</a></li>
                        <li><a href="<?php echo url('students') ?>">Students</a></li>
                        <li><a href="<?php echo url('classrooms') ?>">Classrooms</a></li>
                        <li class="uk-nav-divider"></li>
                        <li class="uk-nav-header">Configurations</li>
                        <li><a href="#">Admin</a></li>
                        <li><a href="#">Payments</a></li>
                        <li><a href="#">Salaries</a></li>
                        <li><a href="#">Other Website Settings</a></li>
                        <li class="uk-nav-divider"></li>
                        <li>
                            <a href="#" id="logout-button" title="Logout"><span uk-icon="icon: sign-out;"></span> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="uk-width-expand" id="content-right">
                <div uk-height-viewport="expand: true">
                    <div class="uk-text-left page-header">
                        @yield('pageHeader')
                    </div>
                    <hr>

                    <div id="main-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="<?php echo route('logout') ?>" method="POST">
        @csrf
    </form>

    <script src="<?php echo url('/js/underscore-1.9.1.min.js') ?>"></script>
    <script src="<?php echo url('/uikit-3.1.6/js/uikit.min.js') ?>"></script>
    <script src="<?php echo url('/uikit-3.1.6/js/uikit-icons.min.js') ?>"></script>
    <script src="<?php echo url('/js/moment-2.24.0.min.js') ?>"></script>
    <script src="<?php echo url('/js/axios.min.js') ?>"></script>
    <script src="<?php echo url('/js/util.js') ?>"></script>

    <script>
        window.legoAdmin = {
            baseUrl: '<?php echo url('/') ?>'
        };

        axios.defaults.headers.common = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }

        document.getElementById('logout-button').addEventListener('click', function (event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }, false);

        function url(path) {
            return legoAdmin.baseUrl + path;
        }
    </script>

    @yield('pageJavascript')

</body>
</html>