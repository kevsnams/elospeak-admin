@extends('layouts.app')

@section('pageTitle', 'Teachers')
@section('pageHeader')
    <span class="uk-text-large">Teachers</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-teacher" uk-toggle="target: #teacher-modal"><span uk-icon="icon: plus"></span> Add Teacher</button>
<hr>
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
                    <td class="cursor-hand" data-show-teacher="<?php echo $teacher->id ?>"><?php echo $teacher->username ?></td>
                    <td class="cursor-hand" data-show-teacher="<?php echo $teacher->id ?>"><?php echo $teacher->full_name ?></td>
                    <td class="cursor-hand" data-show-teacher="<?php echo $teacher->id ?>"><?php echo $teacher->skype ?></td>
                    <td>
                    <ul class="uk-iconnav">
                        <li><a href="javascript:;" onclick="teacherModify(<?php echo $teacher->id ?>)"><span uk-icon="icon: file-edit"></span> Modify</a></li>
                        <li><a href="#"><span uk-icon="icon: trash"></span> Delete</a></li>
                    </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

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
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input" id="teacher-password" required type="password" name="password" data-bouncer-target="#password-error">
                    </div>
                    <div id="password-error"></div>
                </div>

                <div class="uk-width-1-2">
                    <label class="uk-form-label" for="teacher-password-repeat">Password Repeat <span class="form-required">*</span></label>
                    <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input class="uk-input" id="teacher-password-repeat" required data-bouncer-password-match="#teacher-password" type="password" name="password_repeat"  data-bouncer-target="#password-repeat-error">
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
                        <input class="uk-input" id="teacher-birthday" required type="text" name="birthday" data-bouncer-target="#birthday-error">
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

    function teacherModify(id) {
        var form = document.getElementById('teacher-form');
        form.setAttribute('action', '<?php echo route('teachers.index') ?>/'+ id);

        var teacherId = document.getElementById('hidden-teacher-id');

        // If teacher id hidden field DOES NOT exists
        if (!teacherId) {
            // Create input hidden element, with teacher id value
            var e = document.createElement('input');
            e.id = 'hidden-teacher-id';
            e.name = 'id';
            e.type = 'hidden';
            e.value = id;

            form.appendChild(e);
        } else {
            // Otherwise, just update the value
            teacherId.name = 'id';
            teacherId.value = id;
        }

        // Add the method spoofer for PUT request
        var methodSpoof = document.getElementById('hidden-method-spoof');

        if (!methodSpoof) {
            var e = document.createElement('input');
            e.id = 'hidden-method-spoof';
            e.name = "_method";
            e.type = 'hidden';
            e.value = 'PUT';

            form.appendChild(e);
        } else {
            methodSpoof.name = '_method';
            methodSpoof.value = 'PUT';
        }

        UIkit.modal('#teacher-modal').show();
    }

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
        // Set action = to teacher.store
        document.getElementById('teacher-form').setAttribute('action', '<?php echo route('teachers.store') ?>');

        // Remove hidden teacher id field
        var teacherId = document.getElementById('hidden-teacher-id');
        if (teacherId) teacherId.parentNode.removeChild(teacherId);

        var methodSpoof = document.getElementById('hidden-method-spoof');
        if (methodSpoof) methodSpoof.parentNode.removeChild(methodSpoof);
    });

    tdt.on('close', function () {
        fv.validate(document.getElementById('teacher-birthday'));
    });

    tdt.on('change', function () {
        fv.validate(document.getElementById('teacher-birthday'));
    });

    document.addEventListener('bouncerFormValid', function (evt) {
        var form = evt.target;
        var data = serialize(form);

        axios.post(form.getAttribute('action'), data).then(function (r) {
            if (r.status == 200) {
                UIkit.notification("<span uk-icon='icon: check'></span> Successfully created a teacher", 'success');

                form.reset();

                setTimeout(function() {
                    UIkit.modal('#teacher-modal').hide();
                    window.location.reload();
                }, 500);
            }
        }).catch(function (error) {
            if (error.response && error.response.status == 422) {
                UIkit.notification("<span uk-icon='icon: warning'></span> Failed creating a teacher", "danger");

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

    document.querySelector('[data-show-teacher]').addEventListener('click', function (evt) {
        evt.preventDefault();

        var field = evt.target;
        var teacherId = field.getAttribute('data-show-teacher');

        console.log(teacherId);
    });
</script>
@endsection