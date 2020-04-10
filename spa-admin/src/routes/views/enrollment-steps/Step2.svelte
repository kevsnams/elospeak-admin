<script>
export let student;
export let data;

import _ from 'lodash';
import Timeslot from './Timeslot.svelte';
import { createEventDispatcher } from 'svelte';

const dispatch = createEventDispatcher();
const dayKeys = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

let timeslots = _.has(data, 'timeslots') ? data.timeslots : {};

if (!_.has(data, 'timeslots')) {
    dayKeys.forEach((day) => {
        timeslots[day] = [];
    });
}

function handleAddSlot(event)
{
    timeslots[event.detail.day].push({
        start: event.detail.slot.start,
        end: event.detail.slot.end
    });

    timeslots[event.detail.day] = timeslots[event.detail.day];
}

function handleRemoveSlot(event)
{
    const day = event.detail.day;
    const index = event.detail.index;

    timeslots[day].splice(index, 1);
    timeslots[day] = timeslots[day];
}

function next()
{
    const keys = _.keys(timeslots);
    let allEmpty = true;

    for (let i in keys) {
        let day = keys[i];

        if (timeslots[day].length > 0) {
            allEmpty = false;
            break;
        }
    }

    if (!allEmpty) {
        dispatch('next', {
            timeslots
        });
    }
}

function back()
{
    dispatch('back', true);
}
</script>
<h1 class="display-4" style="font-size: 2rem;">Timeslots</h1>
<div class="alert alert-info">
    <strong>Instructions</strong>: Add timeslots for each day. This will be used in filling up classrooms within the enrollment period selected on the previous step
</div>
<div class="days d-flex justify-content-center">
    <div class="weekend">SUN</div>
    <div>MON</div>
    <div>TUE</div>
    <div>WED</div>
    <div>THU</div>
    <div>FRI</div>
    <div class="weekend">SAT</div>
</div>

<div class="timeslots d-flex justify-content-center">
    {#each dayKeys as day}
        <Timeslot {day} slots={timeslots[day]} duration={student.country.duration} on:addSlot={handleAddSlot} on:removeSlot={handleRemoveSlot}></Timeslot>
    {/each}
</div>
<hr>
<button class="btn btn-secondary" on:click|preventDefault={back}>&laquo; Back</button>
<button class="btn btn-primary" on:click|preventDefault={next}>Next &raquo;</button>

<style>
.days {
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}
.days div {
    width: 100%;
    padding: 1rem 0;
    text-align: center;
    font-size: 1.2rem;
}
.days div:not(:last-child) {
    border-right: 1px solid #ccc;
}
.days .weekend {
    color: #f03;
    font-weight: bold;
}
</style>