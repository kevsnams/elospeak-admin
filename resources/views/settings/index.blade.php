@extends('layout')
@include('partials.nav',  [ 'page' => 'settings' ])

@section('content')
    <div class="container mt-1">
        <div class="d-flex">
            <div class="w-100 white-wrapper p-4">
                <h1 class="display-4">Settings</h1>
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

                <form class="d-flex mb-2" novalidate method="POST" action="{{ route('settings.update', [ 'setting' => 1 ]) }}">
                    @method('PUT')
                    @csrf

                    <fieldset class="w-auto">
                        <legend>Social Media</legend>
                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-settings-socmed-facebook" class="col-form-label">Facebook</label>
                                <input type="text" class="form-control" id="settings-socmed-facebook" value="{{ $settings['socmed.facebook'] ?? '' }}" name="data[socmed][facebook]">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-settings-socmed-instagram" class="col-form-label">Instagram</label>
                                <input type="text" class="form-control" id="settings-socmed-instagram" value="{{ $settings['socmed.instagram'] ?? '' }}" name="data[socmed][instagram]">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-settings-socmed-twitter" class="col-form-label">Twitter</label>
                                <input type="text" class="form-control" id="settings-socmed-twitter" value="{{ $settings['socmed.twitter'] ?? '' }}" name="data[socmed][twitter]">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="classroom-settings-socmed-email" class="col-form-label">Official Email</label>
                                <input type="text" class="form-control" id="settings-socmed-email" value="{{ $settings['socmed.email'] ?? '' }}" name="data[socmed][email]">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
