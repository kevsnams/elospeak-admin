@php
$trail = [
    [
        'route' => route('pages.index'),
        'label' => 'Home'
    ]
];

$page = 'home';

if ($fromPage === 'teacher' && $classroom->teacher) {
    $page = 'teachers';
    array_push($trail,
        [
            'route' => route('teachers.index'),
            'label' => 'Teachers'
        ],

        [
            'route' => route('teachers.show', [ 'teacher' => $classroom->teacher->id ]),
            'label' => $classroom->teacher->full_name
        ],

        [
            'label' => 'Classroom: '. $classroom->start->format('Y-m-d H:i A'),
            'active' => true
        ]
    );
} else if ($fromPage === 'student') {
    $page = 'students';
    array_push($trail,
        [
            'route' => route('students.index'),
            'label' => 'Students'
        ],

        [
            'route' => route('students.show', [ 'student' => $classroom->student->id ]),
            'label' => $classroom->student->full_name
        ],

        [
            'label' => 'Classroom: '. $classroom->start->format('Y-m-d H:i A'),
            'active' => true
        ]
    );
}
@endphp

@extends('layout')
@include('partials.nav', [ 'page' => $page ])

@section('content')
    @include('partials.breadcrumb', [
        'items' => $trail
    ])
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">Classroom Information</h1>
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

                <form class="d-flex mb-2" novalidate method="POST" action="{{ route('classrooms.update', [ 'classroom' => $classroom->id ]) }}">
                    @method('PUT')
                    @csrf

                    <fieldset class="w-auto">

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-teacher" class="col-form-label">Teacher</label>
                                <select class="custom-select" id="classroom-teacher" name="data[teacher]">
                                    <option value="">Select a Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ $classroom->teacher && $classroom->teacher->id === $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-student_id" class="col-form-label">Student</label>
                                <a class="d-block" href="{{ route('students.show', [ 'student' => $classroom->student->id ]) }}">{{ $classroom->student->full_name }}</a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-start_date" class="col-form-label">Start Date</label>
                                <input type="text" class="form-control" id="classroom-start_date" name="data[start_date]" value="{{ $classroom->start->format('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-start_time" class="col-form-label">
                                    Start Time
                                    <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="24-HOUR Philippine Time (PHT/UTC+8)">?</span>
                                </label>
                                <input type="text" class="form-control" id="classroom-start_time" name="data[start_time]" value="{{ $classroom->start->format('H:i') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-duration" class="col-form-label">
                                    Duration (in MINUTES)
                                    <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="In minutes (e.g. 120 = 120 minutes)">?</span>
                                </label>
                                <input type="text" class="form-control" id="classroom-duration" name="data[duration]" value="{{ $classroom->duration }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-duration" class="col-form-label">
                                    End Date/Time
                                    <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="The calculated time (based on duration) when the classroom ends">?</span>
                                </label>
                                <span class="text-secondary d-block">{{ $classroom->end->format('F j, Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-status" class="col-form-label">Status</label>
                                <select class="custom-select" name="data[status]">
                                    @foreach ($status as $item)
                                        <option value="{{ $item[0] }}" {{ $item[0] === $classroom->status ? 'selected' : '' }}>{{ $item[1] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-price" class="col-form-label">
                                    Price (in PHP)
                                    <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="Classroom prices should be in PHP. 0 = FREE">?</span>
                                </label>
                                <input type="text" class="form-control" id="classroom-price" name="data[price]" value="{{ $classroom->price }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        @if (($fromPage === 'teacher' || $fromPage === 'student') && $classroom->{$fromPage})
                            <a href="{{ route($page .'.show',
                                [
                                    $fromPage => $classroom->{$fromPage}->id
                                ]
                            ) }}" class="btn btn-secondary">Back</a>
                        @endif
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
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        var now = new Date();
        var pickr = new Pikaday({
            field: document.getElementById('teacher-birthday'),
            format: 'YYYY-MM-DD',
            maxDate: now,
            yearRange: [now.getFullYear() - 100, now.getFullYear()]
        });
    </script>
@endsection
