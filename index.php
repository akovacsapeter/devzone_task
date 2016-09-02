<?php
require("config.php");
require("class/class.route.php");
$route = new route($_REQUEST["url"], $database);
require($route->get_page());