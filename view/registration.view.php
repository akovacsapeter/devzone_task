<h1>Registration</h1>
<?php if ($_GET["action"] == "activate" && $activating_error != null && $activating_error->has_error()) {?>
	<div class="alert"><?= $activating_error->error_message()?></div>
<?php } elseif ($_GET["action"] == "registration_thanks") {?>
	<h3>You've succesfully registrated.</h3>
	<div>We've sent you an activation url via e-mail. Please click on or copy that url into your browser's address bar and activate within 48 hours.</div>
<?php } elseif ($_GET["action"] == "activation_thanks") {?>
	<h3>Congratulations, you've succesfully activated.</h3>
	<div>Please <a href="login">login.</a></div>
<?php } else {
	if ($registration_post_error != null && $registration_post_error->has_error()) {?>
		<div class="alert"><?= $registration_post_error->error_message()?></div>
	<?php }?>
	<form name="registration-form" class="registration-form" method="post" action="registration" autocomplete="off">
		<input type="hidden" name="action" value="registration_attempt">
		<div>
			<label for="name">
				Name
			</label>
			<input type="text" name="name" id="name" class="mandatory" placeholder="Type your name">
		</div>
		<div>
			<label for="name">
				E-mail address
			</label>
			<input type="text" name="email" id="email" class="mandatory" placeholder="Type your e-mail address">
		</div>
		<div>
			<label for="password">
				Password<br/><small>at least 8 characters, should contains numbers and characters too.</small>
			</label>
			<input type="password" name="password" class="mandatory" id="password" placeholder="Type your password">
		</div>
		<div>
			<label for="password_again">
				Password again
			</label>
			<input type="password" name="password_again" class="mandatory" id="password_again" placeholder="Type your password again">
		</div>
		<div>
			<button type="submit" name="registration_submit_btn">Registration</button>
		</div>
	</form>
	<div class="info">
		<a href="login">Login</a> | <a href="forgot">Forgot your password?</a>
	</div>
<?php }?>