<script>
export let params = {};

import _ from 'lodash';

import SettingsCountriesComponent from './views/settings/Countries.svelte';
import SettingsRegistryComponent from './views/settings/Registry.svelte';

if (_.isNull(params.page)) {
    params.page = 'registry';
}

const pages = [
    {
        page: 'registry',
        label: 'Registry',
        path: '#/settings/registry',
        component: SettingsRegistryComponent
    },

    {
        page: 'countries',
        label: 'Countries',
        path: '#/settings/countries',
        component: SettingsCountriesComponent
    }
];
let current;

$: {
    if (params.page) {
        switchPage(params.page);
    } else {
        switchPage('registry');
    }
}

function switchPage(page)
{
    current = _.find(pages, ['page', page]);
}
</script>

<h2 class="display-4">Settings</h2>

<ul class="nav nav-tabs">
    {#each pages as page}
        <li class="nav-item">
            <a href={page.path} class="nav-link" class:active={page.page == current.page} on:click={() => switchPage(page.page)}>{page.label}</a>
        </li>
    {/each}
</ul>

<svelte:component this={current.component} />