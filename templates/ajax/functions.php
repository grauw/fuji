<?php
require_once 'css/stylesheets.php';
require_once 'template.php';
require_once '../scripts/l10nmanager.php';

class AJAX_Template extends Template {
	
	public $groupid = null;
	
	function apply($entry, $page, Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		$templateview = $this->getTemplateView($entry->type);
		ob_start();
		include_once $templateview->location;
		if($this->acceptsGZIP())
			$this->outputGZIP();		
	}
	
	function applyUserlist(Template $template, $groupid=null) {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$template = $this;
		ob_start();		
		include_once '../templates/ajax/userlist.php';
		if($this->acceptsGZIP())
			$this->outputGZIP();			
	}
	
	function applyMenu(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		ob_start();
		include_once '../templates/ajax/menu.php';
		if($this->acceptsGZIP())
			$this->outputGZIP();			
	}
	
	function applyGroups(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		ob_start();		
		include_once '../templates/ajax/groups.php';
		if($this->acceptsGZIP())
			$this->outputGZIP();		
	}
	
	function failInsufficientRights(Template $template) {
		header('HTTP/1.0 401 Unauthorized');		
	}
	
	public function getPrefix()
	{
		if ($this->acceptsXHTML()) {
			header('Content-Type: application/xhtml+xml');
			$stylerv = '';
			foreach (AJAX_Stylesheets::$stylesheets as $stylesheet) {
				$stylerv .= '<?xml-stylesheet href="/templates/ajax/css/'. $stylesheet['href'] .'" ';
				if (isset($stylesheet['alternate'])) $stylerv .= 'alternate="yes" ';
				if (isset($stylesheet['title'])) $stylerv .= 'title="'. $stylesheet['title'] .'" ';
				$stylerv .= 'type="text/css"?'.">\n";
			}
			return '<?xml version="1.0" encoding="UTF-8"?'.">\n".$stylerv;
		} else {
			header('Content-Type: text/html');
		}
	}
	
	public function getHead()
	{
		$stylerv = '';
		foreach (AJAX_Stylesheets::$stylesheets as $stylesheet) {
			$stylerv .= '<link rel="'. (isset($stylesheet['alternate']) ? 'alternate ' : '') .'stylesheet" ';
			$stylerv .= 'href="/templates/ajax/css/'. $stylesheet['href'] .'" ';
			if (isset($stylesheet['title'])) $stylerv .= 'title="'. $stylesheet['title'] .'" ';
			if (isset($stylesheet['media'])) $stylerv .= 'media="'. $stylesheet['media'] .'" ';
			$stylerv .= 'type="text/css" />'."\n";
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
	
return 'AJAX_Template';

?>