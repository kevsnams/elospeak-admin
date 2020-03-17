<script>
export let csrf_token;

import Router from 'svelte-spa-router';
import active from 'svelte-spa-router/active'
import routes from './routes';

let logoutForm;
function logout()
{
	logoutForm.submit();
}
</script>

<div class="container-fluid">
	<div class="row mt-3">
		<div class="col-2">
			<h1 class="text-center">ELOSpeak</h1>
			<div class="nav flex-column nav-pills mt-3 sidenav-bar">
				<a href="#/" use:active class="nav-link">Overview</a>
				<a href="#/teachers" use:active class="nav-link">Teachers</a>
				<a href="#/students" use:active class="nav-link">Students</a>
				<a href="#/settings" use:active class="nav-link">Settings</a>
				<a href="javascript:;" on:click="{logout}" class="nav-link">Logout</a>
			</div>
		</div>

		<div class="col-10">
			<Router {routes} />
		</div>
	</div>
</div>

<form method="POST" action="./logout" bind:this={logoutForm}>
	<input type="hidden" name="_token" value={csrf_token} />
</form>

<style>
	:global(.required) {
		color: #f03;
		font-weight: bold;
	}
	:global(#main) {
		font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	}

	.sidenav-bar {
		font-size: 1.5rem;
	}
</style>