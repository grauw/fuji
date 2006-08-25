<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'l10nmanager.php';
require_once 'pluginmanager.php';

class Template extends Dataclass {
	
	public $groupid;
	public $groups;
	public $entry;
	public $entries;
	public $page;
	public $l10n;
	public $plugin;
	public $pluginname;
	public $date;
	public $today;
	
	static function getTableName() {
		return 'templates';
	}
	static function getPrimaryKeyName() {
		return 'mimetype';
	}
	static function getResourceUriFormat() {
		return 'template/%s';
	}
	
	static function getTemplate($mimetype = NULL) {
		if ($mimetype == NULL) $mimetype = self::getMimeType();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE mimetype = '".addslashes($mimetype)."'");
		$templatedata = $data[0];
		if(!$templatedata) trigger_error('template not found');
		$templateclass = require_once(realpath(dirname(__FILE__).'/../'.$templatedata['basedir']));
		header("Content-Type: $mimetype; charset=UTF-8");
		return new $templateclass($templatedata);
	}
	
	static function getPluginTemplate($plugin, $mimetype = NULL) {
		if ($mimetype == NULL) $mimetype = self::getMimeType();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE mimetype = '".addslashes($mimetype)."'");
		$templatedata = $data[0];
		if(!$templatedata) trigger_error('template not found');
		$templateclass = require_once(realpath(dirname(__FILE__).'/../plugins/'.$plugin.'/'.$templatedata['basedir']));
		header("Content-Type: $mimetype; charset=UTF-8");
		$t = new $templateclass($templatedata);
		$t->pluginname = $plugin;
		return $t;
	}	
	
	function getTemplateView($view) {
		if(isset($this->pluginname))
		{
			if (!preg_match('/^\w+$/', $view)) return FALSE;
			$file = 'plugins/'.$this->pluginname.'/'.dirname($this->basedir).'/'.$view.'.php';
			if (!file_exists(realpath(dirname(__FILE__).'/../'.$file))) return FALSE;
			return realpath(dirname(__FILE__).'/../'.$file);
		}
		else
		{
			if (!preg_match('/^\w+$/', $view)) return FALSE;
			$file = dirname($this->basedir).'/'.$view.'.php';
			if (!file_exists(realpath(dirname(__FILE__).'/../'.$file))) return FALSE;
			return realpath(dirname(__FILE__).'/../'.$file);
		}
	}
	
	function applyView($view) {
		if(isset($this->pluginname))
		{
			$this->l10n = new L10nManager();
			$this->startGZIP();
			require_once($this->getTemplateView($this->pluginname, $view));		
		}
		else
		{
			$this->plugin = new PluginManager();
			$this->l10n = new L10nManager();
			$this->startGZIP();
			require_once($this->getTemplateView($view));
		}
	}
	
	static function getMimeType() {
		if (isset($_GET['mime'])) {
			$mimetype = $_GET['mime'];
		} else {
			$mimetype = $_SERVER['HTTP_ACCEPT'];
		}
		
		// TODO: select the MIME type of choice
		
		Resource::addVary('Content-Type');
		if (isset($_GET['mime'])) return $_GET['mime'];
		if (self::acceptsFujiXHTML()) return 'application/x-fuji+xhtml+xml';
		if (self::acceptsXHTML()) return 'application/xhtml+xml';
		return 'text/html';
		
		// TODO: else 406 Not Acceptable
		// Note: Quote: HTTP/1.1 servers are allowed to return responses which are
		// not acceptable according to the accept headers sent in the request. (rfc2616 10.4.7)
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

?>