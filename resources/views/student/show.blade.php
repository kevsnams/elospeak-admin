@php
    use App\Classroom;
@endphp

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
                'active' => true
            ]
        ]
    ])
    <div class="container mt-1">
        @if ($errors->any())
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4 class="alert-heading">Please input the correct values</h4>
                <p class="mb-2">The following errors occured upon validation of your data:</p>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ str_ireplace(
                                [ 'data.', '_' ],
                                [ '', ' ' ],
                                $error)
                            }}
                        </li>
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

        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <div class="d-flex mb-2">
                    <h1 class="display-4">{{ $student->full_name }}</h1>

                    <div class="ml-auto">
                        <button data-toggle="modal" data-target="#add-classroom" class="btn btn-success">Add Classroom</button>

                        <a href="{{ route('students.edit', [ 'student' => $student->id ]) }}" class="btn btn-outline-info">Edit</a>

                        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#delete-student-modal">Delete</button>
                    </div>
                </div>
                <hr class="my-4">
                <h4 class="mb-4">Student Information</h4>
                <dl class="row">
                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9">{{ $student->full_name }}</dd>

                    <dt class="col-sm-3">Username</dt>
                    <dd class="col-sm-9">{{ $student->username }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $student->email }}</dd>

                    @if (filled($student->skype))
                        <dt class="col-sm-3">Skype</dt>
                        <dd class="col-sm-9">{{ $student->skype }}</dd>
                    @endif

                    @if (filled($student->personal_contact_number))
                        <dt class="col-sm-3">Contact Number</dt>
                        <dd class="col-sm-9">{{ $student->personal_contact_number }}</dd>
                    @endif

                    @if (filled($student->birthday))
                        <dt class="col-sm-3">Birthday</dt>
                        <dd class="col-sm-9">{{ $student->birthday->format('F j, Y') }} ({{ $student->age }} y/o)</dd>
                    @endif
                </dl>
                <hr class="my-4">
                <h4 class="mb-4">Classrooms</h4>
                @if ($student->classrooms->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Start</th>
                                <th scope="col">End</th>
                                <th scope="col">Status</th>
                                <th scope="col">Teacher</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->classrooms as $classroom)
                                <tr>
                                    <th scope="row">{{ $classroom->id }}</th>
                                    <td>{{ $classroom->start->format('D - F j, Y') }}</td>
                                    <td>{{ $classroom->start->format('h:i A') }}</td>
                                    <td>{{ $classroom->end->format('h:i A') }}</td>
                                    <td>
                                        @if ($classroom->status == Classroom::STATUS_ACTIVE)
                                            <span class="badge badge-primary">Active</span>
                                        @endif

                                        @if ($classroom->status == Classroom::STATUS_DONE)
                                            <span class="badge badge-light">DONE</span>
                                        @endif

                                        @if ($classroom->status == Classroom::STATUS_CANCELLED)
                                            <span class="badge badge-warning">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($classroom->teacher)
                                            <a href="{{ route('teachers.show', [ 'teacher' => $classroom->teacher->id ]) }}">{{ $classroom->teacher->full_name }}</a>
                                        @else
                                            <em>No Teacher</em>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('classrooms.edit', [ 'classroom' => $classroom->id, 'from' => 'student' ]) }}" class="btn btn-sm btn-outline-success">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-light">
                        This student doesn't have a classroom yet. To create one, click "Add Classroom" button above.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="add-classroom">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Classroom Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form novalidate method="POST" action="{{ route('classrooms.store') }}" id="classroom-form">
                        @csrf
                        <input type="hidden" name="data[student]" value="{{ $student->id }}">
                        <div class="form-group row">
                            <label for="classroom-start-date" class="col-3 col-form-label">Start Date</label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="classroom-start-date" name="data[start_date]" placeholder="YYYY-MM-DD">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="classroom-start-time" class="col-3 col-form-label">
                                Start Time
                                <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="24-HOUR Philippine Time (PHT/UTC+8)">?</span>
                            </label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="classroom-start-time" name="data[start_time]" placeholder="e.g. 13:30 (as in 1:30 PM)">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="classroom-duration" class="col-3 col-form-label">
                                Duration
                                <span class="badge badge-pill badge-primary" data-toggle="tooltip" title="" data-original-title="In minutes (e.g. 120 = 120 minutes)">?</span>
                            </label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="classroom-duration" name="data[duration]" placeholder="Minutes">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="classroom-teacher" class="col-3 col-form-label">Teacher</label>
                            <div class="col-5">
                                <select class="custom-select" id="classroom-teacher" name="data[teacher]">
                                    <option>Select a Teacher...</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="classroom-teacher" class="col-3 col-form-label">Price (in PHP)</label>
                            <div class="col-5">
                                <input type="text" class="form-control" id="classroom-duration" name="data[price]" placeholder="0 means free">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="document.getElementById('classroom-form').submit()" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">POPE</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="delete-student-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Danger Zone</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Make sure to delete all the classrooms associated with this student. Are you sure you want to proceed?</p>

                    <form method="POST" action="{{ route('students.destroy', [ 'student' => $student->id ]) }}" id="delete-student-form">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-student-form').submit();">Yes, delete this student</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
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
            field: document.getElementById('classroom-start-date'),
            format: 'YYYY-MM-DD'
        });
    </script>
@endsection
