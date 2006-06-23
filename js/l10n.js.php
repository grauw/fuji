<?php
	require_once("../scripts/l10nmanager.php");
	require_once("../scripts/usermanager.php");
	require_once("../scripts/caching.php");
	UserManager::authenticate();
	L10nManager::getL10nCache()->send();
	L10nManager::loadL10n();
	header('Content-Type: application/javascript; charset=utf-8');
?>
Fuji.messages = { 	warning							: "<?php echo L10n::$warning ?>",
					error							: "<?php echo L10n::$error ?>",
					sure_delete_group				: "<?php echo L10n::$sure_delete_group ?>",
					sure_delete_item				: "<?php echo L10n::$sure_delete_item ?>",
					passwords_do_not_match			: "<?php echo L10n::$passwords_do_not_match ?>",
					email_empty						: "<?php echo L10n::$email_empty ?>",
					invalid_url						: "<?php echo L10n::$invalid_url ?>",
					ok								: "<?php echo L10n::$ok ?>",
					cancel							: "<?php echo L10n::$cancel ?>",
					error_insufficient_rights		: "<?php echo L10n::$error_insufficient_rights ?>",
					possible_reasons				: "<?php echo L10n::$possible_reasons ?>",
					error_restricted_forum			: "<?php echo L10n::$error_restricted_forum ?>",
					error_rights_revoked			: "<?php echo L10n::$error_rights_revoked ?>",
					error_anonymous					: "<?php echo L10n::$error_anonymous ?>",
					error_deleted_resource			: "<?php echo L10n::$error_deleted_resource ?>",
					error_server_misconfiguration	: "<?php echo L10n::$error_server_misconfiguration ?>",
					error_resource_cannot_be_found	: "<?php echo L10n::$error_resource_cannot_be_found ?>",
					error_invalid_url				: "<?php echo L10n::$error_invalid_url ?>",
					error_unknown					: "<?php echo L10n::$error_unknown ?>",
					error_bug						: "<?php echo L10n::$error_bug ?>",
					contact_administrator			: "<?php echo L10n::$contact_administrator ?>",
					error_loading_resource			: "<?php echo L10n::$error_loading_resource ?>",
					loading							: "<?php echo L10n::$loading ?>",
					please_wait						: "<?php echo L10n::$please_wait ?>"
				}
