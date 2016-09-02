<?php

class login_post_error extends request_error {

	private $email = "";
	private $password = "";
	private $captcha_set = "";
	private $captcha_typed = "";

	public function __construct($email, $password, $captcha_set, $captcha_typed) {
		
		$this->email = $email;
		$this->password = $password;
		$this->captcha_set = $captcha_set;
		$this->captcha_typed = $captcha_typed;

	}

	public function check() {

		$error = false;
		if ($this->email == "") {
			parent::add("E-mail", "empty");
			$error = true;
		} elseif (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			parent::add("E-mail", "invalid");
			$error = true;
		}
		if ($this->password == "") {
			parent::add("Password", "empty");
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