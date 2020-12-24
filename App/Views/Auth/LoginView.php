<form id="form" class="form" method="POST">
	<h2>Login to your account</h2>

	<div class="form-control">
		<label for="username">Username :</label>
		<input
			autocomplete="off"
			type="text"
			id="username"
			name="username"
			placeholder="enter your username"
			required
		/>
		<small id="_username">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="password">Password :</label>
		<input
			autocomplete="off"
			type="password"
			id="password"
			name="password"
			placeholder="enter your password"
			required
		/>
		<small id="_password">Error msg here</small>
	</div>
	<div class="form-control remember">
		<input
			type="checkbox"
			id="remember"
			name="remember"
			value="on"
		/>
		<label for="remember">Remember me</label><br />
	</div>
	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>

	<button id="btn-submit" class="btn-submit" type="submit">Login</button>
	<p class="login">
		or new to the website ? <a href="/register">Register</a>
	</p>
	<p class="forget-pass">
		:/ i forgot my password! <a href="/forgetpass">Forget password</a>
	</p>
</form>
