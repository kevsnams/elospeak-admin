@php
    use App\CustomerMessage;
@endphp

@extends('layout')
@include('partials.nav',  [ 'page' => 'messages' ])

@section('content')
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">Messages (from Contact Us page)</h1>

                @if ($messages->count() > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Email</th>
                                <th scope="col">Sender's Full Name</th>
                                <th scope="col">Date Sent</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                                @php
                                    $isUnRead = $message->is_read === CustomerMessage::IS_UNREAD;
                                @endphp

                                <tr class="{{ $isUnRead ? 'table-light' : '' }}">
                                    @if ($isUnRead)
                                        <th scope="row" class="align-middle">
                                            {{ $message->email }}
                                        </th>
                                    @else
                                        <td class="align-middle">
                                            {{ $message->email }}
                                        </td>
                                    @endif

                                    <td class="align-middle">{{ $message->full_name }}</td>
                                    <td class="align-middle">{{ $message->created_at->format('F j, Y h:i A') }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route('messages.show', [ 'message' => $message->id ]) }}" class="btn {{ $isUnRead ? 'btn-success' : 'btn-outline-success' }}">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <hr class="my-1">
                    <div class="alert alert-secondary mt-5">
                        <strong>No messages yet.</strong> Come back some other time.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
