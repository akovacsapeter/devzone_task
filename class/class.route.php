<?php

class route {

	private $page = "";
	private $url_array = array();
	private $database;

	public function __construct($url, $database) {

		$this->database = $database;
		$this->url_array = explode('/', $url);

		switch($this->url_array[0]) {
			case "registration" : {
				$this->page = "registration.php";
				break;
			}
			case "profile" : {
				if (is_numeric($_SESSION["suc_reg"])) {
					$this->page = "profile.php";
				} else {
					$this->page = "login.php";
				}
				break;
			}
			case "login" : {
				$this->page = "login.php";
				break;
			}
			case "logout" : {
				$this->page ="logout.php";
				break;
			}
			case "forgot" : {
				$this->page = "forgot.php";
				break;
			}
			case "greetings" : {
				if (is_numeric($_SESSION["suc_reg"])) {
					$this->page = "greetings.php";
				} else {
					$this->page = "login.php";
				}
				break;
			}
			default : {
				if (is_numeric($_SESSION["suc_reg"])) {
					$this->page = "greetings.php";
				} else {
					$this->page = "login.php";
				}
				break;	
			}
		}
	}

	public function get_page() {
		
		foreach($_REQUEST as $key => $val) {
			$_REQUEST[$key] = $this->database->escape($val);
		}
		return $this->page;
	}

}