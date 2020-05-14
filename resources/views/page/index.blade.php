@extends('layout')
@include('partials.nav')

@section('content')
    <div class="container mt-5">
        <div class="col-12">
            <div class="jumbotron">
                <h1 class="display-3">ELOSpeak's Admin Panel</h1>
                <p class="lead">
                    Updates will be listed here.
                </p>

                <hr class="my-4">

                <p>To view plans, features and bug reports - please go to our Trello page.</p>

                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="https://trello.com/b/MddBDfXz/elospeak" role="button">Go to Trello</a>
                </p>
            </div>
        </div>
    </div>
@endsection
