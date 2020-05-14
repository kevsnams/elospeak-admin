@extends('layout')
@include('partials.nav', [ 'page' => 'teachers' ])

@section('content')
    @include('partials.breadcrumb', [
        'items' => [
            [
                'route' => route('pages.index'),
                'label' => 'Home'
            ],

            [
                'route' => route('teachers.index'),
                'label' => 'Teachers'
            ],

            [
                'label' => 'Add Teacher',
                'active' => true
            ]
        ]
    ])
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">New Teacher Form</h1>
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

                <form class="d-flex mb-2" novalidate method="POST" action="{{ route('teachers.store') }}">
                    @csrf

                    <fieldset class="w-auto">
                        <legend>Personal Information</legend>

                        <div class="form-group row">
                            <div class="col">
                                <label for="teacher-username" class="col-form-label">Username</label>
                                <input type="text" class="form-control" id="teacher-username" name="data[username]" value="{{ old('data.username') }}">
                            </div>

                            <div class="col">
                                <label for="teacher-email" class="col-form-label">Email</label>
                                <input type="email" class="form-control" id="teacher-email" name="data[email]" value="{{ old('data.email') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="teacher-password" class="col-form-label">Password</label>
                                <input type="password" class="form-control" id="teacher-password" name="data[password]">
                            </div>

                            <div class="col">
                                <label for="teacher-password_repeat" class="col-form-label">Repeat Password</label>
                                <input type="password" class="form-control" id="teacher-password_repeat" name="data[password_repeat]">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="teacher-full_name" class="col-form-label">Full Name</label>
                                <input type="text" class="form-control" id="teacher-full_name" name="data[full_name]" value="{{ old('data.full_name') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
                    </fieldset>

                    <fieldset class="w-auto ml-4">
                        <legend>Contact &amp Other Information</legend>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="teacher-skype" class="col-form-label">Skype</label>
                                <input type="text" class="form-control" id="teacher-skype" name="data[skype]" value="{{ old('data.skype') }}">
                            </div>

                            <div class="col-auto">
                                <label for="teacher-personal_contact_number" class="col-form-label">Contact No</label>
                                <input type="text" class="form-control" id="teacher-personal_contact_number" name="data[personal_contact_number]" value="{{ old('data.personal_contact_number') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="teacher-birthday" class="col-form-label">Birthday</label>
                                <input type="text" class="form-control" id="teacher-birthday" name="data[birthday]" value="{{ old('data.birthday') }}">
                            </div>

                            <div class="col-auto">
                                <label for="teacher-address" class="col-form-label">Address</label>
                                <input type="text" class="form-control" id="teacher-address" name="data[address]" value="{{ old('data.address') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-auto">
                                <label for="teacher-educational_attainment" class="col-form-label">Educational Attainment</label>
                                <select class="custom-select" id="classroom-educational_attainment" name="data[educational_attainment]" value="{{ old('data.educational_attainment') }}">
                                    <option selected>Select...</option>
                                    @foreach ($educationAttainments as $key => $educ)
                                        <option value="{{ $key }}" {{ $key == old('data.educational_attainment') && old('data.educational_attainment') !== null ? 'selected' : '' }}>{{ $educ }}</option>
                                    @endforeach
                                </select>
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
            field: document.getElementById('teacher-birthday'),
            format: 'YYYY-MM-DD',
            maxDate: now,
            yearRange: [now.getFullYear() - 100, now.getFullYear()]
        });
    </script>
@endsection
