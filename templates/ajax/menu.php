<?php echo $template->getPrefix() ?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="menuListDiv">
	<fuji:content>
		<?php
			if(UserManager::getUser()->nickname == 'anonymous')
				echo '<p>' . L10n::$anonymous_user . '</p>';
			else
				echo '<p>' . L10n::$logged_in_as . ' ' . UserManager::getUser()->nickname . '</p>';
			echo '<ul>'."\n";
			echo '<li><a href="#statistics/">'.L10n::$statistics.'</a></li>'."\n";
			if(UserManager::getUser()->nickname == 'anonymous'){
				echo '<li><a href="javascript:Fuji.login()">'.L10n::$login.'</a></li>'."\n";
				echo '<li><a href="register">'.L10n::$register.'</a></li>'."\n";
			}else{
				echo '<li><a href="#profile/">'.L10n::$profile.'</a></li>'."\n";
				if(UserManager::isAdmin())
					echo '<li><a href="javascript:Fuji.newServerSettingsDialog()">'.L10n::$server_settings.'</a></li>'."\n";
				echo '<li><a href="javascript:Fuji.newPreferencesDialog()">'.L10n::$preferences.'</a></li>'."\n";
				echo '<li><a href="#users/">'.L10n::$users.'</a></li>'."\n";
				if(UserManager::isAdmin()) echo '<li><a href="#groups/">'.L10n::$groups.'</a></li>'."\n";
				echo '<li><a href="javascript:Fuji.logout()">'.L10n::$logout.'</a></li>'."\n";
			}
			echo '</ul>'."\n";
		?>
	</fuji:content>
</fuji:menu>
