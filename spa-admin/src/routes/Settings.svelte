<script>
import jq from 'jquery';
import axios from 'axios';
import {onMount} from 'svelte';

import {
    Trash2Icon,
    EditIcon,
    PlusSquareIcon
} from 'svelte-feather-icons';

const xhr = axios.get('./settings/register');

let tmp = {};
function showDeleteModal(setting)
{
    tmp = setting;
    jq('#delete-confirm').modal('show');
}

async function deleteSetting(id)
{
    try {
        const deleteXHR = await axios.post('./settings/delete', {
            id
        });

        setTimeout(() => {
            top.location.reload();
        }, 1000);
    } catch (e) {
        alert('Error: Unable to delete setting');
    }
}

let isModifyXHR = false, modifyForm, modifyXHR, showModifySuccess = false, showModifyFail = false;

function showModifyModal(setting)
{
    tmp = setting;
    jq('#modify-modal').modal('show');
}

async function saveSetting()
{
    isModifyXHR = true;

    const data = new FormData(modifyForm);

    try {
        modifyXHR = await axios.post('./settings/modify', data);
        showModifyFail = false;
        showModifySuccess = true;

        setTimeout(() => {
            top.location.reload();
        }, 1000);
    } catch (e) {
        showModifyFail = true;
    }

    isModifyXHR = false;
}

let addForm, addXHR, isAddXHR = false, showAddSuccess = false, showAddFail = false;

async function addSetting()
{
    isAddXHR = true;

    const data = new FormData(addForm);

    try {
        addXHR = await axios.post('./settings/add', data);
        showAddFail = false;
        showAddSuccess = true;

        setTimeout(() => {
            top.location.reload();
        }, 1000);
    } catch (e) {
        showAddFail = true;
    }

    isAddXHR = false;
}
</script>

<div class="d-flex justify-content-between align-items-center">
    <div>
        <h2 class="display-4">Settings</h2>
    </div>

    <div>
        <button type="button" class="btn btn-secondary btn-lg" on:click={() => {jq('#add-modal').modal('show')}}>
            <PlusSquareIcon /> Add Setting
        </button>
    </div>
</div>

{#await xhr}
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>
{:then settings}
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Key</th>
                <th>Value</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {#each settings.data as setting}
                <tr>
                    <td>{setting.key}</td>
                    <td>{setting.value}</td>
                    <td>
                        <div class="btn-group">
                            <a href="javascript:;" on:click={() => {showModifyModal(setting)}} class="btn btn-secondary btn-sm">
                                <EditIcon /> Modify
                            </a>
                            <a href="javascript:;" on:click={() => {showDeleteModal(setting)}} class="btn btn-secondary btn-sm">
                                <Trash2Icon /> Delete
                            </a>
                        </div>
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>
{:catch}
    <div class="alert alert-danger">
        Failed fetching settings register
    </div>
{/await}

<div class="modal fade" id="add-modal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Setting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {#if showAddSuccess}
                    <div class="alert alert-success">
                        Successfully added setting
                    </div>
                {/if}

                {#if showAddFail}
                    <div class="alert alert-danger">
                        Error: Unable to add new setting
                    </div>
                {/if}

                <form class="form-inline" bind:this={addForm} method="POST">
                    <div class="form-group mb-2 mr-2">
                        <label for="add-key" class="sr-only">Key</label>
                        <input type="text" class="form-control" placeholder="Key" id="add-key" name="key">
                    </div>

                    <div class="form-group mb-2">
                        <label for="add-value" class="sr-only">Value</label>
                        <input type="text" class="form-control" placeholder="Value" id="add-value" name="value">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                {#if isAddXHR}
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm"></span> Loading...
                    </button>
                {:else}
                    <button type="button" class="btn btn-primary" on:click={addSetting}>
                        Add Setting
                    </button>
                {/if}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-confirm" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hey! Think before you delete.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <mark>{tmp.key}</mark> setting? This will affect the website if this setting is currently being used.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" on:click={() => {deleteSetting(tmp.id)}}>
                    Understood
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modify-modal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modify "<em>{tmp.key}</em>"</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="w-100">
                        <form method="POST" bind:this={modifyForm}>
                            {#if showModifySuccess}
                                <div class="alert alert-success">
                                    Successfully modified setting!
                                </div>
                            {/if}

                            {#if showModifyFail}
                                <div class="alert alert-danger">
                                    Error: Unable to save setting at this time
                                </div>
                            {/if}

                            <div class="form-group">
                                <label for="new_value">"{tmp.key}" New Value</label>
                                <input type="text" name="new_value" value="{tmp.value}" class="form-control" id="new_value" />
                            </div>
                            <input type="hidden" name="id" value="{tmp.id}" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {#if isModifyXHR}
                    <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm"></span> Loading...
                    </button>
                {:else}
                    <button type="button" class="btn btn-primary" on:click={saveSetting}>
                        Save Setting
                    </button>
                {/if}
            </div>
        </div>
    </div>
</div>