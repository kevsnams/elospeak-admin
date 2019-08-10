@extends('layouts.app')
@section('pageTitle', 'Student | {{ $student->full_name }}')

@section('pageHeader')
    <span class="uk-text-large">Student | {{ $student->full_name }}</span>
@endsection