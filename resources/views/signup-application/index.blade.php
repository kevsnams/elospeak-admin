@php
    use App\SignupApplication;
@endphp
@extends('layout')
@include('partials.nav', [ 'page' => 'applications' ])

@section('content')
    <div class="container mt-1">

        @if (session('message'))
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {!! session('message') !!}
            </div>
        @endif

        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <div class="d-flex mb-2">
                    <h1 class="display-4">Applications</h1>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                            <tr>
                                <th scope="row">{{ $application->id }}</th>
                                <td>{{ $application->json->full_name }}</td>
                                <td>{{ $application->json->email }}</td>
                                <td>
                                    @if ($application->status == SignupApplication::STATUS_PENDING)
                                        <span class="badge badge-primary">PENDING</span>
                                    @endif

                                    @if ($application->status == SignupApplication::STATUS_ACCEPTED)
                                        <span class="badge badge-success">ACCEPTED</span>
                                    @endif

                                    @if ($application->status == SignupApplication::STATUS_DENIED)
                                        <span class="badge badge-warning">DENIED</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('applications.show', [ 'application' => $application->id ]) }}" class="btn btn-primary d-block d-md-inline">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
