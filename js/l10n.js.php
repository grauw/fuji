<?php	//TODO: Use javascript to access the language files (XML) directly, instead of using PHP
	require_once("../scripts/l10nmanager.php");
	require_once("../scripts/caching.php");
	L10nManager::getL10nCache()->send();
	$l10n = new L10nManager();
	header('Content-Type: application/javascript; charset=utf-8');
?>
Fuji.Messages = { 	warning							: "<?php echo $l10n->warning ?>",
					error							: "<?php echo $l10n->error ?>",
					sure_delete_group				: "<?php echo $l10n->sure_delete_group ?>",
					sure_delete_item				: "<?php echo $l10n->sure_delete_item ?>",
					passwords_do_not_match			: "<?php echo $l10n->passwords_do_not_match ?>",
					email_empty						: "<?php echo $l10n->email_empty ?>",
					invalid_url						: "<?php echo $l10n->invalid_url ?>",
					ok								: "<?php echo $l10n->ok ?>",
					cancel							: "<?php echo $l10n->cancel ?>",
					error_insufficient_rights		: "<?php echo $l10n->error_insufficient_rights ?>",
					possible_reasons				: "<?php echo $l10n->possible_reasons ?>",
					error_restricted_forum			: "<?php echo $l10n->error_restricted_forum ?>",
					error_rights_revoked			: "<?php echo $l10n->error_rights_revoked ?>",
					error_anonymous					: "<?php echo $l10n->error_anonymous ?>",
					error_deleted_resource			: "<?php echo $l10n->error_deleted_resource ?>",
					error_server_misconfiguration	: "<?php echo $l10n->error_server_misconfiguration ?>",
					error_resource_cannot_be_found	: "<?php echo $l10n->error_resource_cannot_be_found ?>",
					error_invalid_url				: "<?php echo $l10n->error_invalid_url ?>",
					error_unknown					: "<?php echo $l10n->error_unknown ?>",
					error_bug						: "<?php echo $l10n->error_bug ?>",
					contact_administrator			: "<?php echo $l10n->contact_administrator ?>",
					error_loading_resource			: "<?php echo $l10n->error_loading_resource ?>",
					loading							: "<?php echo $l10n->loading ?>",
					please_wait						: "<?php echo $l10n->please_wait ?>"
				}
