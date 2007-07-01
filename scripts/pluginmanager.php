<?php
require_once 'scripts/database.php';
require_once 'scripts/plugin.php';

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
		if(file_exists("plugins/$name/$name.php"));
		{
			include_once("plugins/$name/$name.php");
			array_push($this->plugins, new $name());
		}
	}
	static function getPlugins() {
		return Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "plugins");
	}
	static function reloadPlugins() {
		Database::execute('DELETE FROM plugins');
		$dirs = scandir('plugins');
		$plugins = array();
		foreach($dirs as $dir)
		{
			if(file_exists("plugins/$dir/$dir.php"))
			{
				include_once("plugins/$dir/$dir.php");
				$plugin = new $dir();
				$name = $dir;
				$description = $plugin->description();
				$author = $plugin->author();
				$homepage = $plugin->homepage();
				$version = $plugin->version();
				//TODO: Check if Fuji version is okay
				$min_fuji_version = $plugin->min_fuji_version();
				$max_fuji_version = $plugin->max_fuji_version();
				Database::execute("INSERT INTO plugins (name,description,author,homepage,version,min_fuji_version,max_fuji_version) VALUES ('$name','$description','$author','$homepage','$version','$min_fuji_version','$max_fuji_version')");
			}
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