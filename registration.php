<?php
require("class/class.registration_post_error.php");
require("class/class.registration.php");
require("lib/phpmailer/class.phpmailer.php");
if ($_POST["action"] == "registration_attempt") {
	$registration_post_error = new registration_post_error($_POST["name"], $_POST["email"], $_POST["password"], $database);//check fields
	$registration_post_error->check();
	if (!$registration_post_error->has_error()) {
		//save first
		$registration = new registration(0, $database);
		$registration->set("name", $_POST["name"]);
		$registration->set("email", $_POST["email"]);
		$registration->set("password", md5($_POST["password"]));
		$registration->set("activate_code", md5(uniqid(rand(), true)));
		$registration->set("reg_date", date("Y.m.d H:i:s"));
		$registration = new registration($registration->save(), $database);
		//try to sending activation mail
		$mailer = new PHPMailer;
		$mailer->IsSMTP();
		$mailer->From = $registration->activate_from;
		$mailer->Sender   = $registration->activate_sender;
		$mailer->FromName = $registration->activate_from_name;
		$mailer->AddAddress($registration->email);
		$mailer->Subject = $registration->activate_subject;
		$mailer->Body = $registration->get_activate_body(URL);
		$mailer->isHTML(true);
		$mailer->Send();
		if ($mailer->IsError()) {
			$registration->drop();
			$registration_post_error->add("Activation", "Unable to send activation. Please re-register later.");
		} else {
			header("Location: registration&action=registration_thanks");
			exit;
		}
	}
} elseif ($_GET["action"] == "activate") {
	$database->execute("DELETE FROM dt_registration WHERE active = 0 AND reg_date < DATE_ADD(NOW(), INTERVAL -48 HOUR)");//drop old inactive registrations
	$activating_error = new request_error();

	if ($database->size("dt_registration", "activate_code = '".$_GET["code"]."'") > 0) {
		$registration = $database->record("dt_registration", "*", "activate_code = '".$_GET["code"]."'");
		if ($registration->active) {
			$activating_error->add("Activation", "Already active. Please login");
		} else {
			$registration->set("active", 1);
			$registration->save();
		}
	} else {
		$activating_error->add("Activation", "Unknown registration. Please register again.");
	}
	if (!$activating_error->has_error()) {
		header("Location: registration&action=activation_thanks");
		exit;
	}
}
require("view/head.view.php");
require("view/registration.view.php");
require("view/bottom.view.php");