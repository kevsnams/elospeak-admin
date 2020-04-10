<script>
export let isLoading = false;
export let isSuccess = false;
export let isFail = false;
export let buttonText = '';
export let successText = '';
export let failText = '';

import _ from 'lodash';
</script>

<div class="row">
    <div class="col-auto">
        {#if isLoading}
            <button class="btn btn-primary" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
        {:else}
            <button type="submit" class="btn btn-primary">{buttonText}</button>
        {/if}
    </div>
    <div class="col-auto">
        {#if isSuccess}
            <div class="alert alert-success px-2 py-2">
                {@html successText}
            </div>
        {/if}

        {#if isFail}
            <div class="alert alert-danger px-2 py-2">
                {#if _.isString(failText)}
                    {@html failText}
                {:else}
                    An error occurred:
                    <ul>
                        {#each failText as text}
                            <li>{text}</li>
                        {/each}
                    </ul>
                {/if}
            </div>
        {/if}
    </div>
</div>