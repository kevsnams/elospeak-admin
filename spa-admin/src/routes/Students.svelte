<script>
import _ from 'underscore';
import {onMount} from 'svelte';
import axios from 'axios';
import jq from 'jquery';
import {
    UserPlusIcon,
    UserIcon,
    PhoneIcon
} from 'svelte-feather-icons';

let isInitiated = false, students = [], filteredStudents = [];

onMount(async () => {
    try {
        const xhr = await axios.get('./students');

        xhr.data.forEach((data) => {
            students = [...students, data];
        });
        
        filteredStudents = students;

        isInitiated = true;
    } catch (e) {
        // ERROR
    }
});

let keywords;
const searchStudent = _.debounce(async () => {
    if (keywords.length) {
        filteredStudents = students.filter((student) => {
            const regexp = new RegExp(`(${keywords})`, 'i');

            return regexp.test(student.full_name) 
                || regexp.test(student.username) 
                || regexp.test(student.email) 
                || regexp.test(student.skype);
        });
    } else {
        filteredStudents = students;
    }
}, 700);

let addForm, isAddSuccess = false, addErrors = [];

async function submitAddForm()
{
    const fields = jq('.t'), fbs = jq('[id^="fb-"');

    fbs.removeClass('invalid-feedback d-block');
    fbs.addClass('d-none');

    addErrors = [];

    const data = new FormData(addForm);

    fields.prop('disabled', true);
    fields.removeClass('is-invalid');
    try {
        const xhr = await axios.post('./students', data);

        if (xhr.data.success) {
            isAddSuccess = true;
            setTimeout(() => {
                jq('#add-modal').modal('hide');
                top.location.href = `#/student/?id=${xhr.data.id}`;
            }, 1000);
        }
    } catch (e) {
        if (e.response.status == 422) {
            hasErrors = true;
            for (let key in e.response.data.errors) {
                addErrors = [
                    ...addErrors,
                    e.response.data.errors[key][0]
                ];

                jq(`#add_${key}`).addClass('is-invalid');

                const fb = jq(`#fb-${key}`);
                fb.addClass('invalid-feedback d-block');
                fb.text(e.response.data.errors[key][0]);
            }
        }
    }

    fields.prop('disabled', false);
}
</script>

<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="display-4">Students</h2>
    </div>

    <div>
        <button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#add-modal">
            <UserPlusIcon /> Add Student
        </button>
    </div>
</div>

<div class="modal fade" id="add-modal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="text-muted af mb-2">
                    <span class="required">*</span> Required fields
                </span>

                {#if isAddSuccess}
                    <div class="alert alert-success mb-3">
                        Successfully created student. Redirecting...
                    </div>
                {/if}

                <form bind:this={addForm} novalidate>
                    <div class="form-group">
                        <label for="add_username">Username <span class="required">*</span></label>
                        <input type="text" class="form-control t" id="add_username" name="username">
                        <div id="fb-username" class="d-none"></div>
                    </div>

                    <div class="form-group">
                        <label for="add_password">Password <span class="required">*</span></label>
                        <input type="password" class="form-control t" id="add_password" name="password">
                        <div id="fb-password" class="d-none"></div>
                    </div>

                    <div class="form-group">
                        <label for="add_password_repeat">Password <span class="required">*</span></label>
                        <input type="password" class="form-control t" id="add_password_repeat" name="password_repeat">
                        <div id="fb-password_repeat" class="d-none"></div>
                    </div>

                    <div class="form-group">
                        <label for="add_full_name">Full Name <span class="required">*</span></label>
                        <input type="text" class="form-control t" id="add_full_name" name="full_name">
                        <div id="fb-full_name" class="d-none"></div>
                    </div>

                    <div class="form-group">
                        <label for="add_email">Email <span class="required">*</span></label>
                        <input type="email" class="form-control t" id="add_email" name="email">
                        <div id="fb-email" class="d-none"></div>
                    </div>

                    <div class="form-group">
                        <label for="add_skype">Skype <span class="required">*</span></label>
                        <input type="text" class="form-control t" id="add_skype" name="skype">
                        <div id="fb-skype" class="d-none"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" on:click={submitAddForm}>Create</button>
            </div>
        </div>
    </div>
</div>

{#if isInitiated}
    <input type="text" bind:value={keywords} class="form-control form-control-lg" on:keyup={searchStudent} placeholder="Search Student" />

    <table class="table mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Skype</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {#each filteredStudents as student}
                <tr>
                    <td>{student.id}</td>
                    <td>
                        <UserIcon />
                        <a href="#/student/?id={student.id}">
                            {student.full_name}
                        </a>
                    </td>
                    <td>{student.username}</td>
                    <td>
                        <PhoneIcon />
                        <a href="skype:{student.skype}?chat">
                            {student.skype}
                        </a>
                    </td>
                    <td>...</td>
                </tr>
            {/each}
        </tbody>
    </table>
{:else}
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
{/if}

<style>
.af {
    font-size: 0.9rem;
    display: block;
}
</style>