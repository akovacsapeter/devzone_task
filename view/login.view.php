<h1>Login</h1>
<?php if ($login_post_error != null && $login_post_error->has_error()) {?>
	<div class="alert"><?= $login_post_error->error_message()?></div>
<?php }?>
<form name="login-form" class="login-form" method="post" action="login" autocomplete="off">
	<input type="hidden" name="action" value="login_attempt">
	<div>
		<label for="name">
			E-mail address
		</label>
		<input type="text" name="email" id="email" class="mandatory" placeholder="Type your e-mail address">
	</div>
	<div>
		<label for="password">
			Password
		</label>
		<input type="password" name="password" class="mandatory" id="password" placeholder="Type your password">
	</div>
	<?php if ($login_attempt != null && $login_attempt->needs_captcha()) {?>
		<div>
			<label for="captcha">
				Captcha <img src="view/captcha.php?item=login" class="capctha">
			</label>
			<input type="text" name="captcha" class="mandatory" id="captcha" placeholder="Type the code here">
		</div>
	<?php }?>
	<div>
		<button type="submit" name="login_submit_btn">Login</button>
	</div>
</form>
<div class="info">
	<a href="registration">Registration</a> | <a href="forgot">Forgot your password?</a>
</div>