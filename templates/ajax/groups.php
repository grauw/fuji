<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2 class="windowTitle"><?php echo L10n::$groups; ?></h2>
			<ul id="groupActions" class="windowActions menu">
				<li><a href="javascript:Fuji.newGroup()"><?php echo L10n::$new_group ?></a></li>
			</ul>
			<div id="threadsTableDiv" class="content">
				<table id="threadsTable">
					<thead>
						<tr>
							<th><?php echo L10n::$name ?></th>
							<th><?php echo L10n::$rights ?></th>
							<th><?php echo L10n::$num_users ?></th>
							<th><?php echo L10n::$actions ?></th>
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
							<td><a href="../#users/<?php echo $group->id ?>"><?php echo htmlentities($group->name, ENT_NOQUOTES) ?></a></td>
							<td><?php echo $grouprights->toString() ?></td>
							<td><?php echo $group->getNumMembers() ?></td>
							<td><a href="javascript:Fuji.editGroup('<?php echo $group->id ?>',<?php echo $grouprights->may_read ?>,<?php echo $grouprights->may_post ?>,<?php echo $grouprights->may_edit ?>,<?php echo $grouprights->may_delete ?>)"><?php echo L10n::$edit ?></a>, <a href="javascript:Fuji.newDeleteGroupDialog('<?php echo $group->id ?>')"><?php echo L10n::$delete ?></a></td>
						</tr>
						<?php } ?>
					<?php } else echo L10n::$error_no_groups ?>
					</tbody>
				</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>
