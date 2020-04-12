<script>
import _ from 'underscore';
import {onMount} from 'svelte';
import axios from 'axios';
import jq from 'jquery';
import {
    UserPlusIcon,
    MailIcon,
    PhoneIcon,
    UserIcon,
    UserXIcon
} from 'svelte-feather-icons';

let isInitiated = false, teachers = [], filteredTeachers = [];

onMount(async () => {
    try {
        const xhr = await axios.get('./teachers');

        xhr.data.forEach((data) => {
            teachers = [...teachers, data];
        });
        
        filteredTeachers = teachers;

        isInitiated = true;
    } catch (e) {
        // ERROR
    }
});

let keywords;
const searchTeacher = _.debounce(async () => {
    if (keywords.length) {
        filteredTeachers = teachers.filter((teacher) => {
            const regexp = new RegExp(`(${keywords})`, 'i');

            return regexp.test(teacher.full_name) 
                || regexp.test(teacher.username) 
                || regexp.test(teacher.email) 
                || regexp.test(teacher.skype);
        });
    } else {
        filteredTeachers = teachers;
    }
}, 700);

let educationalAttainment = [];
const eaXHR = axios.get('./teacher/educational-attainment');

eaXHR.then((r) => {
    educationalAttainment = r.data;
});

let addErrors = [], addSuccess = false;
async function submitAddTeacher(evt)
{
    const fields = jq('.t');
    const data = new FormData(evt.target);

    fields.prop('disabled', true);
    try {
        const xhr = await axios.post('./teachers', data);

        addErrors = [];
        addSuccess = true;

        setTimeout(() => {
            jq('#add-modal').modal('hide');
            top.location.href = '#/teacher/?id='+ xhr.data.id;
        }, 1000);
    } catch (e) {
        if (e.response.status == 422) {
            const errors = e.response.data.errors;

            addErrors = [];

            fields.removeClass('is-invalid');

            for (let i in errors) {
                jq(`#teacher-${i}`).addClass('is-invalid');
                addErrors = [...addErrors, errors[i][0]];
            }
        }
    }

    fields.prop('disabled', false);
    
    return false;
}

let tmp = {}, removeSuccess = false;
function showConfirmRemove(teacher)
{
    tmp = teacher;
    jq('#remove-modal').modal('show');
}

async function removeTeacher(id)
{
    try {
        const xhr = await axios.post(`./teachers/${id}`, {
            _method: 'DELETE'
        });

        removeSuccess = true;

        setTimeout(() => {
            top.location.reload();
        }, 1000);
    } catch (e) {
        // ERROR
        alert('Error: Unable to delete teacher');
    }
}
</script>
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="display-4">Teachers</h2>
    </div>

    <div>
        <button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#add-modal">
            <UserPlusIcon /> Add Teacher
        </button>
    </div>
</div>

{#if isInitiated}
    <input type="text" bind:value={keywords} class="form-control form-control-lg" on:keyup={searchTeacher} placeholder="Search Teacher" />

    <table class="table mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Skype</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {#each filteredTeachers as teacher}
                <tr>
                    <td>{teacher.id}</td>
                    <td>
                        <UserIcon />
                        <a href="#/teacher/{teacher.id}">
                            {teacher.full_name}
                        </a>
                    </td>
                    <td>
                        {teacher.username}
                    </td>
                    <td>
                        <PhoneIcon />
                        <a href="skype:{teacher.skype}?chat">
                            {teacher.skype}
                        </a>
                    </td>
                    <td>
                        <MailIcon />
                        <a href="mailto:{teacher.email}">
                            {teacher.email}
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-secondary" on:click|preventDefault={() => {showConfirmRemove(teacher)}}><UserXIcon /> Remove</button>
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>
{:else}
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
{/if}
<div class="modal fade" id="remove-modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove teacher</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {#if removeSuccess}
                    <div class="alert alert-success">
                        Successfully deleted teacher. Refreshing list...
                    </div>
                {/if}
                You are about to remove teacher <mark>{tmp.full_name}</mark>. Are you sure you want to continue with this?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" on:click|preventDefault={removeTeacher(tmp.id)}>Yes, continue</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Teacher</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="text-muted af mb-2">
                    <span class="required">*</span> Required fields
                </span>

                {#if addErrors.length > 0}
                    <div class="alert alert-danger">
                        <ul>
                            {#each addErrors as error}
                                <li>{error}</li>
                            {/each}
                        </ul>
                    </div>
                {/if}

                {#if addSuccess}
                    <div class="alert alert-success">
                        Successfully added teacher. Redirecting to teacher's page...
                    </div>
                {/if}

                <form novalidate on:submit|preventDefault={submitAddTeacher}>
                    <div class="form-group">
                        <label for="teacher-username">Username <span class="required">*</span></label>
                        <input type="text" class="form-control t" id="teacher-username" name="data[username]">
                    </div>

                    <div class="form-group">
                        <label for="teacher-email">Email <span class="required">*</span></label>
                        <input type="email" class="form-control t" id="teacher-email" name="data[email]">
                    </div>

                    <div class="d-flex mb-2">
                        <div class="w-auto mr-2">
                            <label for="teacher-password">Password <span class="required">*</span></label>
                            <input type="password" class="form-control t" id="teacher-password" name="data[password]">
                        </div>

                        <div class="w-auto">
                            <label for="teacher-password_repeat">Password Repeat<span class="required">*</span></label>
                            <input type="password" class="form-control t" id="teacher-password_repeat" name="data[password_repeat]">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="teacher-full_name">Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control t" id="teacher-full_name" name="data[full_name]">
                    </div>
                    
                    <div class="d-flex mb-2">
                        <div class="w-auto mr-2">
                            <label for="teacher-skype">Skype <span class="required">*</span></label>
                            <input type="text" class="form-control t" id="teacher-skype" name="data[skype]">
                        </div>

                        <div class="w-auto">
                            <label for="teacher-educational_attainment">Educational Attainment <span class="required">*</span></label>
                            <select class="custom-select t" id="teacher-educational_attainment" name="data[educational_attainment]">
                                {#each educationalAttainment as ea}
                                    <option value={ ea[0] }>{ ea[1] }</option>
                                {/each}
                            </select>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="w-auto mr-2">
                            <label for="teacher-salary">Weekday Salary (per class) <span class="required">*</span></label>
                            <input type="text" class="form-control t" id="teacher-salary" name="data[salary]">
                        </div>

                        <div class="w-auto">
                            <label for="teacher-salary">Weekend Salary (per class) <span class="required">*</span></label>
                            <input type="text" class="form-control t" id="teacher-salary" name="data[salary_weekend]">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success t">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.af {
    font-size: 0.9rem;
    display: block;
}
</style>