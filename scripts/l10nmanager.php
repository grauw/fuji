<?php
	require_once "database.php";
	require_once "usermanager.php";
	
	class L10nManager {
		private $custom_language_data = array();
		private $default_language_data = array();
		
		function __construct()
		{
			$l10n = isset(UserManager::getUser()->language) ? UserManager::getUser()->language : "";
			$defaultl10n = self::getDefaultL10n();
			$this->default_language_data = $this->loadL10n($defaultl10n);
			if($l10n && $l10n != $defaultl10n)
				$this->custom_language_data = $this->loadL10n($l10n);
			header('Content-Language: '.$this->language);
		}
		
		function loadL10n($l10n)
		{
			$l10ndata = array();
			$l10npath = self::checkL10nPaths($l10n);
			if($l10npath)
			{
				$xml = new XMLReader();
				$xml->open($l10npath);
				$caption = "";
				while($xml->read())
				{
					if($xml->name == 'message' && $xml->nodeType == XMLReader::ELEMENT)
					{
						$name = $xml->getAttribute('name');
						$xml->read();
						$value = $xml->value;
						if($name && $value)
							$l10ndata[$name] = $value;
					}
				}
				return $l10ndata;
			}
			else
				trigger_error("Failure loading L10n-file $l10n");
		}
		
		function __get($name) {
			if(isset($this->custom_language_data[$name]))
				return $this->custom_language_data[$name];
			elseif(isset($this->default_language_data[$name]))
				return $this->default_language_data[$name];
			return "FAIL:$name";
		}
		
		function __set($name, $value) {
		}
		
		function __isset($name) {
			return isset($this->custom_language_data[$name]) || isset($this->default_language_data[$name]);
		}
		
		function __unset($name) {
		}		
		
		/**
		 * Gets an ETag for this user's language file
		 */
		static function getL10nCache()
		{
			$languageInfo = isset(UserManager::getUser()->language) ? self::checkL10nPaths(UserManager::getUser()->language) : self::checkL10nPaths(self::getDefaultL10n());
			return new Cache(filemtime($languageInfo['path']), $languageInfo['lang'], FALSE);
		}
		
		/**
		 * Load the specified language
		 * @param $lang language to load language file of
		 */
		static function manualLoadL10n($lang)
		{
			$this->custom_language_data = $this->loadL10n($lang);
		}
		
		/**
		 * Return the path to the specified language file
		 * @param $lang language to find language file for
		 * @returns FALSE if the language file could not be found
		 */
		static function checkL10nPaths($lang)
		{
			if (file_exists("../../l10n/$lang.xml")) return "../../l10n/$lang.xml";
			if (file_exists("../l10n/$lang.xml"))    return "../l10n/$lang.xml";
			if (file_exists("l10n/$lang.xml"))       return "l10n/$lang.xml";
			return FALSE;
		}
		
		/**
		 * Return database array of available languages
		 */
		static function getL10ns()
		{
			return Database::getArray("SELECT * FROM " . Database::$db_tprefix . "languages");
		}
		
		/**
		 * Return default language
		 */
		static function getDefaultL10n()
		{
			$data = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
			return $data[0][0];	
		}		
	}
?>
