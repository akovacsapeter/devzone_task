<?php

class registration_post_error extends request_error {

	private $name;
	private $email;
	private $password;
	private $database;
	
	public function __construct($name, $email, $password, $database) {
		
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->database = $database;

	}

	public function check() {

		$error = false;
		if ($this->name == "") {
			parent::add("Name", "empty");
			$error = true;
		}
		if ($this->email == "") {
			parent::add("E-mail", "empty");
			$error = true;
		} elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			parent::add("E-mail", "invalid");
			$error = true;
		}
		if ($this->email != "" && $this->database->size("dt_registration", "email = '".$this->email."'") > 0) {
			parent::add("E-mail", "already exists");
			$error = true;
		}
		if ($this->password == "") {
			parent::add("Password", "empty");
			$error = true;
		} elseif (!$this->is_password_strong_enough($this->password)) {
			parent::add("Password", $this->password_error);	
			$error = true;
		}
		if (!$error) {
			return true;
		} else {
			return false;
		}

	}

}