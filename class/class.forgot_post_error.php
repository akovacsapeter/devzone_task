<?php

class forgot_post_error extends request_error {

	private $email;
	private $captcha_set;
	private $captcha_typed;
	private $database;

	public function __construct($email, $captcha_set, $captcha_typed, $database) {
		
		$this->email = $email;
		$this->captcha_set = $captcha_set;
		$this->captcha_typed = $captcha_typed;
		$this->database = $database;

	}

	public function check() {

		$error = false;
		if ($this->email == "") {
			parent::add("E-mail", "empty");
			$error = true;
		} elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			parent::add("E-mail", "invalid");
			$error = true;
		} elseif ($this->database->size("dt_registration", "active = 1 AND email = '".$this->email."'") == 0) {
			parent::add("E-mail", "not activated or non-exists");
			$error = true;
		}
		if ($this->captcha_set != null && $this->captcha_typed == "") {
			parent::add("Captcha", "empty");
			$error = true;
		} elseif ($this->captcha_set != null && $this->captcha_set != $this->captcha_typed) {
			parent::add("Captcha", "wrong");
			$error = true;
		}
		if (!$error) {
			return true;
		} else {
			return false;
		}

	}

}