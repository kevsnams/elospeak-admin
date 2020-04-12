<script>
export let teacher;

import _ from 'lodash';
import axios from 'axios';
import { date_ymd, date_hm } from 'util';

let classrooms = [];
const fetchClassrooms = axios.get('./classrooms', {
    params: {
        teacher: teacher.id,
        with: [ 'student', 'enrollment' ]
    }
});

fetchClassrooms.then((response) => {
    classrooms = _.map(response.data, (data) => {
        return {
            data
        };
    });
});
$: {
    console.log(classrooms);
}
</script>

{#await fetchClassrooms}
    <div class="text-center mt-5">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
{:then response}
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Start</th>
                <th scope="col">End</th>
                <th scope="col">Student</th>
                <th scope="col">Status</th>
                <th scope="col">...</th>
            </tr>
        </thead>
        <tbody>

            {#each classrooms as { data }}
                <tr>
                    <th scope="row">{ data.id }</th>
                    <td>{ date_ymd(new Date(data.start)) }</td>
                    <td>{ date_hm(new Date(data.start)) }</td>
                    <td>{ date_hm(new Date(data.end)) }</td>
                    <td>
                        {#if data.student}
                            <a href="#/student/{ data.student.id }">{ data.student.full_name }</a>
                        {:else}
                            <em>No Student/Deleted Account</em>
                        {/if}
                    </td>
                    <td>{ data.status_text }</td>
                    <td>&nbsp;</td>
                </tr>
            {/each}
        </tbody>
    </table>
{:catch}
    <div class="alert alert-warning w-50 mx-auto">
        Failed fetching teacher's classroom
    </div>
{/await}