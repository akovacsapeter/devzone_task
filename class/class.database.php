<?php

require_once("config.php");

class database {

    private $mysqli;
    
    public function __construct() {
        
        if (($this->mysqli = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE)) == false) {
        	echo "Database connection error.";
        } else {
            $this->mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
        }
    }

    public function execute($query) {
    	$this->mysqli->query($query);
    }

    public function size($view, $condition = '') {
    	$query = 'SELECT count(*) as counted_rows FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
    		$row = $result->fetch_assoc();
    		return $row['counted_rows'];
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('size', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function result($query) {
    	if (($result = $this->mysqli->query($query)) != false) {
    		$result_array = array();
    		$i = 0;
    		while ($row = $result->fetch_array()) {
    			array_push($result_array, $row);
    		}
    		return $result_array;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('result', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function row($query) {
    	if (($result = $this->mysqli->query($query)) != false) {
    		return $result->fetch_assoc();
		}  else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('row', $query, $debug_backtrace[0]);
			return false;
		}
    }

    public function collection($view, $fields = '*', $condition = '', $order = '', $limit = '') {
    	$query = 'SELECT '.$fields.' FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	if ($order != '') {
    		$query .= ' ORDER By '.$order;
    	}
    	if ($limit != '') {
    		$query .= ' LIMIT '.$limit;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
            $extended_class_file = 'class/class.'.$view.'.php';
    		if (file_exists($extended_class_file)) {
                require_once($extended_class_file);
                $class_name = $view;
    		} else {
    			require_once('class.record.php');
                $class_name = 'record';
    		}
    		$collection = array();
    		while ($row = $result->fetch_array()) {
                if ($class_name == 'record') {
                    $record = new record($view, $row['id']);
                } else {
                    $record = new $class_name($row['id']);
                }
	    		foreach($result->fetch_fields() as $field) {
	    			if ($field->name != 'id') {
	    				$record->set($field->name, $row[$field->name]);
	    			}
	    		}
	    		array_push($collection, $record);
	    	}
			return $collection;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('collection', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function group_collection($view, $group_field, $fields = '*', $condition = '', $order = '', $limit = '') {
    	$query = 'SELECT '.$fields.' FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	$query .= ' GROUP By '.$group_field;
    	if ($order != '') {
    		$query .= ' ORDER By '.$order;
    	}
    	if ($limit != '') {
    		$query .= ' LIMIT '.$limit;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
    		$extended_class_file = 'class/class.'.$view.'.php';
            if (file_exists($extended_class_file)) {
                require_once($extended_class_file);
                $class_name = $view;
            } else {
                require_once('class.record.php');
                $class_name = 'record';
            }
    		$collection = array();
    		while ($row = $result->fetch_array()) {
	    		if ($class_name == 'record') {
                    $record = new record($view, $row['id']);
                } else {
                    $record = new $class_name($row['id']);
                }
	    		foreach($result->fetch_fields() as $field) {
	    			if ($field->name != 'id') {
	    				$record->set($field->name, $row[$field->name]);
	    			}
	    		}
	    		array_push($collection, $record);
	    	}
			return $collection;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('group_collection', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function record($view, $fields = '*', $condition = '') {
    	$query = 'SELECT '.$fields.' FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
            $row = $result->fetch_assoc();
    		$extended_class_file = 'class/class.'.$view.'.php';
            if (file_exists($extended_class_file)) {
                require_once($extended_class_file);
                $record = new $view($row['id']);
                $class_name = $view;
            } else {
                require_once('class.record.php');
                $record = new record($view, $row['id'], $this);
            }  		
    		
    		foreach($result->fetch_fields() as $field) {
    			if ($field->name != 'id') {
    				$record->set($field->name, $row[$field->name]);
    			}
    		}
			return $record;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('record', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function first($view, $order, $fields = '*', $condition = '') {
    	$query = 'SELECT '.$fields.' FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	$query .= ' ORDER By '.$order.' LIMIT 1';
    	if (($result = $this->mysqli->query($query)) != false) {
    		$extended_class_file = 'class/class.'.$view.'.php';
            if (file_exists($extended_class_file)) {
                require_once($extended_class_file);
                $class_name = $view;
            } else {
                require_once('class.record.php');
                $class_name = 'record';
            }
    		$row = $result->fetch_assoc();
            if ($class_name == 'record') {
                $record = new record($view, $row['id']);
            } else {
                $record = new $class_name($row['id']);
            }
    		foreach($result->fetch_fields() as $field) {
    			if ($field->name != 'id') {
    				$record->set($field->name, $row[$field->name]);
    			}
    		}
			return $record;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('first', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function aggregation($view, $function, $field, $condition = '') {
    	$query = 'SELECT '.$function.'('.$field.')'.' as aggregation FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
    		$row = $result->fetch_assoc();
    		return $row['aggregation'];
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('aggregation', $query, $debug_backtrace[0]);
    		return false;
    	}
    }

    public function get_field_value($view, $field, $condition = '') {
    	$query = 'SELECT '.$field.' as field_value FROM '.$view;
    	if ($condition != '') {
    		$query .= ' WHERE '.$condition;
    	}
    	if (($result = $this->mysqli->query($query)) != false) {
    		$row = $result->fetch_assoc();
    		return $row['field_value'];
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('get_field_value', $query, $debug_backtrace[0]);
    		return false;
    	}	
    }

    public function set_field_value($view, $field, $value, $condition) {
    	$query = 'UPDATE '.$view.' SET '.$field.' = "'.addslashes($value).'" WHERE '.$condition;
    	if (($result = $this->mysqli->query($query)) != false) {
    		return true;
    	} else {
            $debug_backtrace = debug_backtrace();
            $this->ddd('set_field_value', $query, $debug_backtrace[0]);
    		return false;
    	}	
    }

    public function insert_id() {
    	return $this->mysqli->insert_id;
    }

    public function errno() {
    	return $this->mysqli->errno;
    }

    public function error() {
        return $this->mysqli->error;
    }

    public function escape($value) {
        return $this->mysqli->real_escape_string($value);   
    }

    public function ddd($method, $query, $debug_backtrace) {
        echo '<div class="np-error">DATABASE INTERFACE ERROR<br/>';
        echo 'Method: '.$method.'<br/>';
        echo 'Query: '.$query.'<br/>';
        echo 'Error message: '.$this->mysqli->error.'<br/>';
        echo 'Called from: '.$debug_backtrace['file'].'<br/>';
        echo 'Line: '.$debug_backtrace['line'].'</div>';
        die();
    }

    public function __destruct() {
        $this->mysqli->close();
    }

}