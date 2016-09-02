<?php

class record {

	public $view;
	public $id = 0;
	public $database;

	public function __construct($view = '', $id, $database) {
		
		$this->database = $database;

		if ($view != '') {
			$this->view = $view;
			if ($id != 0) {//instance from database
				$this->id = $id;
				foreach($this->database->row('SELECT * FROM '.$this->view.' WHERE id = "'.$id.'"') as $field => $value) {
					if ($field != 'id') {
						$this->{$field} = $value;
					}
				}
			} else {//init with null
				foreach($this->database->result('SHOW COLUMNS FROM '.$this->view) as $field) {
					if ($field['Field'] != 'id') {
						if (strpos($field['Type'], 'char') !== false || strpos($field['Type'], 'text') !== false || strpos($field['Type'], 'blob') !== false) {
							$this->{$field['Field']} = '';
						} else {
							$this->{$field['Field']} = 0;
						}
					}
				}	
			}
		}

	}

	public function get($property) {
		return $this->{$property};
	}

	public function set($property, $value) {
		$this->{$property} = $value;
		return $this->{$property};
	}

	public function save() {

		if ($this->view != '') {
			if ($this->id == 0) {
				$query  = 'INSERT INTO '.$this->view.' SET ';
			} else {
				$query = 'UPDATE '.$this->view.' SET ';
			}
			foreach($this->database->result('SHOW COLUMNS FROM '.$this->view) as $field) {
				if ($field['Field'] != 'id' && $field['Field'] != 'hash') {
					$query .= $field['Field'].' = "'.addslashes($this->{$field['Field']}).'", ';
				}
			}
			$query = substr($query, 0, -2);//truncate last comma
			if ($this->id != 0) {
				$query .= " WHERE id = '".$this->id."'";
			}
			$this->database->execute($query);
			if ($this->database->errno() == 0) {
				if ($this->id == 0) {
					$this->id = $this->database->insert_id();
				}
				return $this->id;
			} else {
				return false;
			}
		}

	}

	public function drop() {

		if ($this->view != '' && $this->id != 0) {
			if (($this->database->execute('DELETE FROM '.$this->view.' WHERE id = "'.$this->id.'"')) != false) {
				return true;
			} else {
				return false;
			}
		}
		
	}

}