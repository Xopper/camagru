<form id="form" class="form" method="POST">
	<h2>change your password</h2>

	<div class="form-control">
		<label for="Npassword">New password</label>
		<input type="password" id="Npassword" name="Npassword" placeholder="enter a new password" autocomplete="off" minlength="8" required>
		<small id="_Npassword">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="conf-Npass">Confirm new password</label>
		<input type="password" id="conf-Npass" name="conf-Npass" placeholder="confirm the new password" autocomplete="off" minlength="8" required>
		<small id="_conf-Npass">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="Opassword">Confirm changes</label>
		<input type="password" id="Opassword" name="Opassword" placeholder="enter the old password" autocomplete="off" minlength="8" required>
		<small id="_Opassword">Error msg here</small>
	</div>

	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>

	<button id="btn-submit" class="btn-submit" type="submit">submit</button>
	<p class="login">
		wanna back to your account ? <a href="/account/">Account</a>
	</p>
</form>