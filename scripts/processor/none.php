<?php
require_once 'scripts/processor/processor.php';

class None extends Processor {
	public function parse() {
		return func_get_arg(0);
	}
}

?>