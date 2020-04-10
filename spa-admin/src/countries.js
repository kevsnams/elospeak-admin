import {readable} from 'svelte/store';
import axios from 'axios';

export const Countries = readable(null, function start(set) {
    const xhr = axios.get('./countries');

    xhr.then((resp) => {
        set(resp.data);
    });

    return function stop() {

    };
});