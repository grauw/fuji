<?php
require_once 'css/stylesheets.php';
require_once 'template.php';
require_once '../scripts/l10nmanager.php';

class AJAX_Template extends Template {
	
	function apply($entry, Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		/*TODO applyUserlist*/
		if($entry == 'user'){
			include_once '../templates/ajax/userlist.php';
		}else{
			$templateview = $this->getTemplateView($entry->type);
			include_once $templateview->location;
		}
	}
	
	function applyMenu(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		include_once '../templates/ajax/menu.php';
	}
	
	function applyGroups(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		include_once '../templates/ajax/groups.php';
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
	
}
	
return 'AJAX_Template';

?>