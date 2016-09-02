<?php
require("class/class.registration.php");
require("class/class.forgot_post_error.php");
require("class/class.captcha.php");
require("lib/phpmailer/class.phpmailer.php");
if ($_POST["action"] == "forgot_attempt") {

	$forgot_post_error = new forgot_post_error($_POST["email"], $_SESSION["forgot_captcha"], $_POST["captcha"], $database);//check fields

	if ($forgot_post_error->check()) {
		unset($_SESSION["forgot_captcha"]);
		$registration = new registration($database->get_field_value("dt_registration", "id", "email = '".$_POST["email"]."'"), $database);
		//try to sending password reset mail
		$mailer = new PHPMailer;
		$mailer->IsSMTP();
		$mailer->From = $registration->forgot_from;
		$mailer->Sender   = $registration->forgot_sender;
		$mailer->FromName = $registration->forgot_from_name;
		$mailer->AddAddress($registration->email);
		$mailer->Subject = $registration->forgot_subject;
		$mailer->Body = $registration->reset_password();
		$mailer->isHTML(true);
		$mailer->Send();
		if ($mailer->IsError()) {
			$registration->restore_password();
			$forgot_post_error->add("Password reset", "Unable to send new password. Please try again later.");
		} else {
			header("Location: forgot&action=sent_thanks");
			exit;
		}
	}

}
$captcha = new captcha();
$_SESSION["forgot_captcha"] = $captcha->get_code();
require("view/head.view.php");
require("view/forgot.view.php");
require("view/bottom.view.php");