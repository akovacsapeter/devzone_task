<h1>Profile</h1>
<?php if ($_GET["action"] == "modify_thanks") {?>
	<h3>Congratulations, you've succesfully modified your data.</h3>
<?php } elseif ($profile_post_error != null && $profile_post_error->has_error()) {?>
		<div class="alert"><?= $profile_post_error->error_message()?></div>
<?php }?>
<div class="info"><?= $auth_user->email?> | <a href="greetings">greetings</a> | <a href="logout">logout</a></div>
<form name="profile-form" class="registration-form" method="post" action="profile" autocomplete="off">
	<input type="hidden" name="action" value="profile_attempt">
	<div>
		<label for="name">
			Name
		</label>
		<input type="text" name="name" id="name" class="mandatory" placeholder="Type your name" value="<?= $registration->name?>">
	</div>
	<div>
		<label for="name">
			E-mail address
		</label>
		<input type="text" name="email" id="email" class="mandatory" placeholder="Type your e-mail address" value="<?= $registration->email?>">
	</div>
	<div>
		<label for="password">
			Change your password<br/><small>at least 8 characters, should contains numbers and characters too.</small>
		</label>
		<input type="password" name="password" id="password" placeholder="Type your new password">
	</div>
	<div>
		<label for="password_again">
			New password again
		</label>
		<input type="password" name="password_again" id="password_again" placeholder="Type your new password again">
	</div>
	<div>
		<button type="submit" name="registration_submit_btn">Change your profile data</button>
	</div>
</form>