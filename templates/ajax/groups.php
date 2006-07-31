<?xml version="1.0" encoding="UTF-8"?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2><?php echo $this->l10n->groups; ?></h2>
			<ul class="menu">
				<li><a href="javascript:Fuji.Dialogs.newDialog('newGroupDialog')"><?php echo $this->l10n->new_group ?></a></li>
			</ul>
			<div class="content">
				<table>
					<thead>
						<tr>
							<th><?php echo $this->l10n->name ?></th>
							<th><?php echo $this->l10n->rights ?></th>
							<th><?php echo $this->l10n->num_users ?></th>
							<th><?php echo $this->l10n->actions ?></th>
						</tr>
					</thead>
					<tbody>
					<?php 	require_once 'group.php';
							require_once 'entry.php';
							$groupnames = Group::getGroupNames();
							$groups = Array();
							foreach($groupnames as $groupname) array_push($groups, Group::getGroupByName($groupname));
							if(count($groups)>0){
							foreach($groups as $group) { $grouprights = $group->getRights(Entry::getRoot());?>
						<tr>
							<td><a href="#<?php echo $group->getURI() ?>/users"><?php echo htmlentities($group->name, ENT_NOQUOTES) ?></a></td>
							<td><?php echo $grouprights->toString() ?></td>
							<td><?php echo $group->getNumMembers() ?></td>
							<td><a href="javascript:Fuji.Dialogs.editGroup('<?php echo $group->getURI() ?>',<?php echo $grouprights->may_read ?>,<?php echo $grouprights->may_post ?>,<?php echo $grouprights->may_edit ?>,<?php echo $grouprights->may_delete ?>)"><?php echo $this->l10n->edit ?></a>,
								<a href="javascript:Fuji.Dialogs.newDeleteGroupDialog('<?php echo $group->getURI() ?>')"><?php echo $this->l10n->delete ?></a></td>
						</tr>
						<?php } ?>
					<?php } else echo $this->l10n->error_no_groups ?>
					</tbody>
				</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>
