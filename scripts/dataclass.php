<?php
require_once 'database.php';

	class Dataclass {
		private $data = array();
		private $table;
		
		function __construct($data)
		{
			$this->data = $data;
		}		
		
		function getData()
		{
			return $this->data;
		}		
		
		function __get($name) {
			if(!isset($this->data[$name]))
				trigger_error("Undefined variable $name requested");
			return $this->data[$name];
		}
		
		function __set($name, $value) {
			$this->data[$name] = $value;
			Database::execute("UPDATE " . Database::$db_tprefix . $table . " SET $name='$value' WHERE id='$this->id'");
		}
		
		function __isset($name) {
			return isset($this->data[$name]);
		}
		
		function __unset($name) {
			unset($this->data[$name]);
		}		
}
?>
