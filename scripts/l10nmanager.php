<?php
	require_once "database.php";
	require_once "usermanager.php";
	require_once "resource.php";
	
	class L10nManager {
		private $primary_language;
		private $languages;
		private $language_data = array();
		
		function __construct()
		{
			$this->languages = self::intersectL10nLanguages();
			$language = key($this->languages);
			$this->loadL10n($language);
			header('Content-Language: '.$language);
		}
		
		function loadL10n($language)
		{
			$l10npath = self::checkL10nPaths($language);
			if($l10npath)
			{
				$xml = new XMLReader();
				$xml->open($l10npath);
				while($xml->read())
				{
					if($xml->name == 'message' && $xml->nodeType == XMLReader::ELEMENT)
					{
						$name = $xml->getAttribute('name');
						$xml->read();
						$value = $xml->value;
						if ($name && $value && !isset($this->language_data[$name]))
							$this->language_data[$name] = $value;
					}
				}
				if (!isset($this->primary_language)) $this->primary_language = $language;
				return TRUE;
			}
			else
				trigger_error("Failure loading L10n-file for $language");
			return FALSE;
		}
		
		function __get($name) {
			while(next($this->languages) !== FALSE) {
				if (!count($this->languages)) return "FAIL:$name";
				$this->loadL10n(key($this->languages));
			}
			return $this->language_data[$name];
		}
		
		function __set($name, $value) {
		}
		
		function __isset($name) {
			return isset($this->language_data[$name]);
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
			return $this->loadL10n($lang);
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
		 * Return array of available languages and their quality
		 */
		static function getL10ns()
		{
			$l10ns = array();
			$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "languages");
			foreach ($data as $item) {
				$l10ns[$item['id']] = 1.0;
			}
			return $l10ns;
		}
		
		/**
		 * Return database array of available languages
		 */
		static function getL10nData()
		{
			return Database::getArray("SELECT * FROM " . Database::$db_tprefix . "languages");
		}
		
		/**
		 * Return default language
		 */
		static function getDefaultLanguage()
		{
			$data = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
			return $data[0][0];
		}
		
		/**
		 * Return list of Accept-Languages
		 */
		static function getAcceptLanguage() {
			Resource::addVary('Accept-Language');
			$languages = array();
			$acceptlangs = explode(',', str_replace(' ', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']));
			$i = 1;
			foreach ($acceptlangs as $acceptlang) {
				$langcomponents = explode(';q=', $acceptlang);
				$lang = $langcomponents[0];
				// order also matters a tiny little bit
				$q = (isset($langcomponents[1]) ? floatval($langcomponents[1]) : 1.0) - 0.0001 * $i++;
				$languages[$lang] = $q;
			}
			return $languages;
		}
		
		static function getLanguages() {
			$userlangs = isset(UserManager::getUser()->language) ? array(UserManager::getUser()->language => 1.0) : array();
			$acceptlangs = self::getAcceptLanguage();
			$defaultlangs = array(self::getDefaultLanguage() => 0.0001);
			return array_merge($userlangs, $acceptlangs, $defaultlangs);
		}
		
		/**
		 * Return the intersection of the languages and l10ns, return
		 */
		static function intersectL10nLanguages() {
			$languages = self::getLanguages();
			$l10ns = self::getL10ns();
			$rv = array();
			foreach ($l10ns as $l10n => $quality) {
				$rv[$l10n] = -1;
				$l10nsub = $l10n;
				do {
					if (isset($languages[$l10nsub]))
						$rv[$l10n] = max($rv[$l10n], $quality * $languages[$l10nsub]);
					$l10nsub = substr($l10n, 0, strrpos($l10nsub, '-'));
				} while ($l10nsub !== '');
				if (isset($languages['*']) && $rv[$l10n] == -1)
					$rv[$l10n] = $quality * $languages['*'];
			}
			arsort($rv, SORT_NUMERIC);
			return $rv;
		}
		
	}
?>
