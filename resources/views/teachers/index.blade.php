@extends('layouts.app')

@section('pageTitle', 'Teachers')
@section('pageHeader')
    <span class="uk-text-large">Teachers</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-teacher" uk-toggle="target: #teacher-modal"><span uk-icon="icon: plus"></span> Add Teacher</button>
<hr>

@if (session('statusDelete'))
    <div uk-alert class="uk-alert-success">
        <a class="uk-alert-close" uk-close></a>
        {{ session('statusDelete') }}
    </div>
@endif

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
        <?php if ($teachers->isEmpty()): ?>
            <tr>
                <td colspan="4"><em>No Teachers Available</em></td>
            </tr>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <tr>
                    <td class="cursor-hand" uk-toggle="target: #teacher-show-<?php echo $teacher->id ?>" data-teacher-show="<?php echo $teacher->id ?>"><?php echo $teacher->username ?></td>
                    <td class="cursor-hand" uk-toggle="target: #teacher-show-<?php echo $teacher->id ?>" data-teacher-show="<?php echo $teacher->id ?>"><?php echo $teacher->full_name ?></td>
                    <td class="cursor-hand" uk-toggle="target: #teacher-show-<?php echo $teacher->id ?>" data-teacher-show="<?php echo $teacher->id ?>"><?php echo $teacher->skype ?></td>
                    <td>
                        <ul class="uk-iconnav">
                            <li><a href="javascript:;" data-teacher-modify="<?php echo $teacher->id ?>"><span uk-icon="icon: file-edit"></span> Modify</a></li>
                            <li><a href="javascript:;" data-teacher-delete="<?php echo $teacher->id ?>"><span uk-icon="icon: trash"></span> Delete</a></li>
                        </ul>
                    </td>
                </tr>
                <tr id="teacher-show-<?php echo $teacher->id ?>" hidden>
                    <td colspan="4">
                        <div class="uk-text-lead"><?php echo $teacher->full_name ?></div>
                        <hr>
                        <div class="uk-grid" uk-grid>
                            <div class="uk-width-1-3">
                                <dl class="uk-description-list">
                                    <dt>E-Mail</dt>
                                    <dd><?php echo $teacher->email ?></dd>

                                    <dt>Skype</dt>
                                    <dd><?php echo $teacher->skype ?></dd>

                                    <dt>Address</dt>
                                    <dd><?php echo $teacher->address ?></dd>
                                </dl>
                            </div>
                            <div class="uk-width-1-3">
                                <dl class="uk-description-list">
                                    <dt>Educational Attainment</dt>
                                    <dd><?php echo $teacher->educational_attainment_value ?></dd>

                                    <dt>Birthday</dt>
                                    <dd><?php echo date('j F Y', strtotime($teacher->birthday)) ?></dd>

                                    <dt>Age</dt>
                                    <dd><?php echo $teacher->age ?></dd>
                                </dl>
                            </div>
                            <div class="uk-width-1-3"><!--- PICTURE HERE ---></div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<form id="teacher-delete-form" method="POST">
    @csrf
    @method('DELETE')
</form>

<div id="teacher-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Create Teacher</h2>
        <form id="teacher-form" class="uk-form-stacked" method="POST">
            @csrf

            <input type="hidden" id="username-exists" value="">
            <input type="hidden" id="email-exists" value="">

            <div class="uk-margin">
                <label class="uk-form-label" for="teacher-username">Username <span class="form-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="teacher-username" data-bouncer-username-exists="#username-exists" required minlength="6" maxlength="50" type="text" data-bouncer-target="#username-error" name="username">
                    <div id="username-error"></div>
                </div>
            </div>

            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="teacher-password">Password <span class="form-required">*</span></label>
                    <div class="uk-inline uk-width-expand">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input uk-width-expand" id="teacher-password" required type="password" name="password" data-bouncer-target="#password-error">
                    </div>
                    <div id="password-error"></div>
                </div>

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="teacher-password-repeat">Password Repeat <span class="form-required">*</span></label>
                    <div class="uk-inline uk-width-expand">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input uk-width-expand" id="teacher-password-repeat" required data-bouncer-password-match="#teacher-password" type="password" name="password_repeat"  data-bouncer-target="#password-repeat-error">
                    </div>
                    <div id="password-repeat-error"></div>
                </div>
            </div>

            <div class="uk-grid-small" uk-grid>

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="teacher-full-name">Full Name <span class="form-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="teacher-full-name" required type="text" name="full_name" data-bouncer-target="#full-name-error">
                        <div id="full-name-error"></div>
                    </div>
                </div>

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="teacher-email">E-Mail <span class="form-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="teacher-email" data-bouncer-email-exists="#email-exists" required type="email" name="email" data-bouncer-target="#email-error">
                    </div>
                    <div id="email-error"></div>
                </div>

            </div>

            <div class="uk-grid-small" uk-grid>

                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="teacher-personal-contact-number">Contact Number <span class="form-required">*</span></label>
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                        <input class="uk-input" id="teacher-personal-contact-number" required type="number" name="personal_contact_number" data-bouncer-target="#personal-contact-number-error">
                    </div>
                    <div id="personal-contact-number-error"></div>
                </div>

                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="teacher-skype">Skype <span class="form-required">*</span></label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="teacher-skype" required type="text" name="skype" data-bouncer-target="#skype-error">
                        <div id="skype-error"></div>
                    </div>
                </div>

                <div class="uk-width-1-3">
                    <label class="uk-form-label" for="teacher-birthday">Birthday <span class="form-required">*</span></label>
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: calendar"></span>
                        <input class="uk-input" id="teacher-birthday" autocomplete="off" required type="text" name="birthday" data-bouncer-target="#birthday-error">
                    </div>
                    <div id="birthday-error"></div>
                </div>

            </div>

            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label class="uk-form-label" for="teacher-address">Address <span class="form-required">*</span></label>
                    <div class="uk-inline uk-width-expand">
                        <span class="uk-form-icon" uk-icon="icon: location"></span>
                        <input class="uk-input uk-width-expand" id="teacher-address" required type="text" name="address" maxlength="250" data-bouncer-target="#address-error">
                    </div>
                    <div id="address-error"></div>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label" for="teacher-educational-attainment">Educational Attainment <span class="form-required">*</span></label>
                <div class="uk-form-controls">
                    <select class="uk-select" id="teacher-educational-attainment" required name="educational_attainment" data-bouncer-target="#educational-attainment-error">
                        <option value="">Select...</option>
                        <?php foreach ($educationalAttainments as $code => $education): ?>
                            <option value="<?php echo $code ?>"><?php echo $education ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="educational-attainment-error"></div>
                </div>
            </div>

            <div class="uk-margin uk-text-right">
                <div uk-spinner class="ajax-spinner" style="visibility: hidden"></div>
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="submit">
                    Save
                </button>
            </div>
        </form>
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
    window.addEventListener('DOMContentLoaded', (event) => {

        var tdt = tail.DateTime("#teacher-birthday", {
            timeFormat: false,
            today: false,
            weekStart: 1,
            position: "top",
            dateFormat: "d F YYYY",
            closeButton: false
        });
        
        var fv = new Bouncer('#teacher-form', {
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
                    var selector = field.getAttribute('data-bouncer-username-exists');

                    if (!selector) return false;

                    var hiddenPlaceholder = document.querySelector(selector);

                    if (!hiddenPlaceholder) return false;

                    return hiddenPlaceholder.value === 'exists';
                },

                emailExists: function (field) {
                    var selector = field.getAttribute('data-bouncer-email-exists');

                    if (!selector) return false;

                    var hiddenPlaceholder = document.querySelector(selector);

                    if (!hiddenPlaceholder) return false;

                    return hiddenPlaceholder.value === 'exists';
                }
            },
            messages: {
                passwordMismatch: 'Passwords do not match',
                usernameExists: 'Username already exists',
                emailExists: 'E-Mail Address already exists'
            }
        });


        document.getElementById('add-teacher').addEventListener('click', function (evt) {
            // @TODO Add a prompt if modal is open and there are input values in the field(s)

            // Set action = to teacher.store
            document.getElementById('teacher-form').setAttribute('action', '<?php echo route('teachers.store') ?>');

            // Remove hidden teacher id field
            var teacherId = document.getElementById('hidden-teacher-id');
            if (teacherId) teacherId.parentNode.removeChild(teacherId);

            var methodSpoof = document.getElementById('hidden-method-spoof');
            if (methodSpoof) methodSpoof.parentNode.removeChild(methodSpoof);

            document.getElementById('teacher-form').reset();

            document.getElementById('teacher-password').setAttribute('required', 'required');
            var teacherPasswordRepeat = document.getElementById('teacher-password-repeat');
            teacherPasswordRepeat.setAttribute('required', 'required');
            teacherPasswordRepeat.setAttribute('data-bouncer-password-match', '#teacher-password');

            document.querySelector('#teacher-modal .uk-modal-title').innerHTML = 'Create Teacher';

        }, false);

        tdt.on('close', function () {
            fv.validate(document.getElementById('teacher-birthday'));
        });

        tdt.on('change', function () {
            fv.validate(document.getElementById('teacher-birthday'));
        });

        var formAjax = axios.create();

        function showAjaxLoading(param) {
            formElementsDisable('#teacher-form');
            document.querySelector('#teacher-form .ajax-spinner').style.visibility = 'visible';

            return param;
        }

        function hideAjaxLoading(param, returnPromise) {
            returnPromise = typeof returnPromise == 'undefined' ? true : false;
            formElementsDisable('#teacher-form', false);
            document.querySelector('#teacher-form .ajax-spinner').style.visibility = 'hidden';

            return returnPromise ? Promise.reject(param) : param;
        }

        formAjax.interceptors.request.use(showAjaxLoading, hideAjaxLoading);
        formAjax.interceptors.response.use(function (r) {
            hideAjaxLoading(r, false);

            return r;
        }, hideAjaxLoading);

        document.addEventListener('bouncerFormValid', function (evt) {
            var form = evt.target;
            var data = serialize(form);

            formAjax.post(form.getAttribute('action'), data).then(function (r) {
                if (r.status == 200 && r.data.success) {
                    UIkit.notification("<span uk-icon='icon: check'></span> Successfully "+ (r.data.method == 'update' ? 'updated' : 'created') +" a teacher", 'success');

                    form.reset();

                    setTimeout(function() {
                        UIkit.modal('#teacher-modal').hide();
                        window.location.reload();
                    }, 500);
                }
            }).catch(function (error) {
                if (error.response && error.response.status == 422) {
                    UIkit.notification("<span uk-icon='icon: warning'></span> Failed processing teacher's data", "danger");

                    _.each(error.response.data.errors, function (v, i) {
                        var firstError = v[0];
                        

                        // Checks if `username` or `email` is in the error and also checks if 'taken' is part of the first error message
                        if ((i == 'username' || i == 'email') && /taken/.test(firstError)) {
                            // Trigger usernameExists() validation error
                            document.getElementById(i +'-exists').value = 'exists';

                            // Validate that field
                            fv.validate(document.querySelector('[name="'+ i +'"]'));

                            // Remove the trigger
                            document.getElementById(i +'-exists').value = '';

                            // I know, it's st00pid.
                        }
                    });
                } else {
                    // server timeout?
                }
            });
        }, false);

        var showTeacherButtons = document.querySelectorAll('[data-teacher-show]');
        var modifyTeacherButtons = document.querySelectorAll('[data-teacher-modify]');
        var deleteTeacherButtons = document.querySelectorAll('[data-teacher-delete]');

        var teacherForm = document.getElementById('teacher-form');

        _.each(modifyTeacherButtons, function (e) {
            e.addEventListener('click', function (evt) {
                var button = evt.target;
                var teacherId = button.getAttribute('data-teacher-modify');

                teacherForm.setAttribute('action', '<?php echo route('teachers.index') ?>/'+ teacherId);

                document.querySelector('#teacher-modal .uk-modal-title').innerHTML = 'Update Teacher Details';

                var teacherIdField = document.getElementById('hidden-teacher-id');

                document.getElementById('teacher-password').removeAttribute('required');
                var teacherPasswordRepeat = document.getElementById('teacher-password-repeat');
                teacherPasswordRepeat.removeAttribute('required');
                teacherPasswordRepeat.removeAttribute('data-bouncer-password-match');

                // If teacher id hidden field DOES NOT exists
                if (!teacherIdField) {
                    // Create input hidden element, with teacher id value
                    var e = document.createElement('input');
                    e.id = 'hidden-teacher-id';
                    e.name = 'id';
                    e.type = 'hidden';
                    e.value = teacherId;

                    teacherForm.appendChild(e);
                } else {
                    // Otherwise, just update the value
                    teacherIdField.name = 'id';
                    teacherIdField.value = teacherId;
                }

                // Add the method spoofer for PUT request
                var methodSpoof = document.getElementById('hidden-method-spoof');

                if (!methodSpoof) {
                    var e = document.createElement('input');
                    e.id = 'hidden-method-spoof';
                    e.name = "_method";
                    e.type = 'hidden';
                    e.value = 'PUT';

                    teacherForm.appendChild(e);
                } else {
                    methodSpoof.name = '_method';
                    methodSpoof.value = 'PUT';
                }

                UIkit.modal('#teacher-modal').show();

                formAjax.get('<?php echo route('teachers.index') ?>/'+ teacherId, serialize(teacherForm)).then(function (r) {
                    _.each(r.data, function (value, key) {
                        var field = document.querySelector('[name="'+ key +'"]');
                        var _value = value;

                        if (key == 'birthday') _value = moment(value).format('DD MMMM YYYY');

                        if (field) field.value = _value;
                    });
                });
            });
        });

        var deleteTeacherForm = document.getElementById('teacher-delete-form');
        _.each(deleteTeacherButtons, function (e) {
            e.addEventListener('click', function (evt) {
                var button = evt.target;
                var teacherId = button.getAttribute('data-teacher-delete');

                UIkit.modal.confirm('Are you sure you want to delete this teacher?').then(function() {
                    deleteTeacherForm.setAttribute('action', '<?php echo route('teachers.index') ?>/'+ teacherId);
                    deleteTeacherForm.submit();
                }, function() {
                    deleteTeacherForm.removeAttribute('action');
                });
            });
        });
    });
</script>
@endsection