@extends('layouts.app')
@section('pageTitle', 'Students')

@section('pageHeader')
    <span class="uk-text-large">Students</span>
@endsection

@section('pageBreadcrumbs')
    @include('includes.breadcrumbs', [
        'trails' => [
            [
                'text' => 'Home',
                'href' => url('/')
            ],
            [
                'text' => 'Students',
                'is_active' => true
            ]
        ]
    ])
@endsection

@section('content')

@if (session('statusDelete'))
    <div uk-alert class="uk-alert-success">
        <a class="uk-alert-close" uk-close></a>
        {{ session('statusDelete') }}
    </div>
@endif
<form class="uk-search uk-search-default uk-width-large">
    <span uk-search-icon></span>
    <input class="uk-search-input" type="search" name="query" placeholder="Search...">
</form>

<table class="uk-table uk-table-divider uk-table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Skype</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($students->isEmpty()): ?>
            <tr>
                <td colspan="4"><em>No Students Available</em></td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td class="cursor-hand" uk-toggle="target: #student-show-<?php echo $student->id ?>"><?php echo $student->username ?></td>
                    <td class="cursor-hand" uk-toggle="target: #student-show-<?php echo $student->id ?>"><?php echo $student->full_name ?></td>
                    <td>
                        <a href="skype:<?php echo $student->skype ?>?chat"><?php echo $student->skype ?></a>
                    </td>
                    <td>
                        <ul class="uk-iconnav">
                            <li><a href="{{ route('students.show', ['id' => $student->id]) }}"><span uk-icon="icon: user"></span> View Profile</a></li>
                            <li><a href="javascript:;" data-student-modify="<?php echo $student->id ?>"><span uk-icon="icon: file-edit"></span> Modify</a></li>
                            <li><a href="javascript:;" data-student-delete="<?php echo $student->id ?>"><span uk-icon="icon: trash"></span> Delete</a></li>
                        </ul>
                    </td>
                </tr>
                <tr id="student-show-<?php echo $student->id ?>" hidden>
                    <td colspan="4">
                        <div class="uk-text-lead"><?php echo $student->full_name ?></div>
                        <hr>
                        <div class="uk-grid" uk-grid>
                            <div class="uk-width-1-3">
                                <dl class="uk-description-list">
                                    <dt>E-Mail</dt>
                                    <dd><?php echo $student->email ?></dd>

                                    <dt>Username</dt>
                                    <dd><?php echo $student->username ?></dd>

                                    <dt>Contact Number</dt>
                                    <dd><?php echo $student->personal_contact_number ?></dd>
                                </dl>
                            </div>
                            <div class="uk-width-1-3">
                                <dl class="uk-description-list">
                                    <dt>Skype</dt>
                                    <dd><a href="skype:<?php echo $student->skype ?>?chat"><?php echo $student->skype ?></a></dd>

                                    <dt>Birthday</dt>
                                    <dd>{{ $student->birthday_human }}</dd>

                                    <dt>Age</dt>
                                    <dd><?php echo $student->age ?></dd>
                                </dl>
                            </div>
                            <div class="uk-width-1-3">
                                <a href="<?php echo route('students.show', ['id' => $student->id]) ?>" class="uk-button uk-button-primary">View Full Profile</a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<form id="student-delete-form" method="POST">
    @csrf
    @method('DELETE')
</form>

<div id="student-modal" uk-modal data-bg-close="false">
    <div class="uk-modal-dialog">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h2 class="uk-modal-title" id="student-modal-header">Create Student</h2>
        </div>
        <div class="uk-modal-body" uk-overflow-auto>
            <form class="uk-form-stacked" id="student-form" method="POST">
                @csrf

                <div class="uk-margin">
                    <span class="uk-text-lead">Personal Information</span>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="student-username">Username <span class="field-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="student-username" required minlength="6" data-bouncer-target="#username-error" maxlength="50" name="username" type="text">
                    </div>
                    <div id="username-error"></div>
                </div>

                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-2">
                        <label class="uk-form-label" for="student-password">Password <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input uk-width-expand" required id="student-password" data-bouncer-target="#password-error" name="password" type="password">
                        </div>
                        <div id="password-error"></div>
                    </div>

                    <div class="uk-width-1-2">
                        <label class="uk-form-label" for="student-password-repeat">Password Repeat <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input uk-width-expand" required data-bouncer-target="#password-repeat-error" data-bouncer-password-match="#student-password" id="student-password-repeat" name="password_repeat" type="password">
                        </div>
                        <div id="password-repeat-error"></div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="student-full-name">Full Name <span class="field-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="student-full-name" required data-bouncer-target="#full-name-error" maxlength="100" name="full_name" type="text">
                    </div>
                    <div id="full-name-error"></div>
                </div>

                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="student-personal-contact-number">Contact Number <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                            <input class="uk-input uk-width-expand" id="student-personal-contact-number" data-bouncer-target="#personal-contact-number-error" name="personal_contact_number" required type="number">
                        </div>
                        <div id="personal-contact-number-error"></div>
                    </div>

                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="student-skype">Skype <span class="field-required">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="student-skype" name="skype" type="text" required data-bouncer-target="#skype-error">
                        </div>
                        <div id="skype-error"></div>
                    </div>

                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="student-email">E-Mail <span class="field-required">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="student-email" name="email" type="email" data-bouncer-target="#email-error" required>
                        </div>
                        <div id="email-error"></div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="student-birthday">Birthday <span class="field-required">*</span></label>
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: calendar"></span>
                        <input class="uk-input" id="student-birthday" name="birthday" autocomplete="off" type="text" required data-bouncer-target="#birthday-error">
                    </div>
                    <div id="birthday-error"></div>
                </div>

                <div class="uk-margin">
                    <span class="uk-text-lead">Schedule Preferences</span>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="schedule-templates">Schedule template</label>
                    <div class="uk-form-controls">
                        <select id="schedule-templates" class="uk-select">
                            <option>Select template</option>
                            <option value="week-S">Mon - Fri</option>
                            <option value="week+S">Mon - Sat (whole week)</option>
                            <option value="MWF">MWF</option>
                            <option value="TTh">TTh</option>
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
                        <div class="uk-width-1-3">
                            <div class="uk-padding-small">
                                <div class="uk-form-label">Days <span class="field-required">*</span></div>
                                <div class="uk-form-controls">
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="M">&nbsp;Mon</label><br>
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="T">&nbsp;Tue</label><br>
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="W">&nbsp;Wed</label><br>
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="Th">&nbsp;Thu</label><br>
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="F">&nbsp;Fri</label><br>
                                    <label><input class="uk-checkbox sched-days" type="checkbox" name="schedule_days[]" value="S">&nbsp;Sat</label><br>
                                </div>
                                <input type="hidden" id="schedule-days-not-empty" required data-bouncer-target="#error-schedule-days">
                                <div id="error-schedule-days"></div>
                            </div>
                        </div>
                        <div class="uk-width-1-3">
                            <div class="uk-padding-small">
                                <label class="uk-form-label" for="schedule-start-time">Start Time <span class="field-required">*</span></label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" data-bouncer-target="#error-schedule-start-time" autocomplete="off" required id="schedule-start-time" name="schedule_start_time">
                                </div>
                                <div id="error-schedule-start-time"></div>
                            </div>
                        </div>
                        <div class="uk-width-1-3">
                            <div class="uk-padding-small">
                                <label class="uk-form-label" for="schedule-start-date">Start Date <span class="field-required">*</span></label>
                                <div class="uk-form-controls">
                                    <input type="text" class="uk-input" data-bouncer-target="#error-schedule-start-date" autocomplete="off" required id="schedule-start-date" name="schedule_start_date">
                                </div>
                                <div id="error-schedule-start-date"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-margin uk-text-right">
                    <div uk-spinner class="ajax-spinner" style="visibility: hidden"></div>
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                    <button class="uk-button uk-button-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pageCSS')
    <link href="<?php echo url('/tail.DateTime-0.4.14/css/tail.datetime-default-blue.min.css') ?>" rel="stylesheet">
@endsection

@section('pageJavascript')
<script src="<?php echo url('/bouncer-1.4.5/dist/bouncer.polyfills.min.js') ?>"></script>
<script src="<?php echo url('/tail.DateTime-0.4.14/js/tail.datetime.min.js') ?>"></script>
<script>
    window.__fv_exists = {
        username: false,
        email: false
    };

    window.addEventListener('DOMContentLoaded', function (event) {
        var tdtBirthday = tail.DateTime("#student-birthday", {
            timeFormat: false,
            today: false,
            weekStart: 1,
            position: "top",
            dateFormat: "d F YYYY",
            closeButton: false
        });

        var tdtScheduleStartTime = tail.DateTime("#schedule-start-time", {
            dateFormat: false,
            time12h: true,
            position: "top",
            closeButton: false,
            timeSeconds: false,
            timeHours: 0,
            timeMinutes: 0,
            timeFormat: 'HH:ss'
        });

        var tdtScheduleStartDate = tail.DateTime("#schedule-start-date", {
            timeFormat: false,
            today: false,
            weekStart: 1,
            position: "top",
            dateFormat: "d F YYYY",
            closeButton: false,
            dateStart: new Date(),
            dateRanges: [
                {
                    days: ["SUN"]
                }
            ]
        });

        var fv = new Bouncer('#student-form', {
            fieldClass: 'uk-form-danger',
            errorClass: 'uk-text-danger uk-margin uk-text-small',
            disableSubmit: true,
            customValidations: {
                passwordMismatch: function (field) {
                    var selector = field.getAttribute('data-bouncer-password-match');

                    if (!selector) return false;

                    var otherField = document.querySelector(selector);

                    if (!otherField) return false;

                    return otherField.value !== field.value;
                },

                usernameExists: function (field) {
                    var hasRule = field.getAttribute('data-bouncer-username-exists');

                    if (hasRule === null) return false;
                    return hasRule == '' && window.__fv_exists.username;
                },

                emailExists: function (field) {
                    var hasRule = field.getAttribute('data-bouncer-email-exists');

                    if (hasRule === null) return false;

                    return hasRule == '' && window.__fv_exists.email;
                }
            },
            messages: {
                passwordMismatch: 'Passwords do not match',
                usernameExists: 'Username already exists',
                emailExists: 'E-Mail Address already exists'
            }
        });

        /** [START] Schedule Templates */
        var scheduleTemplates = {
            'week-S': 'M,T,W,Th,F',
            'week+S': 'M,T,W,Th,F,S',
            'MWF': 'M,W,F',
            'TTh': 'T,Th'
        };

        document.getElementById('schedule-templates').addEventListener('change', function (evt) {
            var value = evt.target.value;

            if (!value) return;

            var tpl = scheduleTemplates[''+ value];
            var days = tpl.split(',');

            _.each(document.querySelectorAll('.sched-days'), function (e) {
                e.checked = false;
            });
            _.each(days, function (v) {
                document.querySelector('.sched-days[value="'+ v +'"]').checked = true;
            });

            // This is for the checking if atleast one is checked or not
            document.getElementById('schedule-days-not-empty').value = 'true';
        }, false);
        /** [END] Schedule Template */

        /** [START] Schedule Days onClick - check if atleast one is checked */
        _.each(document.querySelectorAll('.sched-days'), function (e) {
            e.addEventListener('click', function (evt) {
                var hf = document.getElementById('schedule-days-not-empty');
                hf.value = '';

                _.each(document.querySelectorAll('.sched-days:checked'), function (e) {
                    hf.value = 'true';
                });

                fv.validate(hf);
            }, false);
        });
        /** [END] Schedule Days onClick */

        /** [START] tail.DateTime events - add validation on close and change */
        function tdtValidate(el) {
            return function() {
                fv.validate(document.getElementById(el))
            };
        }
        tdtBirthday.on('close', tdtValidate('student-birthday'));
        tdtBirthday.on('change', tdtValidate('student-birthday'));

        tdtScheduleStartTime.on('close', tdtValidate('schedule-start-time'));
        tdtScheduleStartTime.on('change', tdtValidate('schedule-start-time'));

        tdtScheduleStartDate.on('close', tdtValidate('schedule-start-date'));
        tdtScheduleStartDate.on('change', tdtValidate('schedule-start-date'));
        /** [END] tail.DateTime events */

        /** [START] Student CRUD */
        var studentForm = document.getElementById('student-form');
        var studentModal = document.getElementById('student-modal');

        var studentModifyButtons = document.querySelectorAll('[data-student-modify]');
        var studentDeleteButtons = document.querySelectorAll('[data-student-delete]');
        var studentCreateButton = document.getElementById('add-student');

        function changeModalHeader(content) {
            document.getElementById('student-modal-header').innerHTML = content;
        }

        studentCreateButton.addEventListener('click', function (evt) {
            evt.preventDefault();

            changeModalHeader('Create Student');

            studentForm.reset();
            studentForm.setAttribute('action', url('/students'));
            studentForm.removeSpoofMethod();

            document.getElementById('student-password').setAttribute('required', 'required');
            document.getElementById('student-password-repeat').setAttribute('required', 'required');

            document.getElementById('schedule-days-not-empty').value = '';
        }, false);

        var formAjax = axios.create();

        formAjax.interceptors.request.use(function (r) {
            studentForm.disableAllFields();
            studentForm.querySelector('.ajax-spinner').style.visibility = 'visible';

            return r;
        });

        formAjax.interceptors.response.use(function (r) {
            studentForm.enableAllFields();
            return r;
        }, function (r) {
            studentForm.enableAllFields();
            return Promise.reject(r);
        });

        document.addEventListener('bouncerFormValid', function (evt) {
            var form = evt.target;
            var data = serialize(form);

            formAjax.post(form.getAttribute('action'), data).then(function (r) {
                if (r.status == 200 && r.data.success) {
                    var statusDisplay = '<span uk-icon="icon: check"></span> Successfully '+
                        (form.getSpoofMethod() == 'PUT' ? 'updated' : 'created') +' a student';

                    UIkit.notification(statusDisplay, 'success');

                    form.reset();
                    UIkit.modal(studentModal).hide();

                    setTimeout(function () {
                        window.location.href = url('/students/'+ r.data.id);
                    }, 500);
                }
            }).catch(function (error) {
                UIkit.notification("<span uk-icon='icon: warning'></span> Failed processing student's data", "danger");

                if (error.response && error.response.status == 422) {
                    _.each(error.response.data.errors, function(v, i) {
                        var firstError = v[0];
                        
                        // Similar concept to teachers/index view file code, but this one uses global variable
                        // instead of hidden fields - check the comments there to understand
                        // @TODO Use this approach instead. It's much more cleaner
                        if ((i == 'username' || i == 'email') && /taken/.test(firstError)) {
                            window.__fv_exists[i] = true;
                            studentForm.elements[i].setAttribute('data-bouncer-'+ i +'-exists', '');
                            fv.validate(studentForm.elements[i]);

                            studentForm.elements[i].removeAttribute('data-bouncer-'+ i +'-exists');
                        } else {
                            fv.validate(studentForm.elements[i]);
                        }
                    });
                }
            }).finally(function () {
                studentForm.querySelector('.ajax-spinner').style.visibility = 'hidden';
            });
        }, false);

        _.each(studentModifyButtons, function(e) {
            e.addEventListener('click', function (evt) {
                evt.preventDefault();

                var button = evt.target;
                var id = button.getAttribute('data-student-modify');

                changeModalHeader('Update Student Details');

                studentForm.reset();

                studentForm.setAttribute('action', url('/students/'+ id));
                studentForm.setSpoofMethod('PUT');

                document.getElementById('student-password').removeAttribute('required');
                document.getElementById('student-password-repeat').removeAttribute('required');

                document.getElementById('schedule-days-not-empty').value = 'true';

                UIkit.modal(studentModal).show();

                formAjax.get(url('/students/'+ id)).then(function (r) {
                    if (r.status == 200) {
                        _.each(r.data, function (v, k) {
                            var field = studentForm.elements[k];
                            var _v = v;

                            if (k == 'birthday') _v = moment(v).format('DD MMMM YYYY');

                            if (k == 'classroom_schedule_preference') {
                                var htmlAttr = v.html_attr;

                                _.each(htmlAttr.schedule_days, function (s, day) {
                                    document.querySelector('.sched-days[value="'+ day +'"]').checked = s;
                                });

                                document.getElementById('schedule-start-time').value = htmlAttr.schedule_start_time;
                                document.getElementById('schedule-start-date').value = moment(htmlAttr.schedule_date).format('DD MMMM YYYY');
                            }

                            if (field) field.value = _v;
                        });
                    }
                }).finally(function () {
                    studentForm.enableAllFields();
                    studentForm.querySelector('.ajax-spinner').style.visibility = 'hidden';
                });
            }, false);
        });

        var studentDeleteForm = document.getElementById('student-delete-form');
        _.each(studentDeleteButtons, function (e) {
            e.addEventListener('click', function (evt) {
                evt.preventDefault();

                var button = evt.target;
                var id = button.getAttribute('data-student-delete');

                UIkit.modal.confirm('Are you sure you want to delete this teacher?').then(function() {
                    studentDeleteForm.setAttribute('action', url('/students/'+ id));
                    studentDeleteForm.submit();
                }, function () {
                    studentDeleteForm.removeAttribute('action');
                });
            }, false);
        });
        /** [END] Student CRUD */
    });
</script>
@endsection