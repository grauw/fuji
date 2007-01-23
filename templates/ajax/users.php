<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>
		<div class="box">
			<h2><?php echo $this->l10n->users; ?></h2>
			<ul class="menu">
			<?php if(UserManager::isAdmin()) { ?>
				<li><a href="javascript:Fuji.Dialogs.newCreateUserDialog()"><?php echo $this->l10n->new ?></a></li>
			<?php } ?>
			</ul>
			<div class="content">
				<table>
					<thead>
						<tr>
							<th><?php echo $this->l10n->name ?></th>
							<th><?php echo $this->l10n->email_address ?></th>
							<th><?php echo $this->l10n->group ?></th>
							<th><?php echo $this->l10n->posts ?></th>
							<th><?php echo $this->l10n->actions ?></th>
						</tr>
					</thead>
					<tbody>
					<?php 	require_once 'user.php';
							if(isset($this->groupid)) $userids = Database::getArray("SELECT userid AS id FROM " . Database::$db_tprefix . "groupmembers WHERE groupid=".$this->groupid);
							else $userids = Database::getArray("SELECT id FROM " . Database::$db_tprefix . "users");
							if(count($userids) > 0){
							foreach($userids as $userid) { $user = User::getUser($userid['id']);?>
						<tr class="tableUser">
							<td><a href="#<?php echo $user->getURI() ?>"><?php echo htmlentities($user->nickname, ENT_NOQUOTES) ?></a></td>
							<td><?php if($user->email_visible) { ?><a href="mailto:<?php echo htmlentities($user->email, ENT_NOQUOTES) ?>"><?php echo htmlentities($user->email, ENT_NOQUOTES) ?></a><?php } ?></td>
							<td><?php $groups = $user->getGroups(); foreach($groups as $group) $groupstring .= htmlentities($group->name, ENT_NOQUOTES) . ','; echo rtrim($groupstring, ','); unset($groupstring);?></td>
							<td><?php echo $user->getPosts() ?></td>
							<td>
							<?php if(UserManager::isAdmin()) { ?>
								<span><a href="javascript:Fuji.Dialogs.newEditUserDialog('users/<?php echo $user->id ?>')"><?php echo $this->l10n->edit ?></a></span>
								<span><a href="javascript:Fuji.Dialogs.newDeleteDialog('users/<?php echo $user->id ?>','users')"><?php echo $this->l10n->delete ?></a></span>
							<?php } ?>
							</td>
						</tr>
						<?php } ?>
					<?php } else echo $this->l10n->error_no_users ?>
					</tbody>
				</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>