<?php
require("class/class.auth_user.php");
require("class/class.registration.php");
require("class/class.profile_post_error.php");
if (($auth_user = new auth_user($_SESSION["suc_reg"], $database)) == false) {
	header("Location: login");
	exit;
} else {
	$registration = new registration($_SESSION["suc_reg"], $database);
	if ($_POST["action"] == "profile_attempt") {
		$profile_post_error = new profile_post_error($_SESSION["suc_reg"], $_POST["name"], $_POST["email"], $_POST["password"], $database);//check fields
		$profile_post_error->check();
		if (!$profile_post_error->has_error()) {
			$registration->set("name", $_POST["name"]);
			$registration->set("email", $_POST["email"]);
			if ($_POST["password"] != "") {
				$registration->set("password", md5($_POST["password"]));
			}
			$registration->save();
			header("Location: profile&action=modify_thanks");
			exit;
		}
	}
	require("view/head.view.php");
	require("view/profile.view.php");
	require("view/bottom.view.php");
}