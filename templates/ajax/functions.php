<?php
require_once 'template.php';
require_once '../scripts/stylesheet.php';
require_once '../scripts/l10nmanager.php';
require_once '../scripts/database.php';

class AJAX_Template extends Template {
	
	public $groupid = null;
	
	function apply($entry, $page) {
		L10nManager::loadL10n();
		$thisview = $this->getTemplateView($entry->getView());
		$this->startGZIP();
		require_once($thisview);
	}
	
	function applyUserlist($groupid=null) {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/userlist.php';
	}
	
	function applyStatistics() {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/statistics.php';
	}
	
	function applyProfile() {
		if(isset($groupid)) $this->groupid = $groupid;
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/profile.php';
	}

	function applyMenu() {
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/menu.php';
	}
	
	function applyGroups() {
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/groups.php';
	}
	
	function applyRights($entry, $groups) {
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/rights.php';
	}
	
	function applySearch() {
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/search.php';
	}
	
	function applySearchResults($entries, $page) {
		L10nManager::loadL10n();
		$this->startGZIP();
		include_once '../templates/ajax/searchresults.php';
	}
	
	public function getPrefix()
	{
		if ($this->acceptsFujiXHTML()) {
			header('Content-Type: application/x-fuji+xhtml+xml');
			return '<?xml version="1.0" encoding="UTF-8"?'.">\n";
		} else if ($this->acceptsXHTML()) {
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
		return strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') !== FALSE ? true : false;
	}
	
	public function acceptsFujiXHTML()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/x-fuji+xhtml+xml') !== FALSE ? true : false;
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
