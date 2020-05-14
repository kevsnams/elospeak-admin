<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME', 'ELOSpeak Admin Panel') }}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-4.4.1/css/bootstrap.min.css') }}" />

    @yield('css')

    <style>
        body, html {
            height: 100%;
        }

        body {
            background: rgb(241, 241, 241);
        }

        .white-wrapper {
            background: #fff;
            border-radius: .355rem;
        }
    </style>
</head>
<body>
    @yield('header')

    @yield('content')

    <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('bootstrap-4.4.1/js/bootstrap.bundle.min.js') }}"></script>

    @yield('js')

</body>
</html>
