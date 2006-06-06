<?php echo $template->getPrefix() ?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="menuListDiv">
	<fuji:content>
		<?php
			echo '<p>' . L10n::$logged_in_as . ' ' . UserManager::getUser()->nickname . '</p>';
			echo '<ul>'."\n";
			if(UserManager::getUser()->nickname == 'anonymous'){
				echo '<li><a href="">'.L10n::$login.'</a></li>'."\n";
				echo '<li><a href="scripts/register/">'.L10n::$register.'</a></li>'."\n";
			}else{
				echo '<li><a href="profile/">'.L10n::$profile.'</a></li>'."\n";
				echo '<li><a href="javascript:Fuji.newPreferencesDialog()">'.L10n::$preferences.'</a></li>'."\n";
				echo '<li><a href="#users/">'.L10n::$users.'</a></li>'."\n";
			}
			echo '</ul>'."\n";
		?>
		<ul>
		</ul>
	</fuji:content>
</fuji:menu>