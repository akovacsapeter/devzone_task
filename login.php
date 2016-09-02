<?php
require("class/class.login_post_error.php");
require("class/class.geoip.php");
require("class/class.login_attempt.php");
require("class/class.captcha.php");
if ($_POST["action"] == "login_attempt") {

	$login_post_error = new login_post_error($_POST["email"], $_POST["password"], $_SESSION["login_captcha"], $_POST["captcha"]);//check fields

	$geoip = new geoip();
	$login_attempt = new login_attempt($geoip->get(), $_POST["email"], $_POST["password"], $database);//try to login
	if ($login_attempt->check()) {
		unset($_SESSION["login_captcha"]);
		$_SESSION["suc_reg"] = $login_attempt->success_user();
		header("Location: greetings");
		exit;
	} else {
		$login_post_error->add("Credentials", "Invalid data");
	}
	if ($login_attempt->needs_captcha()) {
		$captcha = new captcha();
		$_SESSION["login_captcha"] = $captcha->get_code();
	}

}
require("view/head.view.php");
require("view/login.view.php");
require("view/bottom.view.php");