<?php
	require_once "database.php";
	require_once "usermanager.php";
	require_once "resource.php";
	
	class L10nManager {
		private $languages = array();
		private $language_data = array();
		
		function __construct($initlang = FALSE, $plugin = NULL)
		{
			if (!$initlang) {
				$this->languages = self::intersectL10nLanguages();
				$language = key($this->languages);
				$this->loadL10n($language, $plugin);
				header('Content-Language: '.$language);
			} else {
				$this->loadL10n($initlang, $plugin);
				header('Content-Language: '.$initlang);
			}
		}
		
		function loadL10n($language, $plugin = NULL)
		{
			$l10npaths = self::checkL10nPaths($language, $plugin);
			if(count($l10npaths) > 0)
			{
				foreach($l10npaths as $l10npath)
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
				}
			}
			else
				trigger_error("Failure loading L10n-file for $language");
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
		 * Return the path to the specified language file
		 * @param $lang language to find language file for
		 * @returns FALSE if the language file could not be found
		 */
		static function checkL10nPaths($lang, $plugin = NULL)
		{
			$paths = array();
			$basepath = substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME']));
			if (file_exists("$basepath/l10n/$lang.xml")) array_push($paths, "$basepath/l10n/$lang.xml");
			if (file_exists("$basepath/plugins/$plugin/l10n/$lang.xml")) array_push($paths, "$basepath/plugins/$plugin/l10n/$lang.xml");
			return $paths;
		}
		
		/**
		 * Return list of available languages (with quality)
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
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$accept_lang = str_replace(' ', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if ($accept_lang) {
					$acceptlangs = explode(',', $accept_lang);
					foreach ($acceptlangs as $acceptlang) {
						list($lang, $q) = explode(';q=', $acceptlang);
						$q = isset($q) ? floatval($q) : 1.0;
						$languages[$lang] = $q;
					}
				} else {
					$languages['*'] = 1.0;
				}
			}
			return $languages;
		}
		
		/**
		 * Return list of languages that the user accepts (with quality)
		 */
		static function getLanguages() {
			$acceptlangs = self::getAcceptLanguage();
			if (isset(UserManager::getUser()->language))
				$acceptlangs[UserManager::getUser()->language] = 9999;
			$defaultlang = self::getDefaultLanguage();
			if (!isset($acceptlangs[$defaultlang]) || $acceptlangs[$defaultlang] <= 0)
				$acceptlangs[$defaultlang] = 0.0001;
			return $acceptlangs;
		}
		
		/**
		 * Return the intersection of the languages and l10ns (with quality).
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
				if ($rv[$l10n] <= 0) unset($rv[$l10n]);
			}
			arsort($rv, SORT_NUMERIC);
			return $rv;
		}
		
	}
?>
