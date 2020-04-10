<script>
export let user;

import _ from 'lodash';
import jq from 'jquery';
import axios from 'axios';
import { date_ymd, date_hm } from 'util';
import { scale } from 'svelte/transition';

let enrollments;
const fetchEnrollments = axios.get('./enrollments', {
    params: {
        student: user.id,
        with: ['classrooms']
    }
});

fetchEnrollments.then((response) => {
    enrollments = _.map(response.data, (data) => {
        return {
            id: data.id,
            data,
            saving: false,
            modifying: false
        }
    });
});

async function save(id)
{
    const index = _.findIndex(enrollments, ['id', id]);

    if (index <= -1) {
        return;
    }

    enrollments[index].saving = true;

    try {
        const active = jq(`.active-${id}`).val();
        const paid = jq(`.paid-${id}`).val();

        await axios.post(`./enrollment/${id}`, {
            _method: 'PUT',
            data: {
                active,
                paid
            }
        });

        enrollments[index].data.active = active;
        enrollments[index].data.paid = paid;

        hide(id);
    } catch (e) {
        alert('An error occured during save');
    } finally {
        enrollments[index].saving = false;
    }
}

function show(id)
{
    const index = _.findIndex(enrollments, ['id', id]);

    if (index >= 0) {
        enrollments[index].modifying = true;
    }
}

function hide(id)
{
    const index = _.findIndex(enrollments, ['id', id]);

    if (index >= 0) {
        enrollments[index].modifying = false;
    }
}
</script>


{#await fetchEnrollments}
    <div class="text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
{:then}
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Enrollment Period (Start)</th>
                <th scope="col">Enrollment Period (End)</th>
                <th scope="col"># classrooms</th>
                <th scope="col">Paid</th>
                <th scope="col">Active</th>
                <th scope="col">Invoice</th>
                <th scope="col">Created</th>
                <th scope="col">Last Updated</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            {#each enrollments as { data, modifying, saving} }
                <tr>
                    <th scope="row">{data.id}</th>
                    <td>
                        {#if data.period}
                            { data.period[0] }
                        {:else}
                            <span class="text-muted">None</span>
                        {/if}
                    </td>
                    <td>
                        {#if data.period}
                            { data.period[1] }
                        {:else}
                            <span class="text-muted">None</span>
                        {/if}
                    </td>
                    <td>{ data.classrooms.length }</td>
                    <td>
                        {#if modifying}
                            <select class="custom-select custom-select-sm f paid-{data.id}" disabled={saving}>
                                <option value="1" selected={ data.paid == true }>YES</option>
                                <option value="0" selected={ data.paid == false }>NO</option>
                            </select>
                        {:else}
                            {@html
                                data.paid == true ? 
                                    '<strong class="text-success">Yes</strong>':
                                    '<span class="text-muted">No</span>'
                            }
                        {/if}
                    </td>
                    <td>
                        {#if modifying}
                            <select class="custom-select custom-select-sm f active-{data.id}" disabled={saving}>
                                <option value="1" selected={ data.active == true }>YES</option>
                                <option value="0" selected={ data.active == false }>NO</option>
                            </select>
                        {:else}
                            {@html
                                data.active == true ?
                                    '<strong class="text-success">Yes</strong>':
                                    '<span class="text-muted">No</span>'
                            }
                        {/if}
                    </td>
                    <td>
                        <a href="./enrollment/pdf/{ data.id }" target="_blank">PDF</a>
                    </td>
                    <td>{ date_ymd(new Date(data.created_at)) } { date_hm(new Date(data.created_at)) }</td>
                    <td>{ date_ymd(new Date(data.updated_at)) } { date_hm(new Date(data.updated_at)) }</td>
                    <td>
                        {#if modifying}
                            <button class="btn btn-success btn-sm" on:click|preventDefault={() => { save(data.id) }} disabled={saving}>
                                Save
                            </button>
                            <button class="btn btn-secondary btn-sm" on:click|preventDefault={() => { hide(data.id) }}>
                                Cancel
                            </button>
                        {:else}
                            <button class="btn btn-primary btn-sm" on:click|preventDefault={() => { show(data.id) }}>
                                Modify
                            </button>
                        {/if}
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>
{:catch}
    <div class="w-50 mx-auto">
        <div class="alert alert-warning">
            Failed to retrieve enrollment data
        </div>
    </div>
{/await}

<style>
.f {
    font-size: 0.7em;
}
</style>