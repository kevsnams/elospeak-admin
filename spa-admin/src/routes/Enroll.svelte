<script>
export let params = {};

import axios from 'axios';
import Breadcrumb from './views/partials/Breadcrumb.svelte';
import {pop} from 'svelte-spa-router'

let student;
async function fetcherInit()
{
    try {
        const xhr = await axios.get(`./students/${params.id}`, {
            params: {
                with: ['country']
            }
        });
        student = xhr.data;

        if (student.has_active_enrollment) {
            pop();
        }
    } catch (e) {
        throw e.response.data;
    }
}

const init = fetcherInit();

import Step1 from './views/enrollment-steps/Step1.svelte';
import Step2 from './views/enrollment-steps/Step2.svelte';
import Step3 from './views/enrollment-steps/Step3.svelte';

const steps = [
    { step: 1, component: Step1 },
    { step: 2, component: Step2 },
    { step: 3, component: Step3 },
];

let path = [], cursor = 0, current = steps[cursor];

$: if (student) {
    path = [
        'Home',
        { label: 'Students', link: '#/students' },
        { label: student.full_name, link: `#/student/${student.id}` },
        'Enroll'
    ];
}

let data = {};

function save(event)
{
    for (let key in event.detail) {
        data[key] = event.detail[key];
    }

    cursor += 1;
    current = steps[cursor];
}

function back()
{
    cursor -= 1;
    current = steps[cursor];
}
</script>

{#await init}
    <div class="ml-auto mr-auto text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
{:then response}
    <h1>
        <a href="#/student/{student.id}">{student.full_name}</a> &#8212; Enroll : <span class="step">Step {current.step}/{steps.length}</span>
    </h1>
    <hr>
    <Breadcrumb {path}></Breadcrumb>
    <svelte:component this={current.component} on:next={save} {student} {data} on:back={back}></svelte:component>
{:catch error}
    <div class="alert alert-warning w-50 ml-auto mr-auto mt-5">
        Failed retrieving student, please try again.
    </div>
{/await}

<style>
.step {
    color: #9b9b9b;
}
</style>