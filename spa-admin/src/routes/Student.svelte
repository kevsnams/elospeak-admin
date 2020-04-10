<script>
export let params = {};

import _ from 'lodash';
import axios from 'axios';

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
</script>

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
                    <a class="dropdown-item text-danger text-muted" href="javascript:;">
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