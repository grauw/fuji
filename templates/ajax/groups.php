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
		<div id="userTableDiv">
			<table id="threadsTable">
				<thead>
					<tr>
						<td><?php echo L10n::$name ?></td>
						<td><?php echo L10n::$rights ?></td>
						<td><?php echo L10n::$num_members ?></td>
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
						<td><?php echo $group->name ?></td>
						<td><?php echo $grouprights->toString() ?></td>
						<td><?php echo $group->getNumMembers() ?></td>
					</tr>
					<?php } ?>
				<?php } else echo L10n::$error_no_users ?>
				</tbody>
			</table>
		</div>
	</fuji:content>
</fuji:item>