@extends('layouts.app')
@section('pageTitle', 'Student | '. $student->full_name)

@section('pageHeader')
    <span class="uk-text-large">Student &raquo; {{ $student->full_name }}</span>
@endsection

@section('content')
    <div class="uk-margin">
        <ul class="uk-tab" uk-switcher="connect: #student-tabs">
            <li><a href="#" class="uk-active">Overview</a></li>
            <li><a href="#">Transactions</a></li>
        </ul>
    </div>
    <div id="student-tabs" class="uk-switcher">
        <div class="uk-grid student-tab" uk-grid>
            <div class="uk-width-2-3">
                <div class="uk-padding-small">
                    @if ($errors->has('balance_amount_whole') || $errors->has('balance_amount_decimal'))
                        <div uk-alert class="uk-alert-danger">
                            <a class="uk-alert-close" uk-close></a>
                            Amount must be numeric
                        </div>
                    @endif

                    @if (session('balanceSuccess'))
                        <div uk-alert class="uk-alert-success">
                            <a class="uk-alert-close" uk-close></a>
                            {{ session('balanceSuccess') }}
                        </div>
                    @endif

                    <!-- a class="uk-align-right">Edit</a -->
                    <p class="uk-text-lead uk-margin-remove-top">Personal Information</p>
                    <div class="uk-flex">
                        <div class="uk-width-1-2">
                            <dl class="uk-description-list">
                                <dt>Full Name</dt>
                                <dd>{{ $student->full_name }}</dd>
                            </dl>
        
                            <dl class="uk-description-list">
                                <dt>Username</dt>
                                <dd>{{ $student->username }}</dd>
                            </dl>
        
                            <dl class="uk-description-list">
                                <dt>Skype</dt>
                                <dd><a href="skype:{{ $student->skype }}?chat">{{ $student->skype }}</a></dd>
                            </dl>

                            <dl class="uk-description-list">
                                <dt>Birthday</dt>
                                <dd>{{ $student->birthday_human }}</dd>
                            </dl>

                            <dl class="uk-description-list">
                                <dt>Age</dt>
                                <dd>{{ $student->age }}</dd>
                            </dl>
                        </div>
                        <div class="uk-width-1-2">
                            <dl class="uk-description-list">
                                <dt>Email</dt>
                                <dd>{{ $student->email }}</dd>
                            </dl>
        
                            <dl class="uk-description-list">
                                <dt>Contact Number</dt>
                                <dd>{{ $student->personal_contact_number }}</dd>
                            </dl>
                        </div>
                    </div>
                    <hr>
                    <!-- a class="uk-align-right">Edit</a -->
                    <p class="uk-text-lead">Schedule Preferences</p>
                    <table class="uk-table uk-table-hover uk-table-center">
                        <thead>
                            <tr>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                                <th>Sunday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if ($student->classroomSchedulePreference->monday)
                                        @foreach ($student->classroomSchedulePreference->monday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->tuesday)
                                        @foreach ($student->classroomSchedulePreference->tuesday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->wednesday)
                                        @foreach ($student->classroomSchedulePreference->wednesday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->thursday)
                                        @foreach ($student->classroomSchedulePreference->thursday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->friday)
                                        @foreach ($student->classroomSchedulePreference->friday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->saturday)
                                        @foreach ($student->classroomSchedulePreference->saturday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>

                                <td>
                                    @if ($student->classroomSchedulePreference->sunday)
                                        @foreach ($student->classroomSchedulePreference->sunday_array as $slots)
                                            {!! implode(' &#8212; ', $slots) !!}<br>
                                        @endforeach
                                    @else
                                        <div class="uk-text-center"><span uk-icon="close"></span></div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-padding-small">
                    <a href="{{ route('student.classrooms', ['id' => $student->id]) }}" class="uk-button uk-button-default uk-width-1-1 uk-margin-small-bottom"><span uk-icon="tv"></span> View Classrooms</a>
                    
                    <!--- 
                    <div class="uk-card uk-card-body uk-card-default">
                        <h3 class="uk-card-title">Create Classrooms</h3>
                        <form method="POST" action="{{ route('classrooms.store') }}">
                            @csrf
                            <input type="hidden" name="classroom[student_id]" value="{{ $student->id }}">
                            <div class="uk-flex">
                                <div class="uk-width-auto">
                                    <label>Quantity</label><br>
                                    <input type="number" class="uk-input show-controls" value="{{ $classroomMaxCreate }}" min="1" max="{{ $classroomMaxCreate }}" name="classroom[quantity]" style="width: 75px">
                                </div>
                                <div class="uk-width-expand">
                                    <div style="padding-top: 25px; padding-left: 20px;">
                                        <label>
                                            <input type="checkbox" id="a" uk-toggle="target: #create-classroom-schedule; animation: uk-animation-slide-top-medium;" name="classroom[use_schedule_preferences]" class="uk-checkbox" checked>
                                            <small>Use Schedule Preferences</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
            
                            <div id="create-classroom-schedule" class="uk-margin-top" hidden>
                                <hr>
                                <div class="uk-flex">
                                    <div class="uk-margin-medium-right">
                                        <input class="uk-input" style="width: 120px" type="text" id="create-classroom-start-time" name="classroom[start_time]" placeholder="Start Time">
                                    </div>
                                    <div>
                                        <input class="uk-input" style="width: 120px" type="text" id="create-classroom-start-date" name="classroom[start_date]" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>
            
                            <div class="uk-margin-top">
                                <button type="submit" class="uk-button uk-button-primary">Create</button>
                            </div>
                        </form>
                    </div>
                    -->
                </div>
            </div>
        </div>

        <div class="uk-grid uk-margin-small-top student-tab" uk-grid>
            <div class="uk-width-1-1">
                <div class="uk-padding-small">
                    <span class="uk-text-lead">History</span><br>
                    <table class="uk-table uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">ID</th>
                                <th class="uk-table-expand">Description</th>
                                <th class="uk-table-small">Amount</th>
                                <th class="uk-table-small">Status</th>
                                <th class="uk-table-small">Invoice</th>
                                <th class="uk-table-small">Date</th>
                                <th class="uk-table-small">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ number_format($transaction->amount) }} KRW</td>
                                    <td>{{ $transaction->invoice->status_text }}</td>
                                    <td>
                                        <a href="{{ route('invoice.download') }}?id={{ $transaction->invoice->id }}">Invoice</a>
                                    </td>
                                    <td>{{ date('F j, Y', strtotime($transaction->created_at)) }}</td>
                                    <td>{{ date('h:i A', strtotime($transaction->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageCSS')
    <link href="<?php echo url('/tail.DateTime-0.4.14/css/tail.datetime-default-blue.min.css') ?>" rel="stylesheet">
@endsection

@section('pageJavascript')
    <script src="<?php echo url('/tail.DateTime-0.4.14/js/tail.datetime.min.js') ?>"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function (event) {
            var tdtCreateClassroomStartTime = tail.DateTime("#create-classroom-start-time", {
                dateFormat: false,
                time12h: true,
                position: "top",
                closeButton: false,
                timeSeconds: false,
                timeHours: 0,
                timeMinutes: 0,
                timeFormat: 'HH:ss'
            });

            var tdtCreateClassroomStartDate = tail.DateTime("#create-classroom-start-date", {
                timeFormat: false,
                today: false,
                weekStart: 1,
                position: "left",
                dateFormat: "d F YYYY",
                closeButton: false,
                dateStart: new Date(),
                dateRanges: [
                    {
                        days: ["SUN"]
                    }
                ]
            });
        });
    </script>
@endsection