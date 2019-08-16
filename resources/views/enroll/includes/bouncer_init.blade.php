var {{ $varName }} = new Bouncer('{{ $formSelector }}', {
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
            return hasRule == '';
        },

        emailExists: function (field) {
            var hasRule = field.getAttribute('data-bouncer-email-exists');
            if (hasRule === null) return false;
            return hasRule == '';
        },

        checkOnce: function (field) {
            var hasRule = field.getAttribute('data-check-once');
            if (hasRule === null) return false;

            var checkboxes = document.querySelectorAll('[data-check-once="sched-day"]:checked');
            var hasError = checkboxes.length === 0;

            if (hasError) {
                var errbox = document.querySelector(field.getAttribute('data-bouncer-target'));
                var errfirst = errbox.firstElementChild;
                errbox.innerHTML = '';
            }

            return hasError;
        }
    },
    messages: {
        passwordMismatch: 'Passwords do not match',
        usernameExists: 'Username already exists',
        emailExists: 'E-Mail Address already exists',
        checkOnce: 'Select atleast 1 schedule'
    }
});