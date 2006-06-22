<?php
require_once 'template.php';
require_once '../scripts/l10nmanager.php';

class RSS_Template extends Template {
	
	public $groupid = null;
	
	function apply($entry, Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		$templateview = $this->getTemplateView($entry->type);
		ob_start();
		include_once $templateview->location;
		if($this->acceptsGZIP())
			$this->outputGZIP();		
	}
	
	function failInsufficientRights(Template $template) {
		header('HTTP/1.0 401 Unauthorized');		
	}
	
	public function getPrefix()
	{
		if ($this->acceptsRSS()) {
			header('Content-Type: application/rss+xml');
		} else {
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
	
	public function acceptsGZIP()
	{
		return strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ? true : false;
	}
	
	public function outputGZIP()
	{
		$contents = ob_get_contents();
		ob_end_clean();
		header("Content-Encoding: gzip");
		$output = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		$output .= substr(gzcompress($contents), 0, -4);
		echo $output; 
		exit();	
	}
}
	
return 'RSS_Template';

?>