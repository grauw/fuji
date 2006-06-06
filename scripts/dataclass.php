<?php
require_once 'database.php';

	class Dataclass {
		private $data = array();
		
		function __construct($data)
		{
			$this->data = $data;
		}		
		
		function getData()
		{
			return $this->data;
		}		
		
		function setTable($table)
		{
			$this->table = $table;
		}
		
		function __get($name) {
			if(!isset($this->data[$name]))
				trigger_error("Undefined variable $name requested");
			return $this->data[$name];
		}
		
		function __set($name, $value) {
			$this->data[$name] = $value;
			$result = Database::execute("UPDATE " . Database::$db_tprefix . $this->getTable() . " SET $name='$value' WHERE id='$this->id'");
		}
		
		function __isset($name) {
			return isset($this->data[$name]);
		}
		
		function __unset($name) {
			unset($this->data[$name]);
		}		
}
?>
