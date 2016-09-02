<?php

class request_error {

	private $errors = array();
	protected $password_error = "";

	public function __construct() {
	}

	public function add($label, $message) {
		$index = 0;

		if (sizeof($this->errors) > 0) {
			$index = sizeof($this->errors);
		}
		$this->errors[$index]["label"] = $label;
		$this->errors[$index]["message"] = $message;

	}

	public function has_error() {
		
		if (sizeof($this->errors)) {
			return true;
		} else {
			return false;
		}
		
	}

	public function error_message() {
		$error_message = array();
		
		foreach ($this->errors as $error) {
			array_push($error_message, $error["label"].": ".$error["message"]);
		}

		return implode(", ", $error_message);
		
	}

	protected function is_password_strong_enough($password) {
		if (strlen($password) < 8) {
        	$this->password_error = "Too short";
        	return false;
    	} elseif (!preg_match("#[0-9]+#", $password)) {
        	$this->password_error = "Must include at least one number";
        	return false;
    	} elseif (!preg_match("#[a-zA-Z]+#", $password)) {
        	$this->password_error = "Must include at least one letter";
        	return false;
    	} else {
    		return true;
		}
	}

}