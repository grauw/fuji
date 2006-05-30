<?php

require_once 'css/stylesheets.php';
require_once 'template.php';

class XHTML_Template extends Template {
	
	function apply($entry, $lang, Template $template) {
		if (!file_exists(realpath(dirname(__FILE__).'../'.'l10n/'.$lang.'.php'))) {
			$lang = 'default';
		}
		include_once '../l10n/'.$lang.'.php';
		$template = $this;
		$templateview = $this->getTemplateView($entry->type);
		include_once $templateview->location;
	}
	
	function failInsufficientRights($lang, Template $template) {
		if (!file_exists(realpath(dirname(__FILE__).'../'.'l10n/'.$lang.'.php'))) {
			$lang = 'default';
		}
		include_once '../l10n/'.$lang.'.php';
		$template = $this;
		include_once 'error.php';		
	}	
	
	public function getPrefix()
	{
		if ($this->acceptsXHTML()) {
			header('Content-Type: application/xhtml+xml');
			$stylerv = '';
			foreach (XHTML_Stylesheets::$stylesheets as $stylesheet) {
				$stylerv .= '<?xml-stylesheet href="/templates/xhtml/css/'. $stylesheet['href'] .'" ';
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
		foreach (XHTML_Stylesheets::$stylesheets as $stylesheet) {
			$stylerv .= '<link rel="'. (isset($stylesheet['alternate']) ? 'alternate ' : '') .'stylesheet" ';
			$stylerv .= 'href="/templates/xhtml/css/'. $stylesheet['href'] .'" ';
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
	
return 'XHTML_Template';

?>