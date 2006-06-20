<?php

require_once("stylesheet.php");

class XHTML {
	
	/**
	 * Sets correct content type, outputs XML and stylesheet headers.
	 */
	static function pageHeaders()
	{
		if (XHTML::acceptsXHTML()) {
			header('Content-Type: application/xhtml+xml; charset=utf-8');
			$xmlstylesheet = Stylesheet::getStyleSheetsXML();
			return '<?xml version="1.0" encoding="UTF-8"?'.">\n" . $xmlstylesheet;
		} else {
			header('Content-Type: text/html; charset=utf-8');
		}
	}
	
	static function headContent()
	{
		if (!XHTML::acceptsXHTML()) {
			Stylesheet::getStyleSheetsHTML();
		}
	}
	
	static function acceptsXHTML()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') ? true : false;
	}
	
}

?>
