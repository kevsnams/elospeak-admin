<script>
export let csrf_token;

function logout()
{

}
</script>

<div class="container">
	<div class="row mt-3">
		<div class="col-3">
			<h1 class="text-center">ELOSpeak</h1>
			<div class="nav flex-column nav-pills mt-3">
				<a href="#/home" class="nav-link active">Overview</a>
				<a href="#/teachers" class="nav-link">Teachers</a>
				<a href="#/students" class="nav-link">Students</a>
				<a href="javascript:;" on:click="{logout}" class="nav-link">Logout</a>
			</div>
		</div>

		<div class="col-9">
			2
		</div>
	</div>
</div>

<form method="POST" action="./logout">
	<input type="hidden" name="_token" value={csrf_token} />
</form>

<style>
	:global(#main) {
		font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	}
</style>