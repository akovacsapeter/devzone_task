<?php

class registration extends record {

	public $id;
	public $database;
	public $activate_from;
	public $activate_sender;
	public $activate_from_name;
	public $activate_subject;
	public $forgot_from;
	public $forgot_sender;
	public $forgot_from_name;
	public $forgot_subject;
	private $old_password;

	public function __construct($id, $database) {
		
		$this->id = $id;
		$this->database = $database;
		parent::__construct('dt_registration', $this->id, $this->database);
		$this->activate_from = "registration@kovacsp.hu";
		$this->activate_sender = "registration@kovacsp.hu";
		$this->activate_from_name = "Devzone Task";
		$this->activate_subject = "Activation";
		$this->forgot_from = "registration@kovacsp.hu";
		$this->forgot_sender = "registration@kovacsp.hu";
		$this->forgot_from_name = "Devzone Task";
		$this->forgot_subject = "Password reset";
		
   	}

   	public function get_activate_body($url) {

   		$activate_url = $url."/registration&action=activate&code=".$this->activate_code;
		$activate_body = "<html><body><p>Dear ".$this->name.",</p><p>You've succesfully registrated for Devzone Task.</p><p>Please click on this activate link within 48 hours:<br><a href='".$activate_url."'>".activate_url."</a></p><p>Sincerely: Devzone Task</p></body></html>";
		return $activate_body;

   	}

   	public function reset_password() {

   		$this->old_password = $this->password;
   		$new_password = substr(md5(uniqid(rand(), true)), 0, 8);
   		$this->set("password", md5($new_password));
   		$this->save();
		$forgot_body = "<html><body><p>Dear ".$this->name.",</p><p>On your request we've reset your password.</p><p>Your new password is the following:<br>".$new_password."</p><p>Note: You can change your password in your profile.<p>Sincerely: Devzone Task</p></body></html>";
		return $forgot_body;

   	}

   	public function restore_password() {

   		if ($this->old_password != "") {
   			$this->password = $this->old_password;
   			$this->save();
   			return true;
   		} else {
   			return false;
   		}

   	}

}