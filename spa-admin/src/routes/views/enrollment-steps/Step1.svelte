<script>
export let student;
export let data;

import _ from 'lodash';
import Pikaday from 'pikaday';
import {onMount} from 'svelte';
import { createEventDispatcher } from 'svelte';

const dispatch = createEventDispatcher();

let start = _.has(data, 'period.start') ? data.period.start : '',
    end = _.has(data, 'period.end') ? data.period.end : '';

let elem = {
    start: null,
    end: null
};

let picker = {
    start: null,
    end: null
};

onMount(() => {
    picker.start = new Pikaday({
        field: elem.start,
        minDate: new Date(),

        onSelect: (date) => {
            picker.end.setMinDate(date);
        }
    });

    picker.end = new Pikaday({
        field: elem.end,
        minDate: new Date()
    });
});



function next()
{
    if (elem.start.value && elem.end.value) {
        dispatch('next', {
            period: {
                start: picker.start.toString('YYYY-MM-DD'),
                end: picker.end.toString('YYYY-MM-DD')
            }
        });
    }
}
</script>
<h1 class="display-4" style="font-size: 2rem;">Enrollment Period</h1>
<form class="w-25 mt-5" on:submit|preventDefault={next}>
    <div class="form-group row">
        <label for="enrollment-start" class="col-sm-4 col-form-label">Start Date</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="enrollment-start" readonly bind:this={elem.start} value={start}>
        </div>
    </div>

    <div class="form-group row">
        <label for="enrollment-end" class="col-sm-4 col-form-label">End Date</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="enrollment-end" readonly bind:this={elem.end} value={end}>
        </div>
    </div>
    <div class="form-group mt-5">
        <button type="submit" class="btn btn-primary" on:click|preventDefault={next}>Next &raquo;</button>
    </div>
</form>