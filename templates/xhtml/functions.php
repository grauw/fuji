<?php
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/dataclasses/stylesheet.php';

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
		$head = '<meta http-equiv="Content-Type" content="' .
			($this->acceptsXHTML() ? 'application/xhtml+xml' : 'text/html') . '; charset=utf-8" />';
		$head .= '<link rel="icon" type="image/png" href="' . $this->getForumRoot() . 'images/siteicon.png" />';
		if (!$this->acceptsXHTML() && !$this->isEVEIngameBrowser()) {
			$head .= Stylesheet::getStyleSheetsHTML();
		} else if ($this->isEVEIngameBrowser()) {
			$head .= '<link rel="stylesheet" href="'.$this->getForumRoot().'style/eve-igb/css/eve-igb.css" type="text/css" />';
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
	
	public function isEVEIngameBrowser()
	{
		return isset($_SERVER['HTTP_EVE_TRUSTED']);
	}
	
	public function getBreadcrumb()
	{
		$breadcrumb = array();
		$curentry = $this->entry;
		while ($curentry->type != Entry::$TYPE_SYSTEM) {
			$breadcrumb[] = '<a href="'.htmlentities($curentry->getRootedURI()).'">'.htmlentities($curentry->title, ENT_NOQUOTES).'</a>';
			$curentry = $curentry->getParent();
		}
		return implode(' &gt; ', array_reverse($breadcrumb));
	}
	
}
	
return 'XHTML_Template';

?>