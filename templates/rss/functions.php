<?php
require_once 'scripts/dataclasses/template.php';

class RSS_Template extends Template {
	
	public function getPrefix()
	{
		if (!$this->acceptsRSS()) {
			header('Content-Type: text/xml');
		}
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
	
	public function acceptsRSS()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/rss+xml') ? true : false;
	}
	
}
	
return 'RSS_Template';

?>