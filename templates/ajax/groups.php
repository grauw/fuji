<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2><?php echo $this->l10n->groups; ?></h2>
			<ul class="menu">
			<?php if(UserManager::isAdmin()) { ?>
				<li><a href="javascript:Fuji.Dialogs.newCreateGroupDialog()"><?php echo $this->l10n->new ?></a></li>
			<?php } ?>
			</ul>
			<div class="content">
				<table>
					<thead>
						<tr>
							<th><?php echo $this->l10n->name ?></th>
							<th><?php echo $this->l10n->users ?></th>
							<th><?php echo $this->l10n->actions ?></th>
						</tr>
					</thead>
					<tbody>
					<?php 	$groupnames = Group::getGroupNames();
							$groups = Array();
							foreach($groupnames as $groupname) array_push($groups, Group::getGroupByName($groupname));
							if(count($groups)>0){
							foreach($groups as $group) { $grouprights = $group->getRights(Entry::getRoot());?>
						<tr>
							<td><a href="#<?php echo $group->getURI() ?>/users"><?php echo htmlentities($group->name, ENT_NOQUOTES) ?></a></td>
							<td><?php echo $group->getNumMembers() ?></td>
							<td><a href="javascript:Fuji.Dialogs.newEditGroupDialog('<?php echo $group->getURI() ?>')"><?php echo $this->l10n->edit ?></a>
								<a href="javascript:Fuji.Dialogs.newDeleteDialog('<?php echo $group->getURI() ?>','groups')"><?php echo $this->l10n->delete ?></a></td>
						</tr>
						<?php } ?>
					<?php } else echo $this->l10n->error_no_groups ?>
					</tbody>
				</table>
			</div>
			<div id="groupDialog" style="display: none">
				<div id="groupDialogHead" class="hd"><?php echo $this->l10n->group ?></div>
				<div id="groupDialogBody" class="bd">
					<form id="groupDialogForm" name="groupDialogForm" class="groupDialogForm" method="POST" action="groups">
						<fieldset>
							<legend><?php echo $this->l10n->group ?></legend>
							<p><?php echo $this->l10n->name ?><br />
								<input id='groupName' name='name' type='text' value='' />
							</p>
						</fieldset>
						<fieldset>
							<legend><?php echo $this->l10n->members ?></legend>
							<ul id="groupDialogUsers">
							</ul>
							<ul id="groupDialogMembers">
							</ul>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</fuji:content>
</fuji:item>
