<?php

class auth_user extends record {

	public $id;
	public $database;

	public function __construct($id, $database) {

		try {
			$this->id = $id;
			$this->database = $database;
			parent::__construct('dt_registration', $this->id, $this->database);
		} catch (Exception $e) {
			return false;
		}

   	}

}