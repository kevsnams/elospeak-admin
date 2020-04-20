@extends('layouts.app')
@section('pageTitle', 'Customer Messages')

@section('pageHeader')
    <span class="uk-text-large">Customer Messages - (from "Contact US")</span>
@endsection

@section('content')
    <div class="uk-padding-small uk-padding-remove-top">
        <table class="uk-table messages">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                    <tr {{ $message->is_read ? '' : 'class=unread' }} onclick="top.location.href = '{{ url('/customer_messages/'. $message->id) }}';">
                        <td>{{ $message->full_name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->created_at->format('F j, Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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