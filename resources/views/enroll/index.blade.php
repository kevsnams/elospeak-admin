@extends('layouts.app')
@section('pageTitle', 'Enrollment Form')

@section('pageHeader')
    <span class="uk-text-large">Enrollment Form</span>
@endsection

@section('content')

<div id="enroll-done-modal" class="uk-flex-top" uk-modal='{"esc-close": false, "bg-close": false}'>
    <div class="uk-modal-dialog">
        <div class="uk-modal-body">
            <div class="uk-text-center">
                <p class="uk-text-lead">Enrollment Finished</p>
                <p>Student is now officially enrolled. Classrooms were already created based on the student's desired schedule.</p>
                <hr>
                <a id="after-enroll-invoice" class="uk-button uk-button-default uk-button-small">Download Invoice</a>
                <a id="after-enroll-profile" class="uk-button uk-button-default uk-button-small">View Student Profile</a>
            </div>
        </div>
    </div>
</div>
<form class="uk-padding" id="enrollment-form" method="POST">
    @csrf
    <div class="uk-grid" uk-grid>
        <div class="uk-width-1-2 uk-card uk-card-body uk-card-default">
            <div>
                <span class="uk-text-lead">Personal Information</span>

                <div class="uk-margin">
                    <label class="uk-form-label" for="enrollment-username">Username <span class="field-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="enrollment-username" required minlength="6" data-bouncer-target="#username-error" maxlength="50" name="student[username]" type="text">
                    </div>
                    <div id="username-error"></div>
                </div>

                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-2">
                        <label class="uk-form-label" for="enrollment-password">Password <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input uk-width-expand" required id="enrollment-password" data-bouncer-target="#password-error" name="student[password]" type="password">
                        </div>
                        <div id="password-error"></div>
                    </div>

                    <div class="uk-width-1-2">
                        <label class="uk-form-label" for="enrollment-password-repeat">Password Repeat <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input uk-width-expand" required data-bouncer-target="#password-repeat-error" data-bouncer-password-match="#enrollment-password" id="enrollment-password-repeat" name="student[password_repeat]" type="password">
                        </div>
                        <div id="password-repeat-error"></div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="enrollment-full-name">Full Name <span class="field-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="enrollment-full-name" required data-bouncer-target="#full-name-error" maxlength="100" name="student[full_name]" type="text">
                    </div>
                    <div id="full-name-error"></div>
                </div>

                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="enrollment-personal-contact-number">Contact Number <span class="field-required">*</span></label>
                        <div class="uk-inline uk-width-expand">
                            <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                            <input class="uk-input uk-width-expand" id="enrollment-personal-contact-number" data-bouncer-target="#personal-contact-number-error" name="student[personal_contact_number]" required type="number">
                        </div>
                        <div id="personal-contact-number-error"></div>
                    </div>

                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="enrollment-skype">Skype <span class="field-required">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="enrollment-skype" name="student[skype]" type="text" required data-bouncer-target="#skype-error">
                        </div>
                        <div id="skype-error"></div>
                    </div>

                    <div class="uk-width-1-3">
                        <label class="uk-form-label" for="enrollment-email">E-Mail <span class="field-required">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="enrollment-email" name="student[email]" type="email" data-bouncer-target="#email-error" required>
                        </div>
                        <div id="email-error"></div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="enrollment-birthday">Birthday <span class="field-required">*</span></label><br>
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: calendar"></span>
                        <input class="uk-input" id="enrollment-birthday" name="student[birthday]" autocomplete="off" type="text" required data-bouncer-target="#birthday-error">
                    </div>
                    <div id="birthday-error"></div>
                </div>
            </div>
        </div>
        
        <div class="uk-width-1-2 uk-card uk-card-body uk-card-default">
            <span class="uk-text-lead">Schedule Preferences</span>
            <div class="uk-margin-small-top uk-width-small">
                <label class="uk-form-label" for="enrollment-start-date">Start Date<span class="field-required">*</span></label>
                <div class="uk-form-controls">
                    <input type="text" required autocomplete="off" id="enrollment-start-date" class="uk-input" name="start_date" data-bouncer-target="#start-date-error">
                    <div id="start-date-error"></div>
                </div>
            </div>
            <table class="uk-table uk-table uk-table-hover uk-table-divider">
                <thead>
                    <tr>
                        <th class="uk-table-shrink">&nbsp;</th>
                        <th class="uk-width-small">Day</th>
                        <th class="uk-width-small">Time</th>
                        <th class="uk-table-shrink">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @include('enroll.includes.enroll_pref_day', [
                        'timeSlots' => $timeSlots,
                        'days' => [
                            ['name' => 'Monday'],
                            ['name' => 'Tuesday'],
                            ['name' => 'Wednesday'],
                            ['name' => 'Thursday'],
                            ['name' => 'Friday'],
                            ['name' => 'Saturday'],
                            ['name' => 'Sunday']
                        ]
                    ])
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="uk-margin uk-text-right">
        <div id="enrollment-spinner" uk-spinner class="ajax-spinner" style="visibility: hidden"></div>
        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        <button class="uk-button uk-button-primary" type="submit">Continue</button>
    </div>
</form>

@endsection

@section('pageCSS')
    <link href="<?php echo url('/tail.DateTime-0.4.14/css/tail.datetime-default-blue.min.css') ?>" rel="stylesheet">
@endsection

@section('pageJavascript')
<script src="<?php echo url('/bouncer-1.4.5/dist/bouncer.polyfills.min.js') ?>"></script>
<script src="<?php echo url('/tail.DateTime-0.4.14/js/tail.datetime.min.js') ?>"></script>
<script>
    function removeSched(evt, button) {
        evt.preventDefault();
        var mainParent = button.parentNode.parentNode;

        mainParent.parentNode.removeChild(mainParent);
    }

    function toggleSched(evt, el) {
        var rootTr = el.parentNode.parentNode;
        var items = rootTr.querySelectorAll('select, button');

        if (el.checked) {
            rootTr.style = 'background: #ffd !important;'
            _.each(items, function(e) {
                e.disabled = false;
            });
            el.setAttribute('data-once-counter', 1);
        } else {
            rootTr.style = '';
            _.each(items, function(e) {
                e.disabled = true;
            });
        }
    }

    function addSched(evt, day) {
        evt.preventDefault();
        var unq = +new Date();
        var parent = document.createElement('div');
        parent.className = 'uk-flex uk-margin-small-top';

        var template = '<div class="uk-margin-small-right">'+
                '<select class="uk-select sched-slots" data-skip-disable data-bouncer-target="#error-'+ [day,unq].join('-') +'" required name="classroom_schedule_preference['+ day.toLowerCase() +'][slots][]">'+
                    '<option value="">Select time slot</option>'+
                    @foreach ($timeSlots as $timeSlot)
                        '<option value="{{ implode('|', $timeSlot) }}">'+
                            '{!! implode(' &#8212; ', $timeSlot); !!}'+
                        '</option>'+
                    @endforeach
                '</select>'+
                '<small id="error-'+ [day,unq].join('-') +'" class="error-float-away"></small>'+
            '</div>'+
            '<div>'+
                '<button class="uk-button uk-button-default uk-button-small" onclick="removeSched(event, this)" uk-tooltip="title: Remove">'+
                    '<span uk-icon="icon: minus"></span>'+
                '</button>'+
            '</div>';

            parent.innerHTML = template;

        var container = document.querySelector('#add-sched-'+ day);
        container.appendChild(parent);
    }

    window.onload = function () {
        var tdtBirthday = tail.DateTime("#enrollment-birthday", {
            timeFormat: false,
            today: false,
            weekStart: 1,
            position: "top",
            dateFormat: "d F YYYY",
            closeButton: false
        });

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var tdtStartDate = tail.DateTime("#enrollment-start-date", {
            timeFormat: false,
            today: true,
            dateStart: tomorrow,
            weekStart: 1,
            position: "top",
            dateFormat: "d F YYYY",
            closeButton: false
        });
        
        @include('enroll.includes.bouncer_init', [
            'varName' => 'fv',
            'formSelector' => '#enrollment-form'
        ])

        _.each(document.querySelectorAll('.sched-day-checkbox'), function (e) {
            e.addEventListener('click', function (evt) {
                fv.validate(evt.target);
            }, false);
        });

        var tdtValidate = function(field) {
            return function () {
                var e = document.getElementById('enrollment-'+ field);
                fv.validate(e);
            }
        };

        tdtBirthday.on('change', tdtValidate('birthday'));
        tdtBirthday.on('close', tdtValidate('birthday'));

        tdtStartDate.on('change', tdtValidate('start-date'));
        tdtStartDate.on('close', tdtValidate('start-date'))

        var formEnroll = document.getElementById('enrollment-form');
        var spinner = document.getElementById('enrollment-spinner');
        var formAjax = axios.create();

        formAjax.interceptors.request.use(function (r) {
            formEnroll.disableAllFields();
            spinner.style.visibility = 'visible';
            return r;
        });

        function enableForm() {
            formEnroll.enableAllFields();
            spinner.style.visibility = 'hidden';
        }

        formAjax.interceptors.response.use(function (r) {
            enableForm();
            return r;
        }, function (r) {
            enableForm();
            return Promise.reject(r);
        });

        var username = document.getElementById('enrollment-username');
        var email = document.getElementById('enrollment-email');

        function validateAvailability(what) {
            var el = document.getElementById('enrollment-'+ what);
            el.setAttribute('data-bouncer-'+ what +'-exists', '');
            fv.validate(document.getElementById('enrollment-'+ what));
            el.removeAttribute('data-bouncer-'+ what +'-exists');
        }

        /**
         * Just remove the contents on schedule time slot
         * It causes mass breakage
         */
        document.addEventListener('bouncerShowError', function (event) {
            var field = event.target;

            if (field.classList.contains('sched-slots')) {
                var errorBox = field.getAttribute('data-bouncer-target');
                document.querySelector(errorBox).style.visibility = 'hidden';
            }
        }, false);

        document.addEventListener('bouncerFormValid', function (evt) {
            var form = evt.target;
            var data = serialize(form);

            var checkAvailability = formAjax.post(url('/enroll/check-availability'), {
                username: username.value,
                email: email.value
            }).catch(function (error) {
                uikitNotif('danger');

                if (error.response && error.response.status == 422) {
                    var errors = error.response.data.errors;

                    if (errors.username) {
                        validateAvailability('username');
                    }

                    if (errors.email) {
                        validateAvailability('email');
                    }
                }
            }).finally(function () {
                formEnroll.enableAllFields();
                spinner.style.visibility = 'hidden';
            });

            checkAvailability.then(function (r) {
                if (r.status == 200 && r.data.success) {
                    formAjax.post(url('/enroll'), data).then(function (r) {
                        if (r.status == 200) {
                            document.getElementById('after-enroll-invoice').setAttribute('href', url('/invoice/download/') +'?id='+ r.data.invoice_id);
                            document.getElementById('after-enroll-profile').setAttribute('href', url('/students') +'/'+ r.data.student_id);
                            UIkit.modal('#enroll-done-modal').show();
                        } else {
                            uikitNotif('danger');
                        }
                    });
                } else {
                    uikitNotif('danger');
                }
            });
        }, false);
    };

    function uikitNotif(type, message) {
        var hasMessage = typeof message != 'undefined';
        var content = '';

        if (type == 'danger') {
            content = hasMessage ? message : "<span uk-icon='icon: warning'></span> Failed processing student's data";
        }

        UIkit.notification(content, type);
    }
</script>
@endsection