@php
    use Carbon\Carbon;

    $header = [];
    $classroomsGridSize = 7;
    
    if ($view == 'date') {
        $header = ['Showing classrooms by date - '. $dateDay->format('j F Y')];
    }

    if ($view == 'monthly') {
        $header = ['Month of '. $startOfMonth->format('F')];
    }
@endphp

<div id="{{ $id }}" class="cv-main">
    <div class="cv-headerbar cv-headerbar-{{ $view }} {{ $view == 'weekly' ? 'uk-flex uk-flex-center' : '' }}">
        @foreach($header as $label)
            @php
                $width = $view == 'weekly' ? 'uk-width-1-7' : '';
                $dayLabel = $view == 'weekly' ? 'cv-day-label-'. $label : '';
            @endphp
            <div class="cv-header cv-header-label-{{ $view }} {{ $width }} {{ $dayLabel }}">
                {{ $label }}
            </div>
        @endforeach
    </div>

    {{-- Show the days (Only on monthly) --}}
        @if ($view == 'monthly')
            <div class="cv-headerbar cv-headerbar-{{ $view }} uk-flex uk-flex-center">
                @foreach($days as $label)
                    <div class="cv-header uk-width-1-7 cv-day-label-{{ strtolower($label) }}">
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        @endif
    {{-- [END] --}}
    
    {{---

        [START] MONTHLY VIEW

    ---}}
    @if ($view =='monthly')
        @while($startOfMonth->lessThan($endOfMonth))
            @foreach ($days as $day)
                @if ($day == 'Monday')
                    <div class="cv-classroom cv-classrooms-{{ $view }} uk-flex">
                @endif

                @if ($startOfMonth->englishDayOfWeek != $day || $startOfMonth->greaterThan($endOfMonth))
                    <div class="cv-room uk-width-1-7">
                        <div class="cv-class-empty">
                            <span class="cv-empty"></span>
                        </div>
                    </div>
                @else
                    @if (!isset($classrooms[$startOfMonth->format('Ymd')]))
                        <div class="cv-room uk-width-1-7">
                            <div class="cv-class-empty">
                                <span class="cv-empty">{{ $startOfMonth->format('j') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="cv-room uk-width-1-7">
                            <div class="cv-class-active cursor-hand" data-classroom-view-day="{{ $startOfMonth->format('Y-m-d') }}">
                                <span class="cv-day">{{ $startOfMonth->format('j') }}</span>
                                <span class="cv-classrooms-count">{{ count($classrooms[$startOfMonth->format('Ymd')]) }} CLASSROOMS</span>
                            </div>
                        </div>
                    @endif

                    @php
                        $startOfMonth = new Carbon($startOfMonth->addDay()->format('Y-m-d'));
                    @endphp
                @endif

                @if ($day == 'Sunday')
                    </div>
                @endif
            @endforeach
        @endwhile
    @endif
    {{---

        [END] MONTHLY VIEW
        
    ---}}

    @if ($view == 'date')
        <div style="margin-top: 20px;">
            <div class="uk-container">
                <div class="uk-flex uk-flex-center" uk-grid>
                    @foreach ($classrooms as $classroom)
                        <div class="uk-width-1-3 uk-card uk-card-default uk-card-body cursor-hand" style="margin-right: 10px;" data-classroom="{{ $classroom->id }}">
                            <div class="uk-card-badge">
                                <span class="cv-status-{{ strtolower($classroom->status_text) }}">{{ $classroom->status_text }}</span>
                            </div>
                            <h3 class="uk-card-title">{{ $classroom->start->format('H:i') }} - {{ $classroom->end->format('H:i') }}</h3>
                            <p>Teacher: {!! $classroom->teacher ? $classroom->teacher->full_name : '<i>No Teacher</i>' !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<div id="cvm-classroom" uk-modal="escClose: false;bgClose: false">
    <div class="uk-modal-dialog uk-modal-body">
        <div class="cvm-spinner uk-text-center" hidden>
            <div uk-spinner></div><br>
            Fetching classroom information
        </div>
        <div class="cvm-form" hidden>
            <form method="POST" id="classroom-form">
                <input type="hidden" id="classroom-id">
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-small" style="padding-top: 8px;">
                        <label for="classroom-date">
                            Date
                        </label>
                    </div>
                    <div class="uk-width-medium">
                        <input type="text" class="uk-input uk-border-rounded" id="classroom-date">
                    </div>
                </div>
                <hr>
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-small" style="padding-top: 8px;">
                        <label for="classroom-time_slot">
                            Time Slot
                        </label>
                    </div>
                    <input type="hidden" id="classroom-timeslot">
                    <div class="uk-width-medium cvm-timeslot-preview" hidden></div>
                    <div class="uk-width-medium cvm-timeslots" hidden></div>
                </div>
                <hr>
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-small" style="padding-top: 8px;">
                        <label for="classroom-search-teacher">
                            Teacher
                        </label>
                    </div>
                    <input type="hidden" id="classroom-teacher">
                    <div class="uk-width-medium">
                        <div class="cvm-current-teacher uk-border-rounded"></div>
                        <div id="change-teacher" class="uk-clearfix">
                            <input type="text" class="uk-input uk-border-rounded" id="classroom-search-teacher" placeholder="i.e. Teacher ID/Full Name/Username">
                            <div class="cvm-teachers" style="padding-top: 10px;"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="uk-grid" uk-grid>
                    <div class="uk-width-small" style="padding-top: 8px;">
                        <label for="classroom-status">
                            Status
                        </label>
                    </div>
                    <div class="uk-width-medium">
                        <select class="uk-select uk-border-rounded" id="classroom-status" name="classroom[status]">
                            @foreach ($classroomStatus as $code => $status)
                                <option value="{{ $code }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <p class="uk-text-right cvm-controls" hidden>
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="cvm-save" type="button">Save</button>
        </p>
    </div>
</div>

@section('pageCSS')
    @parent
    <link href="<?php echo url('/tail.DateTime-0.4.14/css/tail.datetime-default-blue.min.css') ?>" rel="stylesheet">
@endsection

@section('pageJavascript')
    @parent

    <script src="<?php echo url('/tail.DateTime-0.4.14/js/tail.datetime.min.js') ?>"></script>
    <script>
        window.onload = function () {
            var StudentID = {{ $student->id }};

            var cvClassrooms = document.querySelectorAll('[data-classroom]');
            var cvByDay = document.querySelectorAll('[data-classroom-view-day]');
            
            var cvm = document.getElementById('cvm-classroom');
            var cvmTeachers = cvm.querySelector('.cvm-teachers');
            var cvmTimeslots = cvm.querySelector('.cvm-timeslots');
            var cvmCurrentTeacher = cvm.querySelector('.cvm-current-teacher');
            var cvmTimeslotPreview = cvm.querySelector('.cvm-timeslot-preview');
            var cvmControls = cvm.querySelector('.cvm-controls');
            var cvmSpinner = cvm.querySelector('.cvm-spinner');
            var cvmForm = cvm.querySelector('.cvm-form');

            var fieldSave = document.getElementById('cvm-save');
            var fieldSearchTeachers = document.getElementById('classroom-search-teacher');
            var fieldDate = document.getElementById('classroom-date');
            var fieldStatus = document.getElementById('classroom-status');
            var fieldClassroomID = document.getElementById('classroom-id');
            var fieldClassroomTeacher = document.getElementById('classroom-teacher');
            var fieldClassroomTimeslot = document.getElementById('classroom-timeslot');
            var fieldClassroomForm = document.getElementById('classroom-form');

            var lookupTeachersResult = {};
            var lookupTeachers = _.debounce(function(evt) {
                if (evt.target.value.trim().length) {
                    axios.post(url('/classroom/teachers'), {
                        classroom_id: fieldClassroomID.value,
                        query: fieldSearchTeachers.value
                    }).then(function (r) {
                        var availableTeachers = r.data.availableTeachers;
                        var teachersList = '<ul class="uk-list cvm-teachers-list">';
                        
                        if (availableTeachers.length) {
                            for (var i = 0; i < availableTeachers.length; i++) {
                                lookupTeachersResult['teacher-'+ availableTeachers[i].id] = availableTeachers[i];
                                teachersList += '<li class="cursor-hand uk-border-rounded" data-teacher-select="'+ availableTeachers[i].id +'">'+ availableTeachers[i].full_name +'</li>';
                            }
                        } else {
                            teachersList += '<li><em>No Results</em></li>';
                        }

                        teachersList += '</ul>';
                        cvmTeachers.innerHTML = teachersList;
                    });
                } else {
                    cvmTeachers.innerHTML = '';
                }
            }, 800);

            function ajaxLoadingState() {
                cvmSpinner.removeAttribute('hidden');
                cvmForm.setAttribute('hidden', '');
                cvmControls.setAttribute('hidden', '');
            }

            function ajaxDoneState() {
                cvmSpinner.setAttribute('hidden', '');
                cvmForm.removeAttribute('hidden');
                cvmControls.removeAttribute('hidden');
            }

            function showTimeslots() {
                cvmTimeslotPreview.setAttribute('hidden', '');
                cvmTimeslots.removeAttribute('hidden');
            };

            function cancelTimeslotChange() {
                cvmTimeslotPreview.removeAttribute('hidden');
                cvmTimeslots.setAttribute('hidden', '');
                cvmTimeslots.innerHTML = '';
            }

            function drawSelectedTeacher(teacher) {
                cvmCurrentTeacher.innerHTML = '<div class="uk-border-rounded">'+
                    '<div><small>Current Teacher</small></div>'+
                    '<img src="https://a.imge.to/2019/08/07/AsKHC.png" width="25" height="25" class="uk-align-left uk-margin-remove-bottom uk-margin-small-right">'+
                    '<strong>'+ teacher.full_name +'</strong>'+
                '</div>';
            }

            function fetchAvailableTimeslots() {
                var date = tdtClassroomDate.fetchDate();
                var ymd = [
                    date.getFullYear(),
                    date.getMonth() + 1,
                    date.getDate()
                ].join('-');

                var now = new Date();
                now.setHours(0, 0, 0, 0);

                if (date < now) {
                    cvmTimeslots.innerHTML = '<small class="uk-text-muted">Past classroom date detected. Select today or future date to see available timeslots</small>';
                    return;
                }

                if (!cvmTimeslotPreview.hasAttribute('hidden')) {
                    return;
                }
                
                cvmTimeslots.innerHTML = 'Fetching available timeslots...';

                axios.post(url('/classroom/timeslots'), {
                    student_id: StudentID,
                    date: ymd
                }).then(function (r) {
                    if (r.data.length) {
                        var select = document.createElement('select');
                        select.setAttribute('class', 'uk-select uk-border-rounded');
                        select.setAttribute('id', 'classroom-time_slots');

                        var options = '';
                        for (var i = 0; i < r.data.length; i++) {
                            options += '<option value="'+ r.data[i].join('|') +'">'+ r.data[i].join(' - ') +'</option>';
                        }

                        select.innerHTML += options;
                        cvmTimeslots.innerHTML = '';
                        cvmTimeslots.appendChild(select);
                        cvmTimeslots.innerHTML += '<br><a id="cancel-change-timeslot">Cancel</a>';

                        document.getElementById('classroom-time_slots').addEventListener('change', function (evt) {
                            fieldClassroomTimeslot.value = this.value;
                        }, false);
                    } else {
                        cvmTimeslots.innerHTML = '<span class="uk-text-danger">No available slots for this day</span>';
                    }
                }).catch(function () {
                    UIkit.notification({message: 'Something went wrong. Please check DevTools', status: 'danger'});
                    cvmTimeslots.innerHTML = '<span class="uk-text-danger">Server Error</span>'
                });
            }

            cvmTimeslotPreview.addEventListener('click', function (evt) {
                if (evt.target.id == 'change-timeslot') {
                    showTimeslots();
                    fetchAvailableTimeslots();
                }
            });

            cvmTimeslots.addEventListener('click', function (evt) {
                if (evt.target.id == 'cancel-change-timeslot') {
                    cancelTimeslotChange();
                }
            });

            cvmTeachers.addEventListener('click', function (evt) {
                if (evt.target.hasAttribute('data-teacher-select')) {
                    var TeacherID = evt.target.getAttribute('data-teacher-select');

                    if (typeof lookupTeachersResult['teacher-'+ TeacherID] == 'undefined') {
                        return;
                    }

                    var teacher = lookupTeachersResult['teacher-'+ TeacherID];

                    drawSelectedTeacher(teacher);
                    fieldClassroomTeacher.value = teacher.id;
                    fieldSearchTeachers.value = '';
                    cvmTeachers.innerHTML = '';
                }
            });

            var tdtClassroomDate = tail.DateTime("#classroom-date", {
                timeFormat: false,
                today: false,
                weekStart: 1,
                position: "top",
                dateFormat: "d F YYYY",
                closeButton: false,
                dateStart: new Date()
            });
            

            tdtClassroomDate.on('change', function (evt) {
                fetchAvailableTimeslots();
            });

            _.each(cvByDay, function (e) {
                e.addEventListener('click', function (evt) {
                    evt.preventDefault();
                    var button = this;
                    var date = button.getAttribute('data-classroom-view-day');

                    top.location.href = url('/student/classrooms/'+ StudentID +'/date/'+ date);
                }, false);
            });

            _.each(cvClassrooms, function (e) {
                e.addEventListener('click', function (evt) {
                    evt.preventDefault();
                    var button = this;
                    var id = button.getAttribute('data-classroom');
                    
                    document.getElementById('classroom-id').value = id;

                    UIkit.modal('#cvm-classroom').show();

                    ajaxLoadingState();

                    axios.get(url('/classrooms/'+ id)).then(function (r) {
                        ajaxDoneState();

                        var start = moment(r.data.start_raw);
                        var end = moment(r.data.end_raw);

                        cvmTimeslotPreview.innerHTML = '<strong class="uk-text-emphasis">'+
                                [start.format('HH:mm'), end.format('HH:mm')].join(' - ')
                            +'</strong><a style="padding-left: 10px;" id="change-timeslot">Change</a>';

                        cvmTimeslotPreview.removeAttribute('hidden');
                        
                        tdtClassroomDate.selectDate(start.year(), start.month(), start.date());
                        fieldStatus.value = r.data.status;
                        fieldClassroomTeacher.value = r.data.teacher_id;
                        fieldClassroomTimeslot.value = [start.format('HH:mm'), end.format('HH:mm')].join('|');

                        if (r.data.teacher) {
                            drawSelectedTeacher(r.data.teacher);
                        } else {
                            cvmCurrentTeacher.innerHTML = '<small>No Teacher</small>';
                        }
                    }).catch(function (r) {
                        // Error
                    });
                }, false);
            });

            fieldSearchTeachers.addEventListener('keypress', function (evt) {
                cvmTeachers.innerHTML = 'Fetching available teachers...';
            }, false);

            fieldSearchTeachers.addEventListener('keyup', lookupTeachers, false);
            fieldSearchTeachers.addEventListener('keydown', lookupTeachers, false);

            // Reset on close
            UIkit.util.on(cvm, 'hidden', function() {
                cancelTimeslotChange();

                fieldSearchTeachers.value = '';
                fieldClassroomID.value = '';
                fieldClassroomTeacher.value = '';
                fieldClassroomTimeslot.value = '';
                fieldStatus.value = '';

                cvmTeachers.innerHTML = '';
                cvmControls.setAttribute('hidden', '');
            });

            fieldSave.addEventListener('click', function (evt) {
                var date = tdtClassroomDate.fetchDate();
                var ymd = [
                    date.getFullYear(),
                    date.getMonth() + 1,
                    date.getDate()
                ].join('-');

                var data = {
                    'classroom_id': fieldClassroomID.value,
                    'timeslot': fieldClassroomTimeslot.value,
                    'status': fieldStatus.value,
                    'date': ymd,
                    '_method': 'PUT'
                };

                if (fieldClassroomTeacher.value.trim().length) {
                    data['teacher_id'] = fieldClassroomTeacher.value;
                }

                fieldClassroomForm.disableAllFields();

                axios.post(url('/classrooms/'+ StudentID), data).then(function (r) {
                    if (r.status == 200) {
                        UIkit.notification({message: 'Successfully Updated Classroom. Reloading page...', status: 'success'});

                        setTimeout(function() {
                            top.location.reload(true);
                        }, 1300);
                    } else {
                        UIkit.notification({message: 'Something went wrong. Check DevTools', status: 'danger'});
                    }
                }).finally(function() {
                    fieldClassroomForm.enableAllFields();
                });
            });
        };
    </script>
@endsection