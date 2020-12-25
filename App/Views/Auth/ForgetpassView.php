<form id="form" class="form" method="POST">
	<h2>Reset your password</h2>
	<div class="form-control">
		<label for="email">Email :</label>
		<input
			type="email"
			id="email"
			name="email"
			placeholder="enter your email"
			autocomplete="off"
			required
		/>
		<small id="_email">Error msg here</small>
	</div>
	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>
		<button id="btn-submit" class="btn-submit" type="submit">Send</button>
</form>