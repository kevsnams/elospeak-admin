@php
    use App\CustomerMessage;
@endphp

@extends('layout')
@include('partials.nav',  [ 'page' => 'messages' ])

@section('content')
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">From: {{ $message->email }}</h1>
                <hr class="my-2">

                <dl class="row">
                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9">{{ $message->full_name }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $message->email }}</dd>

                    <dt class="col-sm-3">Date Sent</dt>
                    <dd class="col-sm-9">{{ $message->created_at->setTimezone('Asia/Manila')->format('F j, Y h:i A') }} &#8212; <em>Converted to Asia/Manila PHT (UTC+08:00)</em></dd>
                </dl>

                <hr class="my-2">
                <p class="text-break text-justify font-weight-light" style="line-height: 2; font-size: 1.4rem;">
                    {{ $message->message }}
                </p>
                <hr class="my-2">
                <a href="{{ route('messages.index') }}" class="btn btn-secondary mt-4">Back</a>
            </div>
        </div>
    </div>
@endsection
