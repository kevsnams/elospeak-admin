import 'bootstrap';
import App from './App.svelte';

const app = new App({
	target: document.getElementById('main'),
	props: {
		csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
	}
});

export default app;