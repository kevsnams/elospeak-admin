<script>
export let params = {};

import _ from 'lodash';
import axios from 'axios';

import Profile from './views/teacher/Profile.svelte';
import Classrooms from './views/teacher/Classrooms.svelte';

const fetchTeacher = axios.get(`./teachers/${params.id}`);
let teacher, pages = [], page;

fetchTeacher.then((response) => {
    teacher = response.data;

    pages = [
        { label: 'Profile', component: Profile, name: 'profile' },
        { label: 'Classrooms', component: Classrooms, name: 'classrooms' }
    ];

    goPage(params.page ? params.page : 'profile');
});

$: {
    if (!params.page) {
        goPage('profile');
    }

    goPage(params.page);
}

function goPage(name)
{
    const index = _.findIndex(pages, { name });

    if (index < 0) {
        return;
    }

    page = pages[index];
}
</script>

{#await fetchTeacher}
    <div class="text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
{:then response}
    <h1>{teacher.full_name}</h1>

    <ul class="nav nav-tabs">
        {#each pages as _page}
            <li class="nav-item">
                <a class="nav-link" href="#/teacher/{ teacher.id }/{ _page.name }" class:active={ _page.name == page.name }>
                    { _page.label }
                </a>
            </li>
        {/each}
    </ul>

    <div class="mt-3">
        <svelte:component this={ page.component } { teacher }></svelte:component>
    </div>
{:catch}
    <div class="mx-auto mt-5">
        <div class="alert alert-warning">Failed fetching teacher information</div>
    </div>
{/await}