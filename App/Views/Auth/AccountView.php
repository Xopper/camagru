<form id="form" class="form" method="POST">
	<h2>change your infos</h2>

	<div class="form-control">
		<label for="fName">First name</label>
		<input type="text" id="fName" name="fName" value="<?= $auth->firstName ?>" required minlength="3" maxlength="10" autocomplete="off">
		<small id="_fName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="lName">Last name</label>
		<input type="text" id="lName" name="lName" value="<?= $auth->lastName ?>" required minlength="3" maxlength="10" autocomplete="off">
		<small id="_lName">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="username">Username</label>
		<input type="text" id="username" name="username" value="<?= $auth->username ?>" required minlength="3" maxlength="12" autocomplete="off">
		<small id="_username">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="email">Email</label>
		<input type="email" id="email" name="email" value="<?= $auth->email ?>" required maxlength="63" autocomplete="off">
		<small id="_email">Error msg here</small>
	</div>

	<div class="form-control">
		<label for="password">Confirm changes</label>
		<input type="password" id="password" name="password" placeholder="Enter your password to save changes" required minlength="8" autocomplete="off">
		<small id="_password">Error msg here</small>
	</div>

	<div class="form-control notification">
		<label class="custom-checkbox">
			<input type="checkbox" id="notification_on" name="notification_on" value="on" <?= $auth->notification_on ? "checked" : "" ?> />
			<span class="label-text">Enable notifications</span>
		</label>
	</div>

	<input type="hidden" id="_token" name="token" value="<?= $token ?>" />

	<button id="btn-submit" class="btn-submit" type="submit">submit</button>
	<p class="login">
		or wanna change your password ? <a href="<?= url("/account/security"); ?>">Security</a>
	</p>
</form>