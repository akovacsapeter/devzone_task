<?php
session_start();

//database connection
define("DATABASE_HOST", "localhost");
define("DATABASE_USER", "root");
define("DATABASE_PASSWORD", "");
define("DATABASE_DATABASE", "dbname");
require("class/class.database.php");
require("class/class.record.php");
require("class/class.request_error.php");
$database = new database();

define("URL", "http://kovacsp.hu/devzone");

//debug stuff
define('NO_DEBUG', 0);
define('DB_DEBUG', 1);
define('CODE_DEBUG', 2);
$debug_level = CODE_DEBUG;