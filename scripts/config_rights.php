<?php 
	$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
	require_once 'usermanager.php';
	require_once 'l10nmanager.php';
	require_once 'group.php';
	require_once 'entry.php';
	UserManager::authenticate();
	L10nManager::loadL10n();
	$groups = Group::getAllGroups();
	$entry = Entry::getEntry($id);
	header('Content-Type: application/xhtml+xml; charset=utf-8');
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="configRightsDialog">
	<fuji:loadhandler>
		Fuji.setConfigRightsDialog();
	</fuji:loadhandler>
	<fuji:content>
		<div id="configRightsTitle" class="hd"><?php echo L10n::$config_rights ?></div>
		<div id="configRightsForm" class="bd">
			<form id="configRightsF" name="configRightsF" class="createEditF" method="POST" action="config_rights/">
			<?php foreach($groups as $group) { ?>	
				<?php echo $group->name ?>
				<label><input type="checkbox" 
						name="configRightsRead<?php echo $group->id ?>" 
						id="configRightsRead<?php echo $group->id ?>" 
						<?php if ($group->getRights($entry)->may_read) echo "checked='Checked'" ?> />
						<?php echo L10n::$read ?>
				</label>
				<label><input type="checkbox" 
					name="configRightsPost<?php echo $group->id ?>" 
					id="configRightsPost<?php echo $group->id ?>" 
					<?php if ($group->getRights($entry)->may_post) echo "checked='Checked'" ?> />
					<?php echo L10n::$post ?>
				</label>
				<label><input type="checkbox" 
					name="configRightsEdit<?php echo $group->id ?>" 
					id="configRightsEdit<?php echo $group->id ?>" 
					<?php if ($group->getRights($entry)->may_edit) echo "checked='Checked'" ?> />
					<?php echo L10n::$edit ?>
				</label>
				<label><input type="checkbox" 
					name="configRightsDelete<?php echo $group->id ?>" 
					id="configRightsDelete<?php echo $group->id ?>" 
					<?php if ($group->getRights($entry)->may_delete) echo "checked='Checked'" ?> />
					<?php echo L10n::$delete ?>
				</label><br />
			<?php } ?>
				<input type="hidden" name="configRightsEntryId" id='<?php echo $id ?>' />
			</form>
		</div>
	</fuji:content>
</fuji:item>