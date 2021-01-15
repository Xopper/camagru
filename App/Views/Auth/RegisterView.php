<form id="form" class="form" method="POST">
	<h2>Register with us</h2>

	<div class="form-control">
		<label for="fName">First name</label>
		<input
			autocomplete="off"
			type="text"
			id="fName"
			name="fName"
			placeholder="enter your first name"
			required
			minlength="3"
			maxlength="10"
		/>
		<small id="_fName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="lName">Last name</label>
		<input
			autocomplete="off"
			type="text"
			id="lName"
			name="lName"
			placeholder="enter your last name"
			required
			minlength="3"
			maxlength="10"
		/>
		<small id="_lName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="username">Username</label>
		<input
			autocomplete="off"
			type="text"
			id="username"
			name="username"
			placeholder="enter your username"
			required
			minlength="3"
			maxlength="12"
		/>
		<small id="_username">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="email">Email</label>
		<input
			autocomplete="off"
			type="email"
			id="email"
			name="email"
			placeholder="enter a valid email"
			required
			maxlength="63"
		/>
		<small id="_email">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="password">Password</label>
		<input
			autocomplete="off"
			type="password"
			id="password"
			name="password"
			placeholder="enter a valid password"
			required
			minlength="8"
		/>
		<small id="_password">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="conf-pass">Password confirmation </label>
		<input
			autocomplete="off"
			type="password"
			id="conf-pass"
			name="conf-pass"
			placeholder="confirm your password"
			required
			minlength="8"
		/>
		<small id="_conf-pass">Error msg here</small>
	</div>

	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>

	<button id="btn-submit" class="btn-submit" type="submit">Register</button>
	<p class="login">
		If you already have an account
		<a href="/login">Login</a>
	</p>
</form>
