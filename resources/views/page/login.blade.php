@extends('layout')

@section('content')
    <div class="container h-100">
        <div class="row h-100 flex-column justify-content-center align-items-center">
            <form class="col-4" method="POST" action="{{ route('pages.auth') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-dismissible alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>{{ $errors->get('message')[0] }}!</strong> Try again.
                    </div>
                @endif

                <div class="login rounded">
                    <h1>Admin Login</h1>

                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <input type="text" class="form-control" id="login-username" name="username">
                    </div>

                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" class="form-control" id="login-password" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .login {
            background: #fff;
            padding: 2rem;
        }
    </style>
@endsection
