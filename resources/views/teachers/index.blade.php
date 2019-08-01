@extends('layouts.app')

@section('pageTitle', 'Teachers')
@section('pageHeader')
    <span class="uk-text-large">Teachers</span>
@endsection

@section('content')
<button class="uk-button uk-button-primary" id="add-teacher" uk-toggle="target: #create-modal"><span uk-icon="icon: plus"></span> Add Teacher</button>
<hr>
<table class="uk-table uk-table-divider">
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
                    <td><?php echo $teacher->username ?></td>
                    <td><?php echo $teacher->full_name ?></td>
                    <td><?php echo $teacher->skype ?></td>
                    <td>
                        Edit | Delete
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div id="create-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Create Teacher</h2>

        <form id="teacher-create" class="uk-form-stacked" action="<?php echo route('teachers.store') ?>" method="POST">
            @csrf
            <div class="uk-margin">
                <label class="uk-form-label" for="teacher-username">Username <span class="form-required">*</span></label>
                <div class="uk-form-controls">
                    <input class="uk-input" id="teacher-username" required minlength="6" maxlength="50" type="text" data-bouncer-target="#username-error" name="username">
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
                        <input class="uk-input" id="teacher-email" required type="email" name="email" data-bouncer-target="#email-error">
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
    tail.DateTime("#teacher-birthday", {
        timeFormat: false,
        today: false,
        weekStart: 1,
        position: "top",
        dateFormat: "F d, YYYY",
        closeButton: false
    });
    
    new Bouncer('#teacher-create', {
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
            }
        },
        messages: {
            passwordMismatch: 'Passwords do not match'
        }
    });
</script>
@endsection