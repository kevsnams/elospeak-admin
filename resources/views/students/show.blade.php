@extends('layouts.app')
@section('pageTitle', 'Student | '. $student->full_name)

@section('pageHeader')
    <span class="uk-text-large">Student | {{ $student->full_name }}</span>
@endsection

@section('content')
    <div class="uk-margin">
        <ul class="uk-tab" uk-tab="connect: #student-tabs">
            <li><a href="#" class="uk-active">Overview</a></li>
            <li><a href="#">Transactions</a></li>
        </ul>
    </div>
    <div id="student-tabs" class="uk-switcher">
        <div class="uk-grid" uk-grid uk-tab-item="0">
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
                    <div class="uk-flex">
                        <div class="uk-width-1-3">
                            <dl class="uk-description-list">
                                <dt>Start Time</dt>
                                <dd>{{ $student->classroomSchedulePreference->lz_start_time }}</dd>
                            </dl>
                        </div>

                        <div class="uk-width-1-3">
                            <dl class="uk-description-list">
                                <dt>End Time</dt>
                                <dd>{{ $student->classroomSchedulePreference->lz_end_time }}</dd>
                            </dl>
                        </div>

                        <div class="uk-width-1-3">
                            <dl class="uk-description-list">
                                <dt>Start Date</dt>
                                <dd>{{ $student->classroomSchedulePreference->start_date_human }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-padding-small">
                    <span class="uk-text-lead">Balance</span>
                    <span class="student-balance uk-margin-small-bottom">{{ number_format($student->balance, 2) }} KRW</span>
                    <button class="uk-button uk-button-primary uk-width-1-1" href="#balance-modal" uk-toggle>Add Balance</button>
                    <hr>
                    <button class="uk-button uk-button-default uk-width-1-1">View Classrooms</button>
                </div>
            </div>
        </div>

        <div class="uk-grid uk-margin-small-top" uk-grid uk-tab-item="1">
            <div class="uk-width-1-1">
                <div class="uk-padding-small">
                    <span class="uk-text-lead">History</span><br>
                    <table class="uk-table uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">ID</th>
                                <th class="uk-table-expand">Description</th>
                                <th class="uk-table-small">Amount</th>
                                <th class="uk-table-small">Date</th>
                                <th class="uk-table-small">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->amount }}</td>
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

    <div id="balance-modal" class="uk-flex-top" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <span class="uk-text-lead">Add Balance</span>
            <form method="POST" id="balance-form" action="{{ route('student.add-balance') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $student->id }}">
                <div class="uk-flex uk-flex-middle">
                    <div class="uk-margin-small-right">
                        <label for="balance-amount">Amount (KRW)</label>
                    </div>
                    <div class="uk-margin-small">
                        <input class="uk-input uk-border-pill" style="width: 130px" id="balance-amount-whole" name="balance_amount_whole" type="number" required>
                    </div>
                    <div class="uk-margin-small">
                        <span style="font-weight: bold; display: block; padding: 10px;">.</span>
                    </div>
                    <div class="uk-margin-small">
                        <input class="uk-input uk-border-pill" style="width: 50px" id="balance-amount-decimal" name="balance_amount_decimal" type="number" required value="00">
                    </div>
                    <div class="uk-margin-small uk-margin-medium-left">
                        <button class="uk-button uk-button-primary" type="submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection