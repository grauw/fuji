<?php
require_once 'database.php';
require_once 'plugin.php';
class PluginManager{
	private $plugins = array();
	function __construct() {
		$this->loadPlugins();
	}
	function loadPlugins() {
		$plugins = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "plugins WHERE enabled = 1");
		foreach($plugins as $plugin)
			$this->loadPlugin($plugin['name']);
	}
	function loadPlugin($name) {
		if(file_exists("../plugins/$name/$name.php"));
		{
			include_once("../plugins/$name/$name.php");
			array_push($this->plugins, new $name());
		}
	}
	
	//Plugin implementations
	function getCSS() {
		foreach($this->plugins as $plugin)
			if(method_exists($plugin, 'getCSS'))
				$plugin->getCSS();
	}	
	function getMenu($template, $user, $type) {
		foreach($this->plugins as $plugin)
			if(method_exists($plugin, 'getMenu'))
				$plugin->getMenu($template, $user, $type);
	}
	function getJS() {
		foreach($this->plugins as $plugin)
			if(method_exists($plugin, 'getJS'))
				$plugin->getJS();
	}
	function getIndexExtension($template, $user, $type) {
		foreach($this->plugins as $plugin)
			if(method_exists($plugin, 'indexExtension'))
				$plugin->getIndexExtension($template, $user, $type);
	}	
}
?>