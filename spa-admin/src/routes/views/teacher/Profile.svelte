<script>
export let teacher;

import _ from 'lodash';
import axios from 'axios';
import { slide } from 'svelte/transition';
import { date_ymd } from 'util';

const fetchEducationalAttainment = axios.get('./teacher/educational-attainment');

let isSaving = false, success = false, fail = false, errors = [];
async function save(evt)
{
    isSaving = true, success = false, fail = false;

    const form = new FormData(evt.target);
    form.append('_method', 'PUT');
    
    try {
        await axios.post(`./teachers/${ teacher.id }`, form);
        success = true;
    } catch (e) {
        console.log(e.response.data);
        fail = true;

        if (e.response.data.errors) {
            errors = _.map(e.response.data.errors, (o) => {
                return _.replace(o[0], 'data.', '');
            });
        } else {
            errors = e.message;
        }
    } finally {
        isSaving = false;
    }

    return false;
}
</script>

{#if success}
    <div class="alert alert-success w-50 mx-auto my-4" transition:slide>
        <strong>Success:</strong> Updated teacher information!
    </div>
{/if}

{#if fail}
    <div class="alert alert-danger w-50 mx-auto my-4" transition:slide>
        {#if _.isString(errors)}
            { errors }
        {:else}
            The following errors occured:
            <ul>
                {#each errors as error}
                    <li>{ error }</li>
                {/each}
            </ul>
        {/if}
    </div>
{/if}

<div class="row">
    <div class="col-6">
        <form class="profile-form" novalidate on:submit|preventDefault={save}>
            <h4>Credentials</h4>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-email">Email</label>
                        <input type="email" class="form-control" id="profile-email" name="data[email]" value={ teacher.email } disabled={isSaving}>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="profile-username">Username</label>
                        <input type="text" class="form-control" id="profile-username" name="data[username]" value={ teacher.username } disabled={isSaving}>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" disabled={isSaving}>Update Credentials</button>
        </form>

        <form class="profile-form" novalidate on:submit|preventDefault={save}>
            <h4>Change Password</h4>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-password">Password</label>
                        <input type="text" class="form-control" id="profile-password" name="data[password]" disabled={isSaving}>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="profile-password_repeat">Repeat Password</label>
                        <input type="text" class="form-control" id="profile-password_repeat" name="data[password_repeat]" disabled={isSaving}>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" disabled={isSaving}>Update Password</button>
        </form>

        <form class="profile-form" novalidate on:submit|preventDefault={save}>
            <h4>Personal &amp Contact Information</h4>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-full_name">Full Name</label>
                        <input type="text" class="form-control" id="profile-full_name" name="data[full_name]" value={ teacher.full_name } disabled={isSaving}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-skype">Skype</label>
                        <input type="text" class="form-control" id="profile-skype" name="data[skype]" value={ teacher.skype } disabled={isSaving}>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="profile-educational_attainment">Educational Attainment</label>
                        {#await fetchEducationalAttainment}
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        {:then response}
                            <select class="custom-select" id="profile-educational_attainment" name="data[educational_attainment]" disabled={isSaving}>
                                {#each response.data as ea}
                                    <option value={ ea[0] } selected={ ea[0] == teacher.educational_attainment }>{ ea[1] }</option>
                                {/each}
                            </select>
                        {:catch}
                            <div class="alert alert-warning">
                                Failed to get educational attainment values
                            </div>
                        {/await}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-personal_contact_number">Personal Contact Number</label>
                        <input type="text" class="form-control" id="profile-personal_contact_number" name="data[personal_contact_number]" value={ teacher.personal_contact_number } disabled={isSaving}>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="profile-birthday">Birthday (YYYY-MM-DD)</label>
                        <input type="text" class="form-control" id="profile-birthday" name="data[birthday]" value={ teacher.birthday ? date_ymd(teacher.birthday) : '' } disabled={isSaving}>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-address">Address</label>
                        <input type="text" class="form-control" id="profile-address" name="data[address]" value={ teacher.address } disabled={isSaving}>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" disabled={isSaving}>Update Information</button>
        </form>
    </div>
    <div class="col-6">
        <form class="profile-form" novalidate on:submit|preventDefault={save}>
            <h4>Salary</h4>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="profile-salary">Weekday Salary (Mon - Fri)</label>
                        <input type="text" class="form-control" id="profile-salary" name="data[salary]" value={ teacher.salary } disabled={isSaving}>
                    </div>
                </div>
            
                <div class="col">
                    <div class="form-group">
                        <label for="profile-salary_weekend">Weekend Salary (Sat, Sun)</label>
                        <input type="text" class="form-control" id="profile-salary_weekend" name="data[salary_weekend]" value={ teacher.salary_weekend } disabled={isSaving}>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" disabled={isSaving}>Update Salary</button>
        </form>
    </div>
</div>
<style>
.profile-form {
    box-shadow: 0 0 0 1px rgba(43, 45, 80, 0.1),
        0 2px 5px 0 rgba(43, 45, 80, 0.08),
        0 1px 1.5px 0 rgba(0, 0, 0, 0.07),
        0 1px 2px 0 rgba(0, 0, 0, 0.08);
    border-radius: .355rem;
    padding: 1rem;
    margin-bottom: 1rem;
}
</style>