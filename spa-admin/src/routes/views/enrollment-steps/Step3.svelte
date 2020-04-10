<script>
export let student;
export let data;

import _ from 'lodash';
import axios from 'axios';
import {onMount, createEventDispatcher} from 'svelte';

import { lz } from 'util';

const dispatch = createEventDispatcher();

let classes;

onMount(() => {
    classes = calculateClasses(new Date(data.period.start), new Date(data.period.end), data.timeslots);
});

function calculateClasses(start, end, slots)
{
    const days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

    let weekdays_qty = 0,
        weekends_qty = 0,
        classes_total = 0,
        amount_total = 0,
        weekdays_total = 0,
        weekends_total = 0,
        items = [];

    for (let current = start; current <= end; current.setDate(current.getDate() + 1)) {
        const isWeekend = current.getDay() == 0 || current.getDay() == 6;
        const isWeekday = !isWeekend;
        const day = days[current.getDay()];

        let dayItems = [];

        _.forEach(slots[day], (slot) => {
            if (isWeekday) {
                weekdays_qty++;
            }

            if (isWeekend) {
                weekends_qty++;
            }

            classes_total++;

            const start = [lz(slot.start.hour), lz(slot.start.minute)].join(':'),
                end = [lz(slot.end.hour), lz(slot.end.minute)].join(':');

            dayItems.push({
                start,
                end,
                price: isWeekday ? student.country.price : student.country.price_weekend
            });
        });

        if (dayItems.length) {
            items.push({
                date: [current.getFullYear(), lz(current.getMonth() + 1), lz(current.getDate())].join('-'),
                slots: dayItems
            });
        }
    }

    weekdays_total = weekdays_qty * student.country.price;
    weekends_total = weekends_qty * student.country.price_weekend;
    amount_total = weekdays_total + weekends_total;

    return {
        classes: {
            items,
            total: classes_total
        },

        amount: {
            total: amount_total,
            weekdays: {
                qty: weekdays_qty,
                total: weekdays_total
            },
            weekends: {
                qty: weekends_qty,
                total: weekends_total
            }
        }
    };
}

let isSaving = false, procMessage = [];

async function enroll()
{
    procMessage = [];
    isSaving = true;

    procMessage = ['alert alert-info', 'Preparing enrollment data...'];

    let dates = [];
    _.forEach(classes.classes.items, (item) => {
        _.forEach(item.slots, (slot) => {
            dates.push({
                start: `${item.date} ${slot.start}`,
                end: `${item.date} ${slot.end}`
            });
        });
    });

    try {
        procMessage = ['alert alert-info', 'Processing enrollment...'];
        const procEnrollment = await axios.post('./enrollments', {
            data: {
                dates,
                student_id: student.id,
                start: data.period.start,
                end: data.period.end
            }
        });

        procMessage = ['alert alert-success', `Successfully created an enrollment. <a href="#/student/${student.id}/enrollments/"><strong>View Enrollments</strong></a> or <a href="./enrollment/pdf/${procEnrollment.data.id}"><strong>Download Invoice (PDF)</strong></a>`];
    } catch (e) {
        let responseMessage = e.message;

        if (e.isAxiosError) {
            responseMessage = `HTTP ERROR ${e.response.status} ${e.response.statusText}`;
        }

        procMessage = ['alert alert-danger', `An error occured: ${responseMessage}.`];
    } finally {
        isSaving = false;
    }
}

function back()
{
    dispatch('back', true);
}

</script>

<h1 class="display-4" style="font-size: 2rem;">Preview Enrollment</h1>
<hr>
<div class="d-flex">
    <div class="w-50 mr-3">
        <div>
            <h4>Enrollment Period</h4>
            <div class="row ml-5">
                <div class="col">
                    <strong>Start:</strong>
                </div>
                <div class="col">
                    {data.period.start}
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <strong>End:</strong>
                </div>
                <div class="col">
                    {data.period.end}
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h4>Price</h4>
            <div class="row ml-5">
                <div class="col">
                    <strong>Country</strong>
                </div>
                <div class="col">
                    {student.country.name} ({student.country.code})
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <strong>Currency</strong>
                </div>
                <div class="col">
                    {student.country.currency_code}
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <strong>Price/class (Weekday)</strong>
                </div>
                <div class="col">
                    {student.country.price}
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <strong>Price/class (Weekend)</strong>
                </div>
                <div class="col">
                    {student.country.price_weekend}
                </div>
            </div>
        </div>
    </div>

    <div class="w-50">
        {#if _.isObject(classes)}
            <h4>Amount</h4>

            <div class="row ml-5">
                <div class="col">
                    <strong>Total # classes</strong>
                </div>
                <div class="col">
                    {classes.classes.total}
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <span class="text-muted ml-5"># weekday classes</span>
                </div>
                <div class="col">
                    {classes.amount.weekdays.qty} = {student.country.currency_code} {classes.amount.weekdays.total}
                </div>
            </div>

            <div class="row ml-5">
                <div class="col">
                    <span class="text-muted ml-5"># weekend classes</span>
                </div>
                <div class="col">
                    {classes.amount.weekends.qty} = {student.country.currency_code} {classes.amount.weekends.total}
                </div>
            </div>

            <div class="row total">
                <div class="col text-right">
                    <strong style="font-size: 1.5rem;">Total</strong>
                </div>
                <div class="col">
                    <span class="total-amount">
                        {student.country.currency_code} {classes.amount.total}
                    </span>
                </div>
            </div>

            <div>
                <button class="btn btn-secondary" on:click|preventDefault={back} disabled={isSaving}>&laquo; Back</button>

                {#if isSaving}
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                {:else}
                    <button class="btn btn-primary" on:click|preventDefault={enroll}>Enroll &amp; Create Invoice &raquo;</button>
                {/if}
            </div>

            <div class="mt-5">
                {#if procMessage.length}
                    <div class="{procMessage[0]}">
                        {@html procMessage[1]}
                    </div>
                {/if}
            </div>
        {/if}
    </div>
</div>

{#if _.isObject(classes)}
    <h4 class="mt-5">Classroom Schedule</h4>
    <table class="table">
        <tbody>
            {#each classes.classes.items as item}
                <tr>
                    <td>
                        <span class="item-date">{item.date}</span>

                        <div class="ml-5 mt-1">
                            {#each item.slots as slot}
                                <div class="row w-25">
                                    <div class="col">{slot.start} <span class="text-muted">&#8212;</span> {slot.end}</div>
                                    <div class="col">{student.country.currency_code} {slot.price}</div>
                                </div>
                            {/each}
                        </div>
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>
{/if}
<style>
.item-date {
    display: block;
    font-size: 1.5rem;
}
.total {
    border-top: 1px solid #ccc;
    margin-top: 1rem;
    margin-left: 3rem;
    padding-top: 1rem;
}
.total-amount {
    font-size: 2rem;
    text-decoration: underline;
}
</style>