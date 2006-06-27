<?php echo $template->getPrefix() ?>
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
			<form id="configRightsF" name="configRightsF" class="createEditF" method="POST" action="<?php echo $entry->getURI() ?>/rights">
				<table>
				<?php foreach($groups as $group) { ?>
					<tr>
						<td><?php echo $group->name ?></td>
						<td>
							<label><input type="checkbox" 
									name="configRightsRead<?php echo $group->id ?>" 
									id="configRightsRead<?php echo $group->id ?>" 
									<?php if ($group->getRights($entry)->may_read) echo "checked='Checked'" ?> />
									<?php echo L10n::$read ?>
							</label>
						</td>
						<td>
							<label><input type="checkbox" 
								name="configRightsPost<?php echo $group->id ?>" 
								id="configRightsPost<?php echo $group->id ?>" 
								<?php if ($group->getRights($entry)->may_post) echo "checked='Checked'" ?> />
								<?php echo L10n::$post ?>
							</label>
						</td>
						<td>
							<label><input type="checkbox" 
								name="configRightsEdit<?php echo $group->id ?>" 
								id="configRightsEdit<?php echo $group->id ?>" 
								<?php if ($group->getRights($entry)->may_edit) echo "checked='Checked'" ?> />
								<?php echo L10n::$edit ?>
							</label>
						</td>
						<td>
							<label><input type="checkbox" 
								name="configRightsDelete<?php echo $group->id ?>" 
								id="configRightsDelete<?php echo $group->id ?>" 
								<?php if ($group->getRights($entry)->may_delete) echo "checked='Checked'" ?> />
								<?php echo L10n::$delete ?>
							</label>
						</td>
					</tr>
				<?php } ?>
				</table>
				<input type="hidden" name="configRightsEntryId" id='<?php echo $id ?>' />
			</form>
		</div>
	</fuji:content>
</fuji:item>