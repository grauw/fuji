<?php
require_once 'template.php';
require_once '../scripts/stylesheet.php';

class AJAX_Template extends Template {
	
	public function getPrefix()
	{
		return '<?xml version="1.0" encoding="UTF-8"?'.">\n";
	}
	
	public function getHead()
	{
	}
	
	public function getHeader()
	{
	}
	
	public function getFooter()
	{
	}
	
}
	
return 'AJAX_Template';

?>
