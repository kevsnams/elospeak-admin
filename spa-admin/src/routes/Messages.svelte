<script>
import _ from 'lodash';
import axios from 'axios';
import { slide } from 'svelte/transition';

const fetchMessages = axios.get('./customer-messages');

let messages = [];
fetchMessages.then((response) => {
    messages = _.map(response.data, (data) => {
        return {
            id: data.id,
            data,
            viewing: false
        }
    });
});

function show(id)
{
    const index = _.findIndex(messages, (m) => {
        return m.id === id;
    });

    if (index < 0) {
        return;
    }

    for (let i in messages) {
        if (i == index) {
            continue;
        }

        messages[i].viewing = false;
    }

    messages[index].viewing = !messages[index].viewing;

    if (!messages[index].data.is_read) {
        try {
            const xhrUpdateMessage = axios.post(`./customer-messages/${id}`, {
                _method: 'PUT',
                data: {
                    is_read: true
                }
            });

            xhrUpdateMessage.then(() => {
                messages[index].data.is_read = true;
            });
        } catch (e) {}
    }
}
</script>

<h2 class="display-4">Messages</h2>

<div class="alert alert-info">
    These are messages from "Contact US" page
</div>

<div class="messages">
    {#await fetchMessages}
        <div class="text-center mt-5">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    {:then response}
        <div class="d-flex message-header">
            <div class="from w-25">
                Full Name
            </div>

            <div class="email w-25">
                Email
            </div>

            <div class="date w-25 ml-auto">
                Date
            </div>
        </div>
        {#each messages as { data, viewing}}
            <div class="message" class:viewing={viewing}>
                <div class="preview d-flex" class:read={data.is_read} class:unread={!data.is_read} on:click={() => { show(data.id) }}>
                    <div class="from w-25">
                        {data.full_name}
                    </div>
                    <div class="email w-25">
                        {data.email}
                    </div>
                    <div class="date w-25 ml-auto">
                        {data.sent_date}
                    </div>
                </div>

                {#if viewing}
                    <div class="content" transition:slide>
                        <div class="info">
                            <span class="h">Full Name</span>
                            <div class="c">
                                {data.full_name}
                            </div>
                        </div>

                        <div class="info">
                            <span class="h">Email</span>
                            <div class="c">
                                {data.email}
                            </div>
                        </div>

                        <div class="info">
                            <span class="h">Message</span>
                            <div class="c">
                                {data.message}
                            </div>
                        </div>
                    </div>
                {/if}
            </div>
        {/each}
    {:catch}
        <div class="alert alert-warning mt-5">
            Failed fetching messages
        </div>
    {/await}
</div>

<style>
.messages {
    margin-top: 2rem;
}

.messages .content .info:not(:last-child) {
    margin-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}
.messages .content .info .h {
    display: block;
    font-weight: bold;
    color: #575757;
}
.messages .content .info .c {
    margin-top: .5rem;
    padding-bottom: 1rem;
}

.messages .message-header,
.messages .message .unread {
    font-weight: bold;
}

.messages .message .read {
    color: #666;
}

.messages .message,
.messages .message-header {
    padding: .5rem;
    font-size: .9rem;
}

.messages .message-header {
    border-bottom: 1px solid #f0f0f0;
}

.messages .message:hover,
.messages .message.viewing {
    margin-bottom: .5rem;
    box-shadow: 0 0 0 1px rgba(43, 45, 80, 0.1),
        0 2px 5px 0 rgba(43, 45, 80, 0.08),
        0 1px 1.5px 0 rgba(0, 0, 0, 0.07),
        0 1px 2px 0 rgba(0, 0, 0, 0.08);
    border-radius: .355rem;
}
.messages .message .preview {
    cursor: pointer;
}
.messages .message:not(:last-child) {
    margin-bottom: .5rem;
}

.messages .message .content {
    border-top: 1px solid #f0f0f0;
    margin-top: .5rem;
    padding-top: .5rem;
}
</style>