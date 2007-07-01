<?php

abstract class Processor {

	abstract public function parse();
	static public function getProcessor($name) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "processors WHERE name = '$name'");
		$processordata = $data[0];
		if(!$processordata) trigger_error('processor not found');
		$processorclass = require_once($processordata['name'] . '.php');
		return new $processordata['name']();
	}
}

?>