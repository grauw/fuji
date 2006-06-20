<?php
	require_once "database.php";
	class L10nManager {
		static $langs = array(	array('en-GB', 'English (Great Britain)'),
								array('nl-NL', 'Nederlands (Nederland)'));
		static function loadL10n()
		{
			if(isset(UserManager::getUser()->language))
			{
				$lang = UserManager::getUser()->language;
				if (file_exists('../l10n/'.$lang.'.php'))
					require_once '../l10n/'.$lang.'.php';
				else
					if (file_exists('l10n/'.$lang.'.php'))
						require_once 'l10n/'.$lang.'.php';
					else
					{
						$default = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
						$default = $default[0][0];
						if (file_exists("../l10n/$default.php"))
							require_once "../l10n/$default.php";
						else
							require_once "l10n/$default.php";
					}						
			}
			else
			{
				$default = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
				$default = $default[0][0];
				if (file_exists("../l10n/$default.php"))
					require_once "../l10n/$default.php";
				else
					require_once "l10n/$default.php";
			}
		}
		static function manualLoadL10n($lang)
		{
			if (file_exists('../../l10n/'.$lang.'.php'))
				require_once '../../l10n/'.$lang.'.php';
			else
				if (file_exists('../l10n/'.$lang.'.php'))
					require_once '../l10n/'.$lang.'.php';
				else
					if (file_exists('l10n/'.$lang.'.php'))
						require_once 'l10n/'.$lang.'.php';				
		}
		static function getL10ns()
		{
			return Database::getArray("SELECT * FROM " . Database::$db_tprefix . "languages");
		}
		static function getDefaultL10n()
		{
			$data = Database::getArray("SELECT value FROM " . Database::$db_tprefix . "defaults WHERE name = 'language'");
			return $data[0][0];	
		}		
	}
?>
