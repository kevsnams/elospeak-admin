<script>
export let day;
export let slots;
export let duration;

import {
    ChevronUpIcon,
    ChevronDownIcon,
    CheckIcon,
    XIcon
} from 'svelte-feather-icons';

import { createEventDispatcher } from 'svelte';

import { Settings } from 'settings';
import { lz } from 'util';

const dispatch = createEventDispatcher();

let showSlot = false, hour, minute = 0;

let cfgDuration = duration, cfgStart, cfgEnd;

$: if ($Settings !== null) {
    cfgStart = parseInt($Settings.CLASSROOM.start_hour);
    cfgEnd = parseInt($Settings.CLASSROOM.end_hour);

    hour = cfgStart;
}

function remove(index)
{
    dispatch('removeSlot', {
        day,
        index
    });
}

function save()
{
    const end = calculateEndTime();
    const start = {hour, minute};

    let hasConflict = false;

    for (let i = 0; i < slots.length; i++) {
        let _sStart = new Date();
        _sStart.setHours(slots[i].start.hour);
        _sStart.setMinutes(slots[i].start.minute);

        let _sEnd = new Date();
        _sEnd.setHours(slots[i].end.hour);
        _sEnd.setMinutes(slots[i].end.minute);

        let _nStart = new Date();
        _nStart.setHours(start.hour);
        _nStart.setMinutes(start.minute);

        let _nEnd = new Date();
        _nEnd.setHours(end.hour);
        _nEnd.setMinutes(end.minute);

        if (_nStart >= _sStart && _nStart <= _sEnd) {
            hasConflict = true;
            break;
        }

        if (_nEnd >= _sStart && _nEnd <= _sEnd) {
            hasConflict = true;
            break;
        }
    }

    if (hasConflict) {
        alert('The selected time slot is in conflict with another time slot. Please review');
        return;
    }

    dispatch('addSlot', {
        day,
        slot: {
            start, end
        }
    });

    showSlot = false;
    reset();
}

function calculateEndTime()
{
    const sMinutes = (hour * 60) + minute;
    const total = sMinutes + cfgDuration;

    const eHours = Math.floor(total / 60);
    const eMinutes = total % 60;

    return {
        hour: eHours,
        minute: eMinutes
    }
}

function close()
{
    showSlot = false;
    reset();
}

function reset()
{
    hour = cfgStart;
    minute = 0;
}

function change(operation, type)
{
    hour = hour || cfgStart;

    if (hour == cfgEnd && type == 'minute') {
        return;
    }

    if (operation == 'add') {
        if (type == 'hour') {
            if (hour >= cfgEnd) {
                hour = cfgStart;
                return;
            }

            hour += 1;

            if (hour == cfgEnd) {
                minute = 0;
            }
        } else {
            if (minute >= 59) {
                minute = 0;
                return;
            }

            minute += 1;
        }
    }

    if (operation == 'sub') {
        if (type == 'hour') {
            if (hour <= cfgStart) {
                minute = 0;
                hour = cfgEnd;
                return;
            }

            hour -= 1;
        } else {
            if (minute <= 0) {
                minute = 59;
                return;
            }

            minute -= 1;
        }
    }
}

function toggle()
{
    showSlot = !showSlot;
}

function ft(time)
{
    return `${lz(time.hour)}:${lz(time.minute)}`;
}
</script>

<div class="enroll timeslot">
    {#if !slots.length}
        <div class="empty">EMPTY</div>
    {:else}
        {#each slots as slot, i}
            <div class="slot d-flex align-items-center">
                <div class="w-100 mr-1">
                    {ft(slot.start)} - {ft(slot.end)}
                </div>
                <div class="w-auto pt-1">
                    <a href="javascript:;" on:click|preventDefault={() => {remove(i)}}>
                        <XIcon />
                    </a>
                </div>
            </div>
        {/each}
    {/if}

    {#if !showSlot}
        <button class="btn btn-primary mt-3" on:click={toggle}>Add Timeslot</button>
    {:else}
        <div class="time-control">
            <div class="text-left" style="font-size: .7rem;">
                <strong>Start Time</strong>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <div class="w-100 time">
                    <span class="ticker" on:click={() => {change('add', 'hour')}}>
                        <ChevronUpIcon />
                    </span>
                    <span class="number">
                        {lz(hour)}
                    </span>
                    <span class="ticker" on:click={() => {change('sub', 'hour')}}>
                        <ChevronDownIcon />
                    </span>
                </div>
                <div class="w-100 time">
                    <span class="ticker" on:click={() => {change('add', 'minute')}}>
                        <ChevronUpIcon />
                    </span>
                    <span class="number">
                        {lz(minute)}
                    </span>
                    <span class="ticker" on:click={() => {change('sub', 'minute')}}>
                        <ChevronDownIcon />
                    </span>
                </div>
                <div class="w-100">
                    <a href="javascript:;" on:click|preventDefault={save} class="control save">
                        <CheckIcon />
                    </a>
                </div>
                <div class="w-100">
                    <a href="javascript:;" on:click|preventDefault={close} class="control close">
                        <XIcon />
                    </a>
                </div>
            </div>
            <hr>
            <div class="text-left" style="font-size: .8rem;">
                Only from <em>{lz(cfgStart)}:00</em> to <em>{lz(cfgEnd)}:00</em>
                <br><br>
                <strong>End Time Calculation:</strong><br>
                Duration: {cfgDuration} minutes<br>
                = Start Time + Duration
                <br><br>
                <span class="badge badge-pill badge-info">Note</span> Leave at least 5 minutes after every slot so teachers will have enough time to prepare for their next class
            </div>
        </div>
    {/if}
</div>

<style>
.time {
    margin-right: 1rem;
}
.time span {
    display: block;
}
.time .ticker {
    cursor: pointer;
    background: rgb(226, 226, 226);
}
.time .ticker:hover {
    color: #fff;
    background: #007bff;
}
.time-control {
    margin-top: 1rem;
    margin-left: 1rem;
    margin-right: 1rem;
    padding: .5rem;
    border: 1px solid #ccc;
    border-radius: .355rem;
}
.time-control .control {
    font-size: 1.5em;
}

.timeslot {
    width: 100%;
    padding: 1rem 0;
    text-align: center;
}

.timeslot .empty {
    background: rgb(241, 241, 241);
}

.timeslot .slot {
    padding: 1rem;
}

:global(.enroll.timeslot:not(:last-child)) {
    border-right: 1px solid #ccc;
}
</style>