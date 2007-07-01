<?php
require_once 'scripts/dataclass.php';
require_once 'scripts/database.php';
require_once 'scripts/l10nmanager.php';
require_once 'scripts/pluginmanager.php';

/**
 * Generic base class for all templates.
 * 
 * [insert summary here]
 * 
 * In terms of the MVC design pattern, the Template class can be considered the 'view'
 * of the "Dataclass, Template, Resource" threesome.
 * 
 * [insert further documentation here]
 */
class Template extends Dataclass {
	
	// These are variables that can be passed along the template... if you pass a new variable
	// without declaring it here, the template will generate an error.
	public $groups;
	public $entry;
	public $entries;
	public $user;
	public $users;
	public $page;
	public $l10n;
	public $message;
	public $plugin;
	public $pluginname;
	public $date;
	public $today;
	public $redirecturl;
	
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
		if (!$templatedata) trigger_error('template not found for MIME type '.$mimetype);
		$templateclass = require_once $templatedata['basedir'];
		return new $templateclass($templatedata);
	}
	
	static function getPluginTemplate($plugin, $mimetype = NULL) {
		if ($mimetype == NULL) $mimetype = self::getMimeType();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE mimetype = '".addslashes($mimetype)."'");
		$templatedata = $data[0];
		if (!$templatedata) trigger_error('template not found for MIME type '.$mimetype);
		$templateclass = require_once 'plugins/'.$plugin.'/'.$templatedata['basedir'];
		$t = new $templateclass($templatedata);
		$t->pluginname = $plugin;
		return $t;
	}	
	
	function getTemplateView($view) {
		if (isset($this->pluginname))
		{
			$file = 'plugins/'.$this->pluginname.'/'.dirname($this->basedir).'/'.$view.'.php';
			if (!file_exists($file)) return FALSE;
			return $file;
		}
		else
		{
			$file = dirname($this->basedir).'/'.$view.'.php';
			if (!file_exists($file)) return FALSE;
			return $file;
		}
	}
	
	function applyView($view) {
		if (isset($this->pluginname)) {
			$templatepath = $this->getTemplateView($this->pluginname, $view);
		} else {
			$templatepath = $this->getTemplateView($view);
			$this->plugin = new PluginManager();
		}
		if ($templatepath === FALSE) return;
		$this->l10n = new L10nManager();
		$this->startGZIP();
		header("Content-Type: $this->mimetype; charset=UTF-8");
		require_once($templatepath);
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