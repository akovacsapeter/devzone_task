<?php

class login_attempt {

	private $geoip;
	private $email;
	private $password;
	private $user_id;
	private $log_ttl;
	private $log_block_time;
	private $max_attempts_same_ip;
	private $max_attempts_same_network24;
	private $max_attempts_same_network16;
	private $max_attempst_same_user;
	private $needs_captcha;
	private $database;

	public function __construct($geoip, $email, $password, $database) {

		$this->database = $database;

		$this->geoip = $geoip;
		$this->email = $email;
		$this->password = $password;

		$this->user_id = 0;
		$this->log_ttl = 3600;
		$this->log_block_time = 300;
		$this->max_attempts_same_ip = 3;
		$this->max_attempts_same_network24 = 500;
		$this->max_attempts_same_network16 = 1000;
		$this->max_attempst_same_user = 3;
		$this->needs_captcha = 0;

	}

	public function check() {

		if ($this->database->size("dt_registration", "email = '".$this->email."' AND password = '".md5($this->password)."' AND active = 1")) {
			//user exists and password match, no captcha error - login enabled
			$this->user_id = $this->database->get_field_value("dt_registration", "id", "email = '".$this->email."'");
			return true;
		} else {
			//clean expired log records
			$this->database->execute("DELETE from dt_login_log WHERE attempt_time < DATE_ADD(NOW(), INTERVAL -".$this->log_ttl." SECOND)");
			
			//determine if captcha needs to display
			$attempts_same_ip = 0;
			$attempts_same_network24 = 0;
			$attempts_same_network16 = 0;
			$attempst_same_user = 0;
			foreach($this->database->result("SELECT * FROM dt_login_log") as $log_item) {
				if ($log_item["ip_address"] == $this->geoip) {
					$attempts_same_ip += $log_item["attempts"];
					$attempts_same_network24 += $log_item["attempts"];
					$attempts_same_network16 += $log_item["attempts"];
				} elseif (cidr_match($log_item["ip_address"], get_range($this->geoip, 24))) {
					$attempts_same_network24 += $log_item["attempts"];
					$attempts_same_network16 += $log_item["attempts"];
				} elseif (cidr_match($log_item["ip_address"], get_range($this->geoip, 16))) {
					$attempts_same_network16 += $log_item["attempts"];
				}
				if ($log_item["user_email"] == $this->email) {
					$attempst_same_user += $log_item["attempts"];
				}
			}
			if ($attempts_same_ip >= $this->max_attempts_same_ip || $attempts_same_network24 >= $this->max_attempts_same_network24 || $attempts_same_network16 >= $this->max_attempts_same_network16 || $attempst_same_user >= $this->max_attempst_same_user) {
				$this->needs_captcha = 1;
			}

			//update log
			if ($this->database->size("dt_login_log", "user_email = '".$this->email."' AND ip_address = '".$this->geoip."' AND attempt_time > DATE_ADD(NOW(), INTERVAL -".$this->log_block_time." SECOND)") > 0) {//block found with user and ip
				$this->database->execute("UPDATE dt_login_log SET attempts = attempts + 1 WHERE user_email = '".$this->email."' AND ip_address = '".$this->geoip."' AND attempt_time > DATE_ADD(NOW(), INTERVAL -".$this->log_block_time." SECOND)");
			} elseif ($this->database->size("dt_login_log", "user_email = '".$this->email."' AND attempt_time < '".(strtotime("now") - $this->log_block_time)."'") > 0) {//block found with user only
				$this->database->execute("UPDATE dt_login_log SET attempts = attempts + 1 WHERE user_email = '".$this->email." AND attempt_time > DATE_ADD(NOW(), INTERVAL -".$this->log_block_time." SECOND)");
			} elseif ($this->database->size("dt_login_log", "ip_address = '".$this->geoip."' AND attempt_time < '".(strtotime("now") - $this->log_block_time)."'") > 0) {//block found with ip only
				$this->database->execute("UPDATE dt_login_log SET attempts = attempts + 1 WHERE ip = '".$this->geoip." AND attempt_time > DATE_ADD(NOW(), INTERVAL -".$this->log_block_time." SECOND)");
			} elseif ($this->database->size("dt_login_log", "ip_address = '".$this->geoip."' AND attempt_time < '".(strtotime("now") - $this->log_block_time)."'") > 0) {//block found with ip only
				$this->database->execute("UPDATE dt_login_log SET attempts = attempts + 1 WHERE ip_address = '".$this->geoip." AND attempt_time > DATE_ADD(NOW(), INTERVAL -".$this->log_block_time." SECOND)");
			} else {//no block found
				$this->database->execute("INSERT INTO dt_login_log SET user_email = '".$this->email."', ip_address = '".$this->geoip."', attempt_time = '".date("Y-m-d H:i:s")."', attempts = 1");
			}

			return false;
		}

	}

	public function success_user() {
		
		if ($this->user_id > 0) {
			return $this->user_id;
		} else {
			return false;
		}
		
	}

	public function needs_captcha() {

		if ($this->needs_captcha) {
			return true;
		} else {
			return false;
		}

	}

	private function get_range($ip, $mask) {

		return substr($ip, strrpos(".") + 1).".0/".$mask;

	}

	private function cidr_match($ip, $range) {//check subnet mask match

	    list($subnet, $bits) = explode('/', $range);
	    $ip = ip2long($ip);
	    $subnet = ip2long($subnet);
	    $mask = -1 << (32 - $bits);
	    $subnet &= $mask;
	    
	    return ($ip & $mask) == $subnet;
	
	}

}