@extends('layouts.app')
@section('pageTitle', 'Configuration &raquo; Website Settings')

@section('pageHeader')
    <span class="uk-text-large">Configuration &raquo; Website Settings</span>
@endsection

@section('content')
    <div class="uk-padding-small uk-padding-remove-top">
        <form class="uk-form-stacked" method="POST" action="{{ route('settings.save') }}">
            @csrf
            
            @if (session ('saveSuccess'))
                <div uk-alert class="uk-alert-success">
                    <a class="uk-alert-close" uk-close></a>
                    Successfully updated settings
                </div>
            @endif

            @if ($errors->any())
                <div uk-alert class="uk-alert-success">
                    <a class="uk-alert-close" uk-close></a>
                    <span class="uk-text-lead">An error occurred</span><br>
                    @if ($errors->count())
                        <ul class="uk-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <p class="uk-text-lead uk-margin-remove-top">Classroom</p>
            <hr>

            <div class="uk-margin">
                <label class="uk-form-label" for="classroom-student-price">Price per Class (in KRW) <span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-width-small" id="classroom-price-per-class" type="number" name="CLASSROOM[price_per_class]" value="{{ Arr::get($webSettings, 'CLASSROOM.price_per_class') }}" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="classroom-student-price-weekend">Weekend Price Rate (in KRW) <span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-width-small" id="classroom-price-per-class" type="number" name="CLASSROOM[price_per_class_weekend]" value="{{ Arr::get($webSettings, 'CLASSROOM.price_per_class_weekend') }}" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="classroom-duration">Duration (in minutes) <span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-width-small" id="classroom-duration" type="number" name="CLASSROOM[duration]" value="{{ Arr::get($webSettings, 'CLASSROOM.duration') }}" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="classroom-offset">In-between breaks (in minutes) <span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-width-small" id="classroom-offset" type="number" name="CLASSROOM[offset]" value="{{ Arr::get($webSettings, 'CLASSROOM.offset') }}" type="text">
                </div>
            </div>

            <p class="uk-text-lead uk-margin-remove-top">Students</p>
            <hr>

            <div class="uk-margin">
                <span class="uk-text-muted">Student settings will be placed here</span>
            </div>

            <p class="uk-text-lead uk-margin-remove-top">Teachers</p>
            <hr>

            <div class="uk-margin">
                <label class="uk-form-label" for="teacher-salary-per-class">Salary per class (in PHP) <span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input uk-width-small" id="teacher-salary-per-class" type="number" name="TEACHER[salary_per_class]" value="{{ Arr::get($webSettings, 'TEACHER.salary_per_class') }}" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <button type="submit" class="uk-button uk-button-primary">Save Settings</button>
            </div>
        </form>
    </div>
@endsection