<?php
	require_once "database.php";
	
	class L10nManager {
		
		/**
		 * Loads the language file for this user
		 */
		static function loadL10n()
		{
			$languageInfo = L10nManager::findL10nPath();
			require_once $languageInfo['path'];
		}
		
		/**
		 * Gets an ETag for this user's language file
		 */
		static function getL10nCache() {
			$languageInfo = L10nManager::findL10nPath();
			return new Cache(filemtime($languageInfo['path']), $languageInfo['lang'], FALSE);
		}
		
		/**
		 * Resolves a language for this user
		 * @returns a language info hash array with keys:
		 *          'lang': the selected language
		 *          'path': the path to the language file (FALSE if file not found)
		 */
		static function findL10nPath()
		{
			if(isset(UserManager::getUser()->language))
			{
				$lang = UserManager::getUser()->language;
				$languagePath = L10nManager::checkL10nPaths($lang);
				if ($languagePath === FALSE) {
					$lang = L10nManager::getDefaultL10n();
					$languagePath = L10nManager::checkL10nPaths($lang);
				}
			}
			else
			{
				$lang = L10nManager::getDefaultL10n();
				$languagePath = L10nManager::checkL10nPaths($lang);
			}
			return array('path' => $languagePath, 'lang' => $lang);
		}
		
		/**
		 * Load the specified language
		 * @param $lang language to load language file of
		 */
		static function manualLoadL10n($lang)
		{
			require_once L10nManager::checkL10nPaths();
		}
		
		/**
		 * Load the specified language file
		 * @param $path path to language file
		 */
		static function manualLoadL10nPath($path)
		{
			require_once $path;
		}
		
		/**
		 * Return the path to the specified language file
		 * @param $lang language to find language file for
		 * @returns FALSE if the language file could not be found
		 */
		static function checkL10nPaths($lang)
		{
			if (file_exists("../../l10n/$lang.php")) return "../../l10n/$lang.php";
			if (file_exists("../l10n/$lang.php"))    return "../l10n/$lang.php";
			if (file_exists("l10n/$lang.php"))       return "l10n/$lang.php";
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
