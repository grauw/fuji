<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$groups; ?></h2>
		<div id="groupActions" class="windowActions">
			<a href="javascript:Fuji.newGroup()"><?php echo L10n::$new_group ?></a>
		</div>
		<div id="threadsTableDiv">
			<table id="threadsTable">
				<thead>
					<tr>
						<td><?php echo L10n::$name ?></td>
						<td><?php echo L10n::$rights ?></td>
						<td><?php echo L10n::$num_users ?></td>
						<td><?php echo L10n::$actions ?></td>
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
					<tr id="<?php echo $group->id ?>" name="<?php echo $group->id ?>">
						<td><a href="../#users/<?php echo $group->id ?>"><?php echo $group->name ?></a></td>
						<td name="rights"><?php echo $grouprights->toString() ?></td>
						<td><?php echo $group->getNumMembers() ?></td>
						<td><a href="javascript:Fuji.editGroup('<?php echo $group->id ?>',<?php echo $grouprights->may_read ?>,<?php echo $grouprights->may_post ?>,<?php echo $grouprights->may_edit ?>,<?php echo $grouprights->may_delete ?>)"><?php echo L10n::$edit ?></a></td>
					</tr>
					<?php } ?>
				<?php } else echo L10n::$error_no_groups ?>
				</tbody>
			</table>
		</div>
	</fuji:content>
</fuji:item>
