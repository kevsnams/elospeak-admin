@extends('layout')
@include('partials.nav', [ 'page' => 'students' ])

@section('content')
    <div class="container mt-1">

        @if (session('message'))
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('message') }}
            </div>
        @endif

        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <div class="d-flex mb-2">
                    <h1 class="display-4">Students</h1>

                    <div class="ml-auto">
                        <a href="{{ route('students.create') }}" class="btn btn-success">Add Student</a>
                    </div>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <th scope="row">{{ $student->id }}</th>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->username }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <a href="{{ route('students.show', [ 'student' => $student->id ]) }}" class="btn btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
