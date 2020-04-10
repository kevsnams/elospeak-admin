import {readable} from 'svelte/store';
import axios from 'axios';

function transform(data)
{
    let newObj = {};

    data.forEach((setting) => {
        const keys = setting.key.split('.');

        if (typeof newObj[keys[0]] == 'undefined') {
            newObj[keys[0]] = {};
        }

        newObj[keys[0]][keys[1]] = setting.value;
    });

    return newObj;
}

export const Settings = readable(null, function start(set) {
    const xhr = axios.get('./settings/register');

    xhr.then((resp) => {
        set(transform(resp.data));
    });

    return function stop() {

    };
});