<form id="form" class="form" method="POST">
	<h2>change your infos</h2>

	<div class="form-control">
		<label for="fName">First name</label>
		<input type="text" id="fName" name="fName" value="<?= $auth->firstName ?>" required autocomplete="off">
		<small id="_fName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="lName">Last name</label>
		<input type="text" id="lName" name="lName" value="<?= $auth->lastName ?>" required autocomplete="off">
		<small id="_lName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="username">Username</label>
		<input type="text" id="username" name="username" value="<?= $auth->username ?>" required autocomplete="off">
		<small id="_username">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="email">Email</label>
		<input type="text" id="email" name="email" value="<?= $auth->email ?>" required autocomplete="off">
		<small id="_email">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="password">Confirm Changes</label>
		<input type="password" id="password" name="password" placeholder="Enter your password to save changes" required autocomplete="off">
		<small id="_password">Error msg here</small>
	</div>

	<div class="form-control notification">
		<input type="checkbox" id="notification_on" name="notification_on" value="on" <?= $auth->notification_on ? "checked" : "" ?>>
		<label for="notification_on">Enabele notification</label>
	</div>

	<input
		type="hidden"
		id="_token"
		name="token"
		value="<?= $token ?>"
	/>

	<button id="btn-submit" class="btn-submit" type="submit">submit</button>
	<p class="login">
		or wanna change your password ? <a href="/account/security">Security</a>
	</p>
</form>