<?php
require_once 'template.php';
require_once '../scripts/stylesheet.php';
require_once '../scripts/l10nmanager.php';
require_once '../scripts/database.php';

class XHTML_Template extends Template {
	
	function apply($entry, $lang) {
		L10nManager::loadL10n();
		$template = $this;
		$templateview = $this->getTemplateView($entry->type);
		include_once $templateview->location;
	}
	
	function applyIndex() {
		L10nManager::loadL10n();
		$template = $this;
//		$this->startGZIP();
		include_once '../templates/xhtml/index.php';
	}
	
	public function getPrefix()
	{
		if ($this->acceptsXHTML()) {
			header('Content-Type: application/xhtml+xml; charset=utf-8');
			$xmlstylesheet = Stylesheet::getStyleSheetsXML();
			return '<?xml version="1.0" encoding="UTF-8"?'.">\n" . $xmlstylesheet;
		} else {
			header('Content-Type: text/html; charset=utf-8');
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