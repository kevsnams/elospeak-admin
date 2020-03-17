<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,400,700i,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="{{ asset('dist/bundle.css') }}" rel="stylesheet">

    <style>
        svg.feather {
            height: 1em;
            margin-top: -4px;
            pointer-events: none;
            vertical-align: middle;
            width: 1em;
        }
    </style>
</head>
<body>
    <div id="main"></div>
    <script src="{{ asset('dist/bundle.js') }}" defer></script>
</body>
</html>