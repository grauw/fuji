<?php
	class L10nManager {
		static function loadL10n()
		{
			$lang;
			if(isset(UserManager::getUser()->language))
			{
				$lang = UserManager::getUser()->language;
				if (!file_exists('../l10n/'.$lang.'.php'))
					$lang = 'default';
				include_once "$lang.php";
			}
			else
				include_once "default.php";
		}
	}
?>
