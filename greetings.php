<?php
require("class/class.auth_user.php");
if (($auth_user = new auth_user($_SESSION["suc_reg"], $database)) == false) {
	header("Location: login");
	exit;
}
require("view/head.view.php");
require("view/greetings.view.php");
require("view/bottom.view.php");