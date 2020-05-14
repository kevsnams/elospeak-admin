@extends('layout')
@include('partials.nav', [ 'page' => 'students' ])

@section('content')
    @include('partials.breadcrumb', [
        'items' => [
            [
                'route' => route('pages.index'),
                'label' => 'Home'
            ],

            [
                'route' => route('students.index'),
                'label' => 'Students'
            ],

            [
                'route' => route('students.show', [ 'student' => $student->id ]),
                'label' => $student->full_name,
            ],
            [
                'label' => 'Edit',
                'active' => true
            ]
        ]
    ])
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">Edit Student Form</h1>
                <hr class="my-1">
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

                @if (session('message'))
                    <div class="alert alert-dismissible alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('message') }}
                    </div>
                @endif

                <form class="d-flex mb-2" novalidate method="POST" action="{{ route('students.update', [ 'student' => $student->id ]) }}">
                    @method('PUT')
                    @csrf

                    <fieldset class="w-auto">
                        <legend>Required Information</legend>

                        <div class="form-group row">
                            <div class="col">
                                <label for="student-username" class="col-form-label">Username</label>
                                <input type="text" readonly disabled class="form-control-plaintext text-secondary" id="student-username" value="{{ $student->username }}">
                            </div>

                            <div class="col">
                                <label for="student-email" class="col-form-label">Email</label>
                                <input type="email" readonly disabled class="form-control-plaintext text-secondary" id="student-email" value="{{ $student->email }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="student-password" class="col-form-label">Password</label>
                                <input type="password" class="form-control" id="student-password" name="data[password]">
                            </div>

                            <div class="col">
                                <label for="student-password_repeat" class="col-form-label">Repeat Password</label>
                                <input type="password" class="form-control" id="student-password_repeat" name="data[password_repeat]">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="student-full_name" class="col-form-label">Full Name</label>
                                <input type="text" class="form-control" id="student-full_name" name="data[full_name]" value="{{ $student->full_name }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('students.show', [ 'student' => $student->id ]) }}" class="btn btn-secondary">Back</a>
                    </fieldset>

                    <fieldset class="w-auto ml-4">
                        <legend>Contact Information</legend>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="student-skype" class="col-form-label">Skype</label>
                                <input type="text" class="form-control" id="student-skype" name="data[skype]" value="{{ $student->skype }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="student-personal_contact_number" class="col-form-label">Contact No</label>
                                <input type="text" class="form-control" id="student-personal_contact_number" name="data[personal_contact_number]" value="{{ $student->personal_contact_number }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="student-birthday" class="col-form-label">Birthday</label>
                                <input type="text" class="form-control" id="student-birthday" name="data[birthday]" value="{{ $student->birthday }}">
                            </div>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('pikaday-1.8.0/css/pikaday.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/moment-2.25.2.min.js') }}"></script>
    <script src="{{ asset('pikaday-1.8.0/js/pikaday.js') }}"></script>
    <script>
        var now = new Date();
        var pickr = new Pikaday({
            field: document.getElementById('student-birthday'),
            format: 'YYYY-MM-DD',
            maxDate: now,
            yearRange: [now.getFullYear() - 100, now.getFullYear()]
        });
    </script>
@endsection
