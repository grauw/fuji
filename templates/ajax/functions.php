<?php
require_once 'css/stylesheets.php';
require_once 'template.php';
require_once '../scripts/l10nmanager.php';
require_once '../scripts/database.php';

class AJAX_Template extends Template {
	
	public $groupid = null;
	
	function apply($entry, $page) {
		L10nManager::loadL10n();
		$template = $this;
		$templateview = $this->getTemplateView($entry->type);
		$this->startGZIP();
		include_once $templateview->location;
	}
	
	function applyUserlist(Template $template, $groupid=null) {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/userlist.php';
	}
	
	function applyStatistics(Template $template) {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/statistics.php';
	}
	
	function applyProfile(Template $template) {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/profile.php';
	}

	function applyMenu(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/menu.php';
	}
	
	function applyGroups(Template $template) {
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/groups.php';
	}
	
	function applyRights($entry, $groups) {
		L10nManager::loadL10n();
		$template = $this;
		$this->startGZIP();
		include_once '../templates/ajax/rights.php';
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
		return strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') !== FALSE ? true : false;
	}
	
	public function startGZIP() {
		$gzip_level = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'gzip'");
		$gzip_level = $gzip_level[0][0];
		ini_set('zlib.output_compression_level', $gzip_level);
		ob_start('ob_gzhandler');
	}
	
}
	
return 'AJAX_Template';

?>
