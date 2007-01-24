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
		$head = '<meta http-equiv="Content-Type" content="' . $this->acceptsXHTML() ?
			'application/xhtml+xml' : 'text/html' . '; charset=utf-8" />';
		$head .= '<link rel="icon" type="image/png" href="' . $this->getForumRoot() . 'images/siteicon.png" />';
		if (!$this->acceptsXHTML()) {
			$head .= Stylesheet::getStyleSheetsHTML();
		}
		return $head;
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