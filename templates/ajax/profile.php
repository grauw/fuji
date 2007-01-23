<?php	//TODO: Really needs some more cleanup/fixes (group memberships administration shouldn't be here)
	require_once "../scripts/usermanager.php";
	require_once "../scripts/group.php";
	
	$user = isset($_GET['id']) ? User::getUser($_GET['id']) : UserManager::getUser();
	
	if($_POST['updated']){
		$groups = Database::getArray("SELECT name FROM " . DbVar::$db_tprefix . "groups");
		foreach($groups as $group) {
			$name = $group['name'];
			if($_POST[$name]) Group::getGroupByName($name)->addUser($user);
			else Group::getGroupByName($name)->removeUser($user);
		}
	}


?>
<?php echo $this->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="threadsDiv">
	<fuji:content>	
		<div class="box">
			<h2><?php echo $this->l10n->profile ?></h2>
			<div class="content">
				<table>
					<tr>
						<td rowspan="4"><?php if(isset($user->picture)) { ?><img width="60" src="<?php echo htmlentities($user->picture) ?>" alt="picture"></img><?php } ?></td>
						<td><?php echo $this->l10n->name ?>:</td><td><?php echo htmlentities($user->nickname, ENT_NOQUOTES) ?></td>
					</tr>
					<?php if(isset($user->email) && $user->email_visible) { ?>
					<tr>
						<td><?php echo $this->l10n->email ?>:</td><td><?php echo htmlentities($user->email, ENT_NOQUOTES); ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $this->l10n->posts ?>:</td><td><?php echo $user->getPosts() ?></td>
					</tr>
					<tr>
						<td><?php echo $this->l10n->member_of ?>:</td>
						<td>
							<ul>
							<?php
							$groups = $user->getGroups();
							foreach($groups as $group) { ?>
								<li><?php echo htmlentities($group->name, ENT_NOQUOTES) ?></li>
							<?php } ?>
							</ul>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</fuji:content>
</fuji:item>