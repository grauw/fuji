<?php

require_once "processor.php";

class None extends Processor {
	public function parse() {
		return func_get_arg(0);
	}
}