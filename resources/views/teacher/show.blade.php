<?php
    use App\Classroom;
?>
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
                'route' => route('teachers.show', [ 'teacher' => $teacher->id ]),
                'label' => $teacher->full_name,
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
                    <h1 class="display-4">{{ $teacher->full_name }}</h1>

                    <div class="ml-auto">
                        <a href="{{ route('teachers.edit', [ 'teacher' => $teacher->id ]) }}" class="btn btn-outline-info">Edit</a>
                        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#teacher-modal-delete">Delete</button>
                    </div>
                </div>
                <hr class="my-4">
                <h4 class="mb-4">Teacher Information</h4>
                <dl class="row">
                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9">{{ $teacher->full_name }}</dd>

                    <dt class="col-sm-3">Username</dt>
                    <dd class="col-sm-9">{{ $teacher->username }}</dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9">{{ $teacher->email }}</dd>

                    <dt class="col-sm-3">Nickname</dt>
                    <dd class="col-sm-9">{{ $teacher->nickname }}</dd>

                    @if (filled($teacher->skype))
                        <dt class="col-sm-3">Skype</dt>
                        <dd class="col-sm-9">{{ $teacher->skype }}</dd>
                    @endif

                    @if (filled($teacher->personal_contact_number))
                        <dt class="col-sm-3">Contact Number</dt>
                        <dd class="col-sm-9">{{ $teacher->personal_contact_number }}</dd>
                    @endif

                    @if (filled($teacher->birthday))
                        <dt class="col-sm-3">Birthday</dt>
                        <dd class="col-sm-9">{{ $teacher->birthday->format('F j, Y') }} ({{ $teacher->age }} y/o)</dd>
                    @endif

                    @if (filled($teacher->address))
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">{{ $teacher->address }}</dd>
                    @endif

                    @if (filled($teacher->educational_attainment))
                        <dt class="col-sm-3">Educational Attainment</dt>
                        <dd class="col-sm-9">{{ $teacher->educational_attainment_label }}</dd>
                    @endif
                </dl>
                <hr class="my-4">
                <h4 class="mb-4">Classrooms</h4>
                @if ($teacher->classrooms->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Start</th>
                                <th scope="col">End</th>
                                <th scope="col">Status</th>
                                <th scope="col">Student</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teacher->classrooms as $classroom)
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
                                    </td>
                                    <td>
                                        @if ($classroom->student)
                                            <a href="{{ route('students.show', [ 'student' => $classroom->student->id ]) }}">{{ $classroom->student->full_name }}</a>
                                        @else
                                            <em>No Student</em>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('classrooms.edit', [ 'classroom' => $classroom->id, 'from' => 'teacher' ]) }}" class="btn btn-sm btn-outline-success">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-light">
                        This teacher doesn't have a classroom yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="teacher-modal-delete" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dangerous Zone</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Make sure every this teacher doesn't have an <span class="badge badge-info">ACTIVE</span> class.
                        Are you sure you want to delete this teacher?
                    </p>

                    <form method="POST" action="{{ route('teachers.destroy', [ 'teacher' => $teacher->id ]) }}" id="teacher-delete">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('teacher-delete').submit();">Yes, proceed with delete (No turning back)</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection
