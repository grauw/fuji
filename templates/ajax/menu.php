<?xml version="1.0" encoding="UTF-8"?>
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
			<li><a href="#search"><?php echo $this->l10n->search ?></a></li>
			<?php if (UserManager::getUser()->nickname == 'anonymous') { ?>
				<li><a href="javascript:Fuji.login()"><?php echo $this->l10n->login ?></a></li>
				<li><a href="register"><?php echo $this->l10n->register ?></a></li>
				<?php $this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, Plugin::$USER_ANONYMOUS, Plugin::$MENU_INDEX) ?>
			<?php } else { ?>
				<li><a href="#profile"><?php echo $this->l10n->profile ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="javascript:Fuji.Dialogs.newDialog('serverSettingsDialog')"><?php echo $this->l10n->server_settings ?></a></li>
				<?php } ?>
				<li><a href="javascript:Fuji.Dialogs.newDialog('prefDialog')"><?php echo $this->l10n->preferences ?></a></li>
				<li><a href="#users"><?php echo $this->l10n->users ?></a></li>
				<?php if (UserManager::isAdmin()) { ?>
					<li><a href="#groups"><?php echo $this->l10n->groups ?></a></li>
					<?php $this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, Plugin::$USER_ADMINISTRATOR, Plugin::$MENU_INDEX) ?>
				<?php } ?>
				<li><a href="javascript:Fuji.logout()"><?php echo $this->l10n->logout ?></a></li>
				<?php $this->plugin->getMenu(Plugin::$TEMPLATE_AJAX, Plugin::$USER_LOGGED_IN, Plugin::$MENU_INDEX) ?>
			<?php } ?>
		</ul>
	</fuji:content>
</fuji:menu>
