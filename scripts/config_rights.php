<?php 
	$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
	require_once 'usermanager.php';
	require_once 'l10nmanager.php';
	require_once 'group.php';
	UserManager::authenticate();
	L10nManager::loadL10n();
	$groups = Group::getAllGroups();

?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Config rights</title>
	</head>
	<body>
		<?php echo count($groups) ?>
		<div id="configRightsDialog" style="display: none">
			<div id="configRightsTitle" class="hd"><?php echo L10n::$config_rights ?></div>
			<div id="configRightsForm" class="bd">
				<form id="configRightsF" name="configRightsF" class="createEditF" method="POST" action="config_rights/">
				<?php foreach($groups as $group) { ?>	
					<?php echo $group->name ?>
					<label><input type="checkbox" name="configRightsRead<?php echo $group->id ?>" id="configRightsRead<?php echo $group->id ?>" /><?php echo L10n::$read ?></label>
					<label><input type="checkbox" name="configRightsPost<?php echo $group->id ?>" id="configRightsPost<?php echo $group->id ?>" /><?php echo L10n::$post ?></label>
					<label><input type="checkbox" name="configRightsEdit<?php echo $group->id ?>" id="configRightsEdit<?php echo $group->id ?>" /><?php echo L10n::$edit ?></label>
					<label><input type="checkbox" name="configRightsDelete<?php echo $group->id ?>" id="configRightsDelete<?php echo $group->id ?>" /><?php echo L10n::$delete ?></label><br />
				<?php } ?>
					<input type="hidden" name="configRightsEntryId" id='<?php echo $id ?>' />
				</form>
			</div>
		</div>
	</body>
</html>
