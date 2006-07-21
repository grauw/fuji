<?php
require_once 'template.php';
require_once '../scripts/stylesheet.php';

class XHTML_Template extends Template {
	
	public function getPrefix()
	{
		if ($this->acceptsXHTML()) {
			$xmlstylesheet = Stylesheet::getStyleSheetsXML();
			return '<?xml version="1.0" encoding="UTF-8"?'.">\n" . $xmlstylesheet;
		}
	}
	
	public function getHead()
	{
		if ($this->acceptsXHTML()) {
			return Stylesheet::getStyleSheetsHTML();
		}
	}
	
	public function getHeader()
	{
	}
	
	public function getFooter()
	{
	}
	
	public function acceptsXHTML()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') ? true : false;
	}
	
}
	
return 'XHTML_Template';

?>