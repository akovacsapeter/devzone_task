<?php

class profile_post_error extends request_error {

	private $id;
	private $name;
	private $email;
	private $password;
	private $database;
	
	public function __construct($id, $name, $email, $password, $database) {
		
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->database = $database;

	}

	public function check() {

		if ($this->name == "") {
			parent::add("Name", "empty");
		}
		if ($this->email == "") {
			parent::add("E-mail", "empty");
		} elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			parent::add("E-mail", "invalid");
		}
		if ($this->email != "" && $this->database->size("dt_registration", "email = '".$this->email."' AND id <> '".$this->id."'") > 0) {
			parent::add("E-mail", "already taken");
		}
		if ($this->password != "" && !$this->is_password_strong_enough($this->password)) {
			parent::add("Password", $this->password_error);	
		}

	}

}