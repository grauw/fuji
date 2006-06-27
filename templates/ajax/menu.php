<?php echo $template->getPrefix() ?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="menuListDiv">
	<fuji:content>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			<p><?php echo L10n::$anonymous_user ?></p>
		<?php } else { ?>
			<p><?php echo L10n::$logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
		<?php } ?>
		<ul>
			<li><a href="#statistics"><?php echo L10n::$statistics ?></a></li>
			<?php if (UserManager::getUser()->nickname == 'anonymous') { ?>
				<li><a href="javascript:Fuji.login()"><?php echo L10n::$login ?></a></li>
				<li><a href="register"><?php echo L10n::$register ?></a></li>
			<?php } else { ?>
				<li><a href="#profile"><?php echo L10n::$profile ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="javascript:Fuji.newServerSettingsDialog()"><?php echo L10n::$server_settings ?></a></li>
				<?php } ?>
				<li><a href="javascript:Fuji.newPreferencesDialog()"><?php echo L10n::$preferences ?></a></li>
				<li><a href="#users"><?php echo L10n::$users ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="#groups"><?php echo L10n::$groups ?></a></li>
				<?php } ?>
				<li><a href="javascript:Fuji.logout()"><?php echo L10n::$logout ?></a></li>
			<?php } ?>
		</ul>
	</fuji:content>
</fuji:menu>
