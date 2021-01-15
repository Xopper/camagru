<form id="form" class="form" method="POST">
		<h2>Reset your password</h2>
	<div class="form-control">
		<label for="Npassword">New password</label>
		<input type="password" id="Npassword" name="Npassword" placeholder="enter a new password" autocomplete="off" required minlength="8">
		<small id="_Npassword">Error msg here</small>
	</div>
	<div class="form-control">
		<label for="conf-Npass">Confirm new password</label>
		<input type="password" id="conf-Npass" name="conf-Npass" placeholder="enter a new password" autocomplete="off" required minlength="8">
		<small id="_conf-Npass">Error msg here</small>
	</div>
	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>
	<button id="btn-submit" class="btn-submit" type="submit">Send</button>
</form>