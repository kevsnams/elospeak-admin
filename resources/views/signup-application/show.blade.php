@php
    use App\SignupApplication;
@endphp

@extends('layout')
@include('partials.nav',  [ 'page' => 'applications' ])

@section('content')
    <div class="container mt-1">

        @if ($errors->any())
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4 class="alert-heading">Please input the correct values</h4>
                <p class="mb-2">The following errors occured upon validation of your data:</p>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ str_ireplace('data.', '', $error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">{{ $application->json->full_name }}</h1>
                <hr class="my-2">

                <dl class="row">
                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9">{{ $application->json->full_name }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $application->json->email }}</dd>

                    <dt class="col-sm-3">Skype</dt>
                    <dd class="col-sm-9">{{ $application->json->skype }}</dd>

                    <dt class="col-sm-3">Personal Contact No</dt>
                    <dd class="col-sm-9">{{ $application->json->contact_number ?: 'n/a' }}</dd>

                    <dt class="col-sm-3">Birthday</dt>
                    <dd class="col-sm-9">{{ $application->json->birthday ?: 'n/a' }}</dd>

                    <dt class="col-sm-3">Application Date</dt>
                    <dd class="col-sm-9">{{ $application->created_at->setTimezone('Asia/Manila')->format('F j, Y h:i A') }} &#8212; <em>Converted to Asia/Manila PHT (UTC+08:00)</em></dd>

                    <dt class="col-sm-3">Message</dt>
                    <dd class="col-sm-9">{{ $application->json->message ?: 'n/a' }}</dd>
                </dl>

                <hr class="my-2">
                <div class="d-flex">
                    <div>
                        @if ($application->status == SignupApplication::STATUS_PENDING)
                            <button data-toggle="modal" data-target="#accept-modal" class="btn btn-success mr-1">Accept</button>
                            <button data-toggle="modal" data-target="#deny-modal" class="btn btn-warning">Deny</button>
                        @endif

                        @if ($application->status == SignupApplication::STATUS_ACCEPTED)
                            <div class="alert alert-success">
                                This application was already processed. <a href="{{ route('students.show', [ 'student' => $application->student_id ]) }}" class="alert-link">View student account</a>.
                            </div>
                        @endif

                        @if ($application->status == SignupApplication::STATUS_DENIED)
                            <div class="alert alert-danger">
                                This applications was denied
                            </div>
                        @endif
                    </div>
                    <div class="align-self-center ml-auto">
                        <a href="{{ route('applications.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($application->status == SignupApplication::STATUS_PENDING)
        <div class="modal fade" data-backdrop="static" id="accept-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Applicant's login information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-primary">
                            Please take note of these following information if you don't want to specify username &amp; password
                        </div>
                        <form action="{{ route('applications.accept', [ 'application' => $application->id ]) }}" method="POST" id="accept-form">
                            {{ csrf_field() }}
                            <dl class="row">
                                <dt class="col-sm-3">Username</dt>
                                <dd class="col-sm-9"><input type="text" id="gen-username" name="username" class="form-control" value="{{ $genUsername }}"></dd>

                                <dt class="col-sm-3">Password</dt>
                                <dd class="col-sm-9"><input type="text" id="gen-password" name="password" class="form-control" value="{{ $genPassword }}"></dd>
                            </dl>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="document.getElementById('accept-form').submit()" class="btn btn-success">Accept Application</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-backdrop="static" id="deny-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure you want to deny this application?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('applications.deny', [ 'application' => $application->id ]) }}" id="deny-form">
                            {{ csrf_field() }}
                            <p>
                                Please think twice. Double check if necessary.
                            </p>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="document.getElementById('deny-form').submit()" class="btn btn-warning">Deny Application</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
