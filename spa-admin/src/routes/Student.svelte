<script>
export let params = {};

import _ from 'lodash';
import axios from 'axios';
import jq from 'jquery';

import {
    SettingsIcon,
    AlertTriangleIcon
} from 'svelte-feather-icons';

import StudentProfile from './views/student/Profile.svelte';
import StudentEdit from './views/student/Edit.svelte';
import StudentEnrollments from './views/student/Enrollments.svelte';

let student;
async function fetcherInit()
{
    try {
        const xhr = await axios.get(`./students/${params.id}`, {
            params: {
                with: ['enrollments']
            }
        });
        student = xhr.data;
    } catch (e) {
        throw e.response.data;
    }
}

const pages = [
    {path: `#/student/${params.id}`, name: 'overview', component: StudentProfile},
    {path: `#/student/${params.id}/edit`, name: 'edit', component: StudentEdit},
    {path: `#/student/${params.id}/enrollments`, name: 'enrollments', component: StudentEnrollments}
];

const init = fetcherInit();

let page;

$: {
    if (params.page) {
        changePage(params.page);
    } else {
        changePage('overview');
    }
}

function changePage(name)
{
    page = _.find(pages, {name});
}

function showDeleteConfirm()
{
    jq('#delete-confirm').modal('show');
}

let isDeleting = false;
async function deleteStudent()
{
    isDeleting = true;

    try {
        await axios.post(`./students/${student.id}`, {
            _method: 'DELETE'
        });
        jq('#delete-confirm').modal('hide');
        
        setTimeout(() => {
            top.location.href = '#/students';
        }, 500);
    } catch (e) {
        alert('Error deleting student!');
    } finally {
        isDeleting = false;
    }
}
</script>

<div id="delete-confirm" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deleting student: Think before you proceed!</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you { _.repeat('really ', 5) } sure you want to delete this student?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" disabled={isDeleting}>Cancel</button>
                {#if isDeleting}
                    <button class="btn btn-danger" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                {:else}
                    <button type="button" class="btn btn-danger" on:click|preventDefault={deleteStudent}>Delete Student</button>
                {/if}
            </div>
        </div>
    </div>
</div>
{#await init}
    <div class="ml-auto mr-auto text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
{:then response}
    <div class="d-flex justify-content-between">
        <div>
            <h1>{student.full_name}</h1>
        </div>
        <div>
            <div class="dropdown dropleft">
                <button class="btn btn-dark" data-toggle="dropdown"><SettingsIcon /></button>

                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#/student/{student.id}/edit" on:click={() => changePage('edit')}>Edit Info</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger text-muted" href="javascript:;" on:click|preventDefault={showDeleteConfirm}>
                        <AlertTriangleIcon /> Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3">
        {#each _.reject(pages, ['name', 'edit']) as item}
            <li class="nav-item">
                <a class="nav-link" href="{ item.path }" class:active={item.name == page.name}>{ _.upperFirst(item.name) }</a>
            </li>
        {/each}
    </ul>

    <svelte:component this={page.component} user={student}></svelte:component>
{:catch e}
    <div class="alert alert-warning w-50 ml-auto mr-auto mt-5">
        Failed retrieving student, please try again.
    </div>
{/await}