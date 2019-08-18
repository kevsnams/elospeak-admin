@extends('layouts.app')
@section('pageTitle', 'Classrooms &raquo; '. $student->full_name)

@section('pageHeader')
    <span class="uk-text-large">Classrooms &raquo; {{ $student->full_name }}</span>
@endsection

@section('pageCSS')
    <link rel="stylesheet" href="{{ asset('/css/ClassroomDateView.css') }}">
@endsection


@section('content')
    <h3 class="uk-heading-bullet">This week's classes</h3>
    <div id="classrooms"></div>
@endsection

@section('pageJavascript')
    <script src="{{ asset('/js/moment-2.24.0.min.js') }}"></script>
    <script src="{{ asset('/js/ClassroomDateView.js') }}"></script>
    <script>
        var timeslots = @json($timeslots);

        new ClassroomDateView('#classrooms', timeslots, {
            source: []
        });
    </script>
@endsection