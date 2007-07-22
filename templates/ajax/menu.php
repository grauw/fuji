<?php echo $this->getPrefix() ?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="menuDiv">
	<fuji:content>
		<h2><?php echo $this->l10n->menu ?></h2>
		<?php if(UserManager::getUser()->nickname == 'anonymous') { ?>
			<p><?php echo $this->l10n->anonymous_user ?></p>
		<?php } else { ?>
			<p><?php echo $this->l10n->logged_in_as . ' ' . UserManager::getUser()->nickname ?></p>
		<?php } ?>
		<ul>
			<?php if (UserManager::getUser()->nickname == 'anonymous') { ?>
				<li><a href="javascript:Fuji.login()"><?php echo $this->l10n->login ?></a></li>
				<li><a href="#register"><?php echo $this->l10n->register ?></a></li>
			<?php } else { ?>
				<li><a href="#<?php echo UserManager::getUser()->getURI() ?>"><?php echo $this->l10n->profile ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="javascript:Fuji.Dialogs.newDialog('serverSettingsDialog')"><?php echo $this->l10n->server_settings ?></a></li>
					<li><a href="javascript:Fuji.Dialogs.newRightsDialog('item/1')"><?php echo $this->l10n->rights ?></a></li>
					<li><a href="javascript:Fuji.Dialogs.newDialog('pluginsDialog')"><?php echo $this->l10n->plugins ?></a></li>
				<?php } ?>
				<li><a href="javascript:Fuji.Dialogs.newDialog('prefDialog')"><?php echo $this->l10n->preferences ?></a></li>
				<li><a href="#userlist"><?php echo $this->l10n->users ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="#grouplist"><?php echo $this->l10n->groups ?></a></li>
				<?php } ?>
				<?php $userstatus = (UserManager::getUser()->nickname == 'anonymous' ? Plugin::$USER_ANONYMOUS : (UserManager::getUser()->isAdmin() ? Plugin::$USER_ADMINISTRATOR : Plugin::$USER_LOGGED_IN));
				$this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, Plugin::$USER_LOGGED_IN, Plugin::$MENU_INDEX) ?>
				<li><a href="javascript:Fuji.logout()"><?php echo $this->l10n->logout ?></a></li>
			<?php } ?>
		</ul>
	</fuji:content>
</fuji:menu>
