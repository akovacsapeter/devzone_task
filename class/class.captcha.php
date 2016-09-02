<?php

class captcha {

	private $code = "";
	private $code_length = 8;

	public function __construct() {

		$this->code = $this->create_code();

	}

	private function create_code() {

		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$string = '';
 		$rand_max = strlen($characters) - 1;
 		for ($i = 0; $i < $this->code_length; $i++) {
      		$string .= $characters[mt_rand(0, $rand_max)];
 		}
 		return $string;

	}

	public function get_code() {

		return $this->code;

	}

}