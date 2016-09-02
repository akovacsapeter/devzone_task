<h1>Password reminder</h1>
<?php if ($_GET["action"] == "sent_thanks") {?>
	<p>Your new password has been sent to your e-mail adress.</p>
<?php } else {
	if ($forgot_post_error != null && $forgot_post_error->has_error()) {?>
		<div class="alert"><?= $forgot_post_error->error_message()?></div>
	<?php }?>
	<p>Forgotten your password? If you can't remember your password, please enter your email address below and we'll send you a new password.</p>
	<form name="forgot-form" class="login-form" method="post" action="forgot" autocomplete="off">
		<input type="hidden" name="action" value="forgot_attempt">
		<div>
			<label for="name">
				E-mail address
			</label>
			<input type="text" name="email" id="email" class="mandatory" placeholder="Type your e-mail address">
		</div>
		<div>
			<label for="captcha">
				Captcha <img src="view/captcha.php?item=forgot" class="capctha">
			</label>
			<input type="text" name="captcha" class="mandatory" id="captcha" placeholder="Type the code here">
		</div>
		<div>
			<button type="submit" name="login_submit_btn">Send e-mail</button>
		</div>
	</form>
<?php }?>
<div class="info">
	<a href="registration">Registration</a> | <a href="login">Login</a>
</div>