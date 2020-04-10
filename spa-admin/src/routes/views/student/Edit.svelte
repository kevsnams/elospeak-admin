<script>
export let user;

import SubmitButton from './../partials/SubmitButton.svelte';
import Breadcrumb from './../partials/Breadcrumb.svelte';

import _ from 'lodash';
import {onMount} from 'svelte';
import axios from 'axios';
import jq from 'jquery';
import Pikaday from 'pikaday';
import {
    KeyIcon,
    UserIcon,
    InfoIcon,
    HelpCircleIcon
} from 'svelte-feather-icons';

const path = [
    'Home',
    { label: 'Students', link: `#/students` },
    { label: user.full_name, link: `#/student/${user.id}` },
    'Edit Info'
];

let birthday;

onMount(() => {
    const now = new Date();
    const picker = new Pikaday({
        field: birthday,
        yearRange: [now.getFullYear() - 80, now.getFullYear()]
    });

    jq('[data-toggle="popover"]').popover();
});

let countries = [];
axios.get('./countries', {
    params: {
        is_defined: true
    }
}).then((resp) => {
    countries = resp.data;
});

let status = {
    credentials: {
        isLoading: false,
        isSuccess: false,
        isFail: false,
        buttonText: 'Update Credentials',
        successText: 'Successfully updated credentials',
        failText: ''
    },

    password: {
        isLoading: false,
        isSuccess: false,
        isFail: false,
        buttonText: 'Update Password',
        successText: 'Successfully updated password',
        failText: ''
    },

    info: {
        isLoading: false,
        isSuccess: false,
        isFail: false,
        buttonText: 'Update Personal Information',
        successText: 'Successfully updated personal information',
        failText: ''
    }
}

async function update(form, statusKey)
{
    const fields = form.querySelectorAll('input,select');
    const data = new FormData(form);

    status[statusKey].isLoading = true;

    fields.forEach((field) => {
        field.disabled = true;
    });

    try {
        await axios.post(`./students/${user.id}`, data);

        status[statusKey].isSuccess = true;
        status[statusKey].isFail = false;

        document.querySelectorAll('.is-invalid').forEach((field) => {
            field.classList.remove('is-invalid');
        });
    } catch (e) {
        status[statusKey].isSuccess = false;
        status[statusKey].isFail = true;

        let errors = [];
        _.forEach(e.response.data.errors, (value, key) => {
            let name = key.replace('.', '\\[') +'\\]';
            let text = value[0].replace(/data\./g, '');

            document.querySelector(`[name="${name}"]`).classList.add('is-invalid');

            errors.push(text);
        });

        status[statusKey].failText = errors;
    } finally {
        status[statusKey].isLoading = false;
        fields.forEach((field) => {
            field.disabled = false;
        });
    }
}
</script>
<Breadcrumb {path}></Breadcrumb>
<div class="mx-auto w-50">
    <div class="accordion" id="student-accordion">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <span class="adjust-icon"><UserIcon /></span>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#student-form-1">
                        Credentials
                    </button>
                </h2>
            </div>

            <div id="student-form-1" class="collapse" data-parent="#student-accordion">
                <div class="card-body">
                    <form novalidate class="student-form" on:submit|preventDefault={(e) => { update(e.target, 'credentials') }}>
                        <input type="hidden" name="_method" value="PUT" />
                        <input type="hidden" name="type" value="credentials" />
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="username">Username</label>
                                <input type="text" class="form-control form-control-sm" id="username" name="data[username]" value={user.username}>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="data[email]" value={user.email}>
                            </div>
                        </div>
                        
                        <SubmitButton {...status['credentials']}></SubmitButton>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <span class="adjust-icon"><KeyIcon /></span>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#student-form-2">
                        Password
                    </button>
                </h2>
            </div>

            <div id="student-form-2" class="collapse" data-parent="#student-accordion">
                <div class="card-body">
                    <form novalidate class="student-form" on:submit|preventDefault={(e) => { update(e.target, 'password') }}>
                        <input type="hidden" name="_method" value="PUT" />
                        <input type="hidden" name="type" value="password" />
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control form-control-sm" id="new_password" name="data[new_password]">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="new_password_repeat">Repeat New Password</label>
                                <input type="password" class="form-control form-control-sm" id="new_password_repeat" name="data[new_password_repeat]">
                            </div>
                        </div>
                        
                        <SubmitButton {...status['password']}></SubmitButton>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <span class="adjust-icon"><InfoIcon /></span>
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#student-form-3">
                        Personal Information
                    </button>
                </h2>
            </div>

            <div id="student-form-3" class="collapse" data-parent="#student-accordion">
                <div class="card-body">
                    <form novalidate class="student-form" on:submit|preventDefault={(e) => { update(e.target, 'info') }}>
                        <input type="hidden" name="_method" value="PUT" />
                        <input type="hidden" name="type" value="info" />
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control form-control-sm" id="full_name" name="data[full_name]" value={user.full_name}>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="birthday">Birthday</label>
                                <input type="text" class="form-control form-control-sm" id="birthday" placeholder="YYYY-MM-DD" readonly name="data[birthday]" bind:this={birthday} value={user.birthday}>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col">
                                <label for="personal_contact_number">Personal Contact Number</label>
                                <input type="text" class="form-control form-control-sm" id="personal_contact_number" name="data[personal_contact_number]" value={user.personal_contact_number}>
                            </div>

                            <div class="form-group col">
                                <label for="skype">Skype ID</label>
                                <input type="text" class="form-control form-control-sm" id="skype" name="data[skype]" value={user.skype}>
                            </div>

                            <div class="form-group col">
                                <label for="country" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Countries that are not listed in this field means that its prices per class, and currency is not yet defined. Go to Settings &raquo; Countries to configure">
                                    Country <span class="text-primary"><HelpCircleIcon /></span>
                                </label>
                                <select class="custom-select form-control form-control-sm" id="country" name="data[country_code]">
                                    {#each countries as country}
                                        <option value={country.code}>{country.name}</option>
                                    {/each}
                                </select>
                            </div>
                        </div>
                        
                        <SubmitButton {...status['info']}></SubmitButton>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.adjust-icon {
    font-size: 1.5rem;
}
.student-form label {
    font-size: .8rem;
    font-weight: bold;
}
</style>
