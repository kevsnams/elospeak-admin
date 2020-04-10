<script>
export let user = {};

import Breadcrumb from './../partials/Breadcrumb.svelte';
import Calendar from './../partials/Calendar.svelte';

import {fly} from 'svelte/transition';
import {onMount} from 'svelte';
import Pikaday from 'pikaday';
import axios from 'axios';
import jq from 'jquery';
import _ from 'lodash';

const now = new Date();

let range = [
    new Date(now.getFullYear(), now.getMonth(), 1),
    new Date(now.getFullYear(), now.getMonth() + 1, 0)
];

let fieldDateFrom, fieldDateTo;
onMount(() => {
    const pickerFrom = new Pikaday({
        field: fieldDateFrom,
        defaultDate: range[0],
        setDefaultDate: true,
        onSelect: () => {
            pickerOnSelect(0, pickerFrom.getDate());
        }
    });

    const pickerTo = new Pikaday({
        field: fieldDateTo,
        defaultDate: range[1],
        setDefaultDate: true,
        onSelect: () => {
            pickerOnSelect(1, pickerTo.getDate());
        }
    });

    jq('[data-toggle="popover"]').popover();
});

function pickerOnSelect(dest, value)
{
    range[dest] = value;
}

const fetchTeachers = axios.get('./teachers');
const fetchClassroomStatus = axios.get('./classroom/status');

let checkedClassrooms = [], checkAll = false;

function checkedItems(event)
{
    checkedClassrooms = event.detail;
}

let classroomsTeacher, classroomsStatus;

let updatingClassrooms = false;

async function updateClassrooms()
{
    if (classroomsTeacher === "" && classroomsStatus === "") {
        return;
    }

    updatingClassrooms = true;
    const data = {
        ids: checkedClassrooms
    };

    if (classroomsTeacher !== '') {
        data['teacher'] = classroomsTeacher;
    }

    if (classroomsStatus !== '') {
        data['status'] = classroomsStatus;
    }

    try {
        const xhr = await axios.post('./classrooms/batch', {
            _method: 'PUT',
            data
        });
    } catch (e) {
        alert('Unexpected error: '+ e.message);
    } finally {
        updatingClassrooms = false;
    }
}
</script>

<div class="d-flex">
    <div class="mr-3">
        {#if user.has_active_enrollment}
            <button class="btn btn-primary" disabled data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Unable to make new enrollment. Student have an active enrollment">Enroll</button>
        {:else}
            <a href="#/enroll/{user.id}"class="btn btn-primary">Enroll</a>
        {/if}
    </div>
    <div>
        <button class="btn btn-primary">Create Demo Class</button>
    </div>
    <div class="d-flex ml-5 align-items-center mr-auto">
        <div class="mr-1">
            Filter from:
        </div>
        <div class="form-inline mr-1">
            <input type="text" readonly class="form-control" id="from-date" bind:this={fieldDateFrom}>
        </div>
        <div class="mr-1">
            to:
        </div>
        <div class="form-inline">
            <input type="text" readonly class="form-control" id="to-date" bind:this={fieldDateTo}>
        </div>
    </div>
</div>

<div class="d-flex calendar-controls">
    <div class="ctrl w-auto mr-3">
        <label for="class-select-all" class="l">Select All</label>
        <input type="checkbox" class="form-control form-control-sm" bind:checked={checkAll} id="class-select-all">
    </div>
    {#if checkedClassrooms.length > 0}
        <div class="ctrl w-auto" transition:fly="{{ x: 200, duration: 200 }}">
            <div class="d-flex">
                <div class="mr-3">
                    <label class="l" for="class-assign-to">Assign To</label>
                    {#await fetchTeachers}
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    {:then response}
                        <select class="custom-select custom-select-sm" disabled={updatingClassrooms} bind:value={classroomsTeacher} type="checkbox" id="class-assign-to">
                            <option value="">Select teacher...</option>
                            {#each response.data as teacher}
                                <option value="{teacher.id}">[{teacher.username}] {teacher.full_name}</option>
                            {/each}
                        </select>
                    {:catch}
                        <div class="alert alert-info">
                            Failed retrieving teachers
                        </div>
                    {/await}
                </div>

                <div class="mr-3">
                    <label class="l" for="class-assign-to">Status</label>
                    {#await fetchClassroomStatus}
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    {:then response}
                        <select class="custom-select custom-select-sm" disabled={updatingClassrooms} bind:value={classroomsStatus} type="checkbox" id="class-assign-to">
                            <option value="">Select status...</option>
                            {#each response.data as status}
                                <option value={status[0]}>{status[1]}</option>
                            {/each}
                        </select>
                    {:catch}
                        <div class="alert alert-info">
                            Failed retrieving status
                        </div>
                    {/await}
                </div>

                <div class="mr-3">
                    <label class="l">&nbsp;</label>
                    {#if updatingClassrooms}
                        <button class="btn btn-primary btn-sm" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </button>
                    {:else}
                        <button class="btn btn-primary btn-sm" on:click|preventDefault={updateClassrooms}>Update</button>
                    {/if}
                </div>
            </div>
        </div>
    {/if}
</div>

<Calendar {user} {range} on:checkedItems={checkedItems} {checkAll}></Calendar>

<style>
.l {
    display: block;
    font-size: 0.7em;
    font-weight: bold;
}
.calendar-controls .ctrl {
    padding: .5rem;
    border-radius: .355rem;
    margin-top: 2rem;
    box-shadow: 0 0 0 1px rgba(43, 45, 80, 0.1), 0 2px 5px 0 rgba(43, 45, 80, 0.08), 0 1px 1.5px 0 rgba(0, 0, 0, 0.07), 0 1px 2px 0 rgba(0, 0, 0, 0.08);
}
</style>