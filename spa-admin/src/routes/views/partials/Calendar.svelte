<script>
export let user;
export let checkAll = false;
export let range = [];

import jq from 'jquery';
import _ from 'lodash';
import axios from 'axios';

import { onMount, createEventDispatcher } from 'svelte';
import { slide } from 'svelte/transition';
import { date_ymd, date_hm, MONTHS_LONG, is_string_ymd, is_string_hm } from 'util';
import Pikaday from 'pikaday';

const dispatch = createEventDispatcher();

let checkboxes = [];

onMount(() => {
    jq('.calendar').on('change', '.c', function (evt) {
        let self = jq(evt.target);
        if (self.prop('checked')) {
            checkboxes.push(self.data('id'));
        } else {
            checkboxes.splice(checkboxes.indexOf(self.data('id')), 1);
        }

        checkboxes = checkboxes;

        dispatch('checkedItems', checkboxes);
    });

    
});

$: {
    checkboxes = [];
    jq('.c').prop('checked', checkAll).trigger('change');
}

const monthNames = MONTHS_LONG;

let start, end, calendar = [], classrooms = {}, fetchStatus = 'none';

$: {
    start = typeof range[0] == 'string' ? new Date(range[0]) : range[0];
    end = typeof range[1] == 'string' ? new Date(range[1]) : range[1];

    calendar = createCalendar();

    fillData();
}

function fillData()
{
    classrooms = {};
    let params = {
        start: date_ymd(start),
        end: date_ymd(end)
    };

    if (user.user_type == 'student') {
        params['student'] = user.id;
    } else {
        params['teacher'] = user.id
    }

    params['with'] = ['enrollment', 'teacher'];

    const fetcher = axios.get('./classrooms', {
        params
    });

    fetchStatus = 'fetching';

    fetcher.then(async (resp) => {
        resp.data.forEach((item) => {
            const key = date_ymd(new Date(item.start_raw));

            if (typeof classrooms[key] == 'undefined') {
                classrooms[key] = [];
            }

            classrooms[key].push({
                id: item.id,
                data: item,
                start: new Date(item.start_raw),
                end: new Date(item.end_raw),
                visible: false,
                isUpdating: false
            });
        });

        fetchStatus = 'none';
    });

    fetcher.catch((error) => {
        fetchStatus = 'error';
    });
}

function createCalendar()
{
    let dates = [];
    for (let month = start.getMonth(); month <= end.getMonth(); month++) {
        let obj = {
            month,
            weeks: []
        };

        let current = month == start.getMonth() ? start : new Date(start.getFullYear(), month, 1),
            ldom = new Date(current.getFullYear(), month + 1, 0),
            endCompare = month == end.getMonth() ? end : ldom,
            weeks = [], week = createEmptyWeek();

        while (current <= endCompare) {
            week[current.getDay()] = current;

            if (current.getDay() == 6) {
                weeks.push(week);
                week = createEmptyWeek();
            }

            current = new Date(current.getFullYear(), current.getMonth(), current.getDate() + 1);
            
            if (current > ldom || current > end) {
                weeks.push(week);
            }
        }

        obj.weeks = weeks;
        dates.push(obj);
    }
    
    return dates;
}

function show(key, id)
{
    const i = _.findIndex(classrooms[key], ['id', id]);
    classrooms[key][i].visible = !classrooms[key][i].visible;
}

async function update(key, id)
{
    const i = _.findIndex(classrooms[key], ['id', id]);
    classrooms[key][i].isUpdating = true;

    const date = jq(`.update-date-${id}`).val();
    const start = jq(`.update-time-start-${id}`).val();
    const end = jq(`.update-time-end-${id}`).val();

    if (!is_string_ymd(date)) {
        alert('Date is in incorrect format');
        return;
    }

    if (!is_string_hm(start) || !is_string_hm(end)) {
        alert('Time is in incorrect format');
        return;
    }

    try {
        const xhr = await axios.post(`./classrooms/${id}`, {
            _method: 'PUT',
            data: {
                start: `${date} ${start}`,
                end: `${date} ${end}`
            }
        });

        /**
         * TODO Probably don't trigger an update. Just move the classrooms[key].item out to
         * a different classrooms[key]. But idk
         */
        range = range;
    } catch (e) {
        alert('Error: Updating classroom failed');
    } finally {
        classrooms[key][i].isUpdating = false;
    }
}

function createEmptyWeek()
{
    return Array(7).fill(false);
}
</script>

<h1 class="text-center mt-5">
    {start.getDate()} {monthNames[start.getMonth()]} {start.getFullYear()} 
    &#8212; 
    {end.getDate()} {monthNames[end.getMonth()]} {end.getFullYear()}
</h1>

{#if fetchStatus == 'fetching'}
    <div class="text-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p>Fetching classrooms...</p>
    </div>
{:else if fetchStatus == 'error'}
    <div class="alert alert-warning">
        Failed fetching classrooms. Please retry after a few minutes.
    </div>
{/if}

<div class="calendar">
    {#each calendar as {month, weeks}}
        <h3 class="month">{monthNames[month]}</h3>

        <div class="d-flex justify-content-end header">
            <div class="column weekend">
                SUN
            </div>

            <div class="column">
                MON
            </div>

            <div class="column">
                TUE
            </div>

            <div class="column">
                WED
            </div>

            <div class="column">
                THU
            </div>

            <div class="column">
                FRI
            </div>

            <div class="column weekend">
                SAT
            </div>
        </div>
        
        {#each weeks as week}
            <div class="d-flex body">
                {#each week as day}
                    {#if day == false}
                        <div class="column no-day"></div>
                    {:else}
                        <div class="column day">
                            {#if classrooms[date_ymd(day)] && classrooms[date_ymd(day)].length}
                                <div class="text-right date mb-1">{day.getDate()}</div>

                                {#each classrooms[date_ymd(day)] as {data, start, end, visible, isUpdating}}
                                    <div class="d-flex align-items-center">
                                        <div class="w-auto mr-3">
                                            <input type="checkbox" data-id={data.id} class="form-control c">
                                        </div>
                                        <span class="classroom-item w-100" on:click={() => { show(date_ymd(day), data.id) }}>
                                            {date_hm(start)} &#8212; {date_hm(end)}
                                        </span>
                                    </div>

                                    {#if visible}
                                        <div class="detail-view" transition:slide>
                                            <div class="i">
                                                <label for="dv-teacher">Assigned Teacher</label>
                                                <span class="text-muted">
                                                    {#if data.teacher}
                                                        {data.teacher.full_name}
                                                    {:else}
                                                        NONE
                                                    {/if}
                                                </span>
                                            </div>

                                            <div class="i">
                                                <label for="dv-teacher">Date <span class="text-muted">(YYYY-MM-DD)</span></label>
                                                <input type="text" class="form-control form-control-sm f update-date-{ data.id }" value={date_ymd(start)}>
                                            </div>

                                            <div class="i">
                                                <label for="dv-teacher">Start Time <span class="text-muted">24 hour format (HH:MM)</span></label>
                                                <input type="text" class="form-control form-control-sm f update-time-start-{ data.id }" value={date_hm(start)}>
                                            </div>

                                            <div class="i">
                                                <label for="dv-teacher">End Time <span class="text-muted">24 hour format (HH:MM)</span></label>
                                                <input type="text" class="form-control form-control-sm f update-time-end-{ data.id }" value={date_hm(end)}>
                                            </div>
                                            
                                            {#if isUpdating}
                                                <button class="btn btn-primary btn-sm" type="button" disabled>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Loading...
                                                </button>
                                            {:else}
                                                <div class="i">
                                                    <button class="btn btn-primary btn-sm" on:click|preventDefault={() => { update(date_ymd(start), data.id) }}>Update</button>
                                                </div>
                                            {/if}
                                        </div>
                                    {/if}
                                {/each}

                            {:else}
                                <div class="text-right date">{day.getDate()}</div>
                            {/if}
                        </div>
                    {/if}
                {/each}
            </div>
        {/each}
    {/each}
</div>

<style>
.detail-view {
    box-shadow: 0 0 0 1px rgba(43, 45, 80, 0.1),
        0 2px 5px 0 rgba(43, 45, 80, 0.08),
        0 1px 1.5px 0 rgba(0, 0, 0, 0.07),
        0 1px 2px 0 rgba(0, 0, 0, 0.08);
    padding: .355rem;
    border-radius: .355rem;
    margin: .5rem 0;
}
.detail-view label {
    font-weight: bold;
    display: block;
    text-align: left;
}
.detail-view label, .detail-view .f {
    font-size: .7rem;
}
.detail-view .i:not(:last-child) {
    margin-bottom: 1rem;
}

.calendar {
    padding: .5rem;
    border-radius: .355rem;
    margin-top: 2rem;
    box-shadow: 0 0 0 1px rgba(43, 45, 80, 0.1),
        0 2px 5px 0 rgba(43, 45, 80, 0.08),
        0 1px 1.5px 0 rgba(0, 0, 0, 0.07),
        0 1px 2px 0 rgba(0, 0, 0, 0.08);
}

.calendar .column {
    width: 14.285714285714286%;
    text-align: center;
}

.calendar .header {
    border-bottom: 1px solid #ececec;
    padding-bottom: .5rem;
    padding-top: .5rem;
}

.calendar .header .column {
    font-size: 1.4rem;
}

.calendar .header .weekend {
    color: #f33;
}

.calendar .body .column {
    padding: 2rem 0;
    border-right: 1px solid #ececec;
    border-bottom: 1px solid #ececec;
}

.calendar .body .day {
    font-size: 1.5rem;
    min-height: 100px;
    padding: .5rem;
}

.calendar .body .day .date {
    font-size: 1rem;
    color: #212529;
}

.calendar .body .no-day {
    background: #ececec;
    min-height: 100px;
}
.calendar .month {
    background: #ececec;
    padding: 1rem 0;
    text-align: center;
}

.calendar .classroom-item {
    display: block;
    font-size: .9rem;
    color: inherit;
    cursor: pointer;
    text-align: left;
    padding: .3rem;
    background: rgb(140, 176, 255);
    border-radius: .355rem;
}
.calendar span.classroom-item:hover {
    background: rgb(27, 99, 253);
    color: #fff;
}
</style>