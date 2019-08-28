@extends('layouts.app')
@section('pageTitle', 'Classrooms - '. $student->full_name)

@section('pageHeader')
    <span class="uk-text-large">Classrooms &raquo; {{ $student->full_name }}</span>
@endsection


@section('content')
    <div class="uk-grid" uk-grid>
        <div class="uk-width-expand">
            <ul class="uk-tab">
                <li {!! $view == 'weekly' ? 'class="uk-active"': '' !!}><a href="{{ route('student.classrooms', ['id' => $student->id, 'view' => 'weekly']) }}">This Week</a></li>
                <li {!! $view == 'monthly' ? 'class="uk-active"': '' !!}><a href="{{ route('student.classrooms', ['id' => $student->id, 'view' => 'monthly']) }}">Monthly</a></li>
                @if ($view == 'date')
                    <li class="uk-active"><a>View by date ({{ $dateDay->format('j F Y') }})</a></li>
                @endif
            </ul>
        </div>

        @if ($view == 'monthly')
            <div class="uk-width-auto">
                <div class="uk-padding-small">
                    <span uk-icon="settings"></span> <a uk-toggle="target: #classroom-filter">SHOW FILTER</a>
                </div>
            </div>
        @endif

    </div>
    <div id="classroom-filter" {{ $isFilter ? '' : 'hidden' }}>
        <form>
            <div class="uk-flex-right" uk-grid>
                <div class="uk-width-small">
                    <select class="uk-select uk-radius-rounded" id="classroom-filter-weekly-years" name="filter_year">
                        @foreach ($filterYears as $year)
                            @php
                                $selected = '';
                                if ($isFilter) {
                                    $selected = $filterYear === $year ? 'selected' : '';
                                } else {
                                    $selected = $now->year === $year ? 'selected' : '';
                                }
                            @endphp
                            <option value="{{ $year }}" {{ $selected }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="uk-width-small">
                    <select class="uk-select uk-radius-rounded" id="classroom-filter-weekly-months" name="filter_month">
                        @foreach ($filterMonths as $index => $month)
                            @php
                                $selected = '';
                                if ($isFilter) {
                                    $selected = $filterMonth == $index ? 'selected' : '';
                                } else {
                                    $selected = ($now->month - 1) === $index ? 'selected' : '';
                                }
                            @endphp
                            <option value="{{ $index }}" {{ $selected }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="uk-width-small">
                    <button type="submit" class="uk-button uk-button-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
    <br>
    @include('includes.classroomview', [
        'id' => 'classroom',
        'view' => $view,
        'days' => $days,
        'dateDay' => $dateDay,
        'classrooms' => $classrooms,
        'classroomStatus' => $classroomStatus,
        'startOfMonth' => $startOfMonth,
        'endOfMonth' => $endOfMonth
    ])
@endsection

@section('pageCSS')
    <link rel="stylesheet" href="{{ asset('/css/classroom-view.css') }}">
@endsection