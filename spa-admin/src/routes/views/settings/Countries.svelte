<script>
import _ from 'lodash';
import jq from 'jquery';
import axios from 'axios';
import {onMount} from 'svelte';
import {Countries} from 'countries';

let countryStates = [], filteredCountries = [];
$: {
    if (!_.isNull($Countries)) {
        countryStates = $Countries;
        filteredCountries = countryStates;
    }
}

let keywords;
const searchCountry = _.debounce(async () => {
    if (keywords.length) {
        filteredCountries = countryStates.filter((country) => {
            const regexp = new RegExp(`(${keywords})`, 'i');

            return regexp.test(country.name) 
                || regexp.test(country.code);
        });
    } else {
        filteredCountries = countryStates;
    }

    jq('[data-edit]').prop('hidden', true);

    reset();
}, 700);

onMount(() => {
    jq('.countries').on('click', '[data-country]', (evt) => {
        const code = evt.currentTarget.getAttribute('data-country'),
            edit = jq(`[data-edit=${code}]`),
            vis = edit.prop('hidden');

        jq('[data-edit]').prop('hidden', true);

        edit.prop('hidden', !vis);

        reset();
    });
});

let isSaving = false, errors = [], success = false;
async function save(evt, id)
{
    isSaving = true;

    const data = new FormData(evt.target);

    try {
        await axios.post(`./countries/${id}`, data);
        reset();

        success = true;
    } catch (e) {
        reset();

        _.forEach(e.response.data.errors, (error) => {
            errors = [...errors, error[0]];
        });
    }
}

function reset()
{
    isSaving = false;
    success = false;
    errors = [];
}
</script>

<input type="text" bind:value={keywords} class="form-control form-control-lg mt-4" on:keyup={searchCountry} placeholder="Search Country" />

<table class="table table-hover mt-5 countries">
    <thead>
        <tr>
            <th>Country Name</th>
            <th>Code</th>
            <th>Price/class (Weekday)</th>
            <th>Price/class (Weekend)</th>
            <th>Currency</th>
            <th>Duration/class</th>
        </tr>
    </thead>

    <tbody>
        {#each filteredCountries as country}
            <tr data-country={country.code}>
                <td>{country.name}</td>
                <td>{country.code}</td>
                <td>{@html !country.is_defined ? '<span class="text-muted">Undefined</span>' : country.price}</td>
                <td>{@html !country.is_defined ? '<span class="text-muted">Undefined</span>' : country.price_weekend}</td>
                <td>{@html !country.is_defined ? '<span class="text-muted">Undefined</span>' : country.currency_code}</td>
                <td>{@html !country.is_defined ? '<span class="text-muted">Undefined</span>' : `${country.duration} <span class="font-italic">minutes</span>`}</td>
            </tr>
            <tr data-edit={country.code} hidden>
                <td colspan="5">
                    <h4 class="mb-3">{country.name}</h4>
                    <form class="w-75" novalidate on:submit|preventDefault={(evt) => { save(evt, country.id) }}>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group row">
                            <label for="price_weekday" class="col-4 col-form-label">Price/class weekdays</label>
                            <div class="col-8">
                                <input type="text" class="form-control w-50" id="price_weekday" name="data[price]" value={country.price} disabled={isSaving}>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="price_weekend" class="col-4 col-form-label">Price/class weekends</label>
                            <div class="col-8">
                                <input type="text" class="form-control w-50" id="price_weekend" name="data[price_weekend]" value={country.price_weekend} disabled={isSaving}>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="currency_code" class="col-4 col-form-label">Currency Code</label>
                            <div class="col-8">
                                <input type="text" class="form-control w-50" id="currency_code" name="data[currency_code]" placeholder="e.g. PHP, USD, CAD, NZD" value={country.currency_code} disabled={isSaving}>
                                <small class="form-text text-muted">3 character code that represents the country's currency</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="duration" class="col-4 col-form-label">Duration/class (number in minutes)</label>
                            <div class="col-8">
                                <input type="text" class="form-control w-50" id="duration" name="data[duration]" value={country.duration} disabled={isSaving}>
                            </div>
                        </div>
                        {#if success}
                            <div class="alert alert-success">
                                Successfully updated <em>{country.name}</em>
                            </div>
                        {/if}
                        
                        {#if errors.length}
                            <div class="alert alert-danger">
                                The following errors were found:

                                <ul>
                                    {#each errors as error}
                                        <li>{error}</li>
                                    {/each}
                                </ul>
                            </div>
                        {/if}

                        {#if isSaving}
                            <button class="btn btn-primary" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                        {:else}
                            <button type="submit" class="btn btn-primary">Save</button>
                        {/if}
                    </form>
                </td>
            </tr>
        {/each}
    </tbody>
</table>

<style>
.countries tbody tr[data-country] {
    cursor: pointer;
}
.countries tbody tr[data-edit]:hover {
    background: none !important;
}

.edit-form {
    width: 15%;
}
</style>