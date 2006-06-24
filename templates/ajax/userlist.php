<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<h2 class="windowTitle"><?php echo L10n::$users; ?></h2>
		<div id="threadsTableDiv">
			<table id="threadsTable">
				<thead>
					<tr>
						<td><?php echo L10n::$name ?></td>
						<td><?php echo L10n::$email_address ?></td>
						<td><?php echo L10n::$group ?></td>
						<td><?php echo L10n::$posts ?></td>
					</tr>
				</thead>
				<tbody>
				<?php 	require_once 'user.php';
						if(isset($template->groupid)) $userids = Database::getArray("SELECT userid AS id FROM " . Database::$db_tprefix . "groupmembers WHERE groupid=".$template->groupid);
						else $userids = Database::getArray("SELECT id FROM " . Database::$db_tprefix . "users");
						if(count($userids) > 0){
						foreach($userids as $userid) { $user = User::getUser($userid['id']);?>
					<tr userid=<?php echo "'$user->id'" ?> class="tableUser">
						<td><a href="#../profile/<?php echo $user->id ?>"><?php echo htmlentities($user->nickname, ENT_NOQUOTES) ?></a></td>
						<td><?php if($user->email_visible) echo htmlentities($user->email, ENT_NOQUOTES) ?></td>
						<td><?php $groups = $user->getGroups(); foreach($groups as $group) $groupstring .= htmlentities($group->name, ENT_NOQUOTES) . ','; echo rtrim($groupstring, ','); unset($groupstring);?></td>
						<td><?php echo $user->getPosts() ?></td>
					</tr>
					<?php } ?>
				<?php } else echo L10n::$error_no_users ?>
				</tbody>
			</table>
		</div>
	</fuji:content>
</fuji:item>