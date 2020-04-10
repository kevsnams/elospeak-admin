<script>
export let path = [];

import _ from 'lodash';
</script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        {#each path as crumb, i}
            <li class="breadcrumb-item" class:active={i < path.length - 1}>
                {#if _.isObject(crumb)}
                    <a href={crumb.link}>{crumb.label}</a>
                {:else}
                    {crumb}
                {/if}
            </li>
        {/each}
    </ol>
</nav>