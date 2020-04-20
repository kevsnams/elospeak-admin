@extends('layouts.app')
@section('pageTitle', 'Customer Messages')

@section('pageHeader')
    <span class="uk-text-large">
        <a href="{{ route('customer_messages.index') }}" style="font-size: 1.5rem;">&laquo; BACK</a> | 
        From: {{ $message->full_name }} <i>{{ $message->email }}</i>
    </span>
@endsection

@section('content')
    <div class="uk-padding-small uk-padding-remove-top">
        <strong>Email:</strong> {{ $message->email }}
        <br>
        <strong>Full Name:</strong> {{ $message->full_name }}
        <br>
        <strong>Date Sent:</strong> {{ $message->created_at->format('F j, Y h:i A') }}
        <br>
        <strong>Message:</strong>
        <p style="font-size: 1.3rem;">{{ $message->message }}</p>
    </div>
@endsection

@section('pageCSS')
    <style>
        .unread {
            font-weight: bold;
        }
        .messages tbody tr {
            cursor: pointer;
        }
    </style>
@endsection