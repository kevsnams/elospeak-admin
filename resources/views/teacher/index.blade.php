@extends('layout')
@include('partials.nav', [ 'page' => 'teachers' ])

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
                <div class="d-flex flex-column flex-md-row mb-2">
                    <h1 class="display-4">Teachers</h1>

                    <div class="ml-auto">
                        <a href="{{ route('teachers.create') }}" class="btn btn-success d-block d-md-inline">Add Teacher</a>
                    </div>
                </div>
                <div class="table-responsive">
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
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <th scope="row">{{ $teacher->id }}</th>
                                    <td>{{ $teacher->full_name }}</td>
                                    <td>{{ $teacher->username }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>
                                        <a href="{{ route('teachers.show', [ 'teacher' => $teacher->id ]) }}" class="btn btn-primary d-block d-md-inline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
