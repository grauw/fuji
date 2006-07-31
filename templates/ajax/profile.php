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
<?xml version="1.0" encoding="UTF-8"?>
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
							<?php
								if (!$_GET['edit']) {
								/* If editing */
									$groups = $user->getGroups();
									foreach($groups as $group) echo htmlentities($group->name, ENT_NOQUOTES) . '<br />';
								} else if (UserManager::isAdmin()) {
								/* If not editing */
							?>
							<form name="updateGroup" action="<?php echo $user->getURI() ?>" method="post">
								<?php
									$groups = Database::getArray("SELECT name FROM " . DbVar::$db_tprefix . "groups");
									foreach ($groups as $group) {
								?>
									<label><input type="checkbox" name="<?php echo $group['name'] ?>"<?php if($user->IsInGroup($group['name'])) { ?> checked="checked"<?php } ?> />
											<?php echo $group['name']?></label><br />
								<?php } ?>
								<input type="hidden" name="updated" value="true" />
							</form>
							<?php } ?>
						</td>
					</tr>
				</table>
				<?php if (UserManager::isAdmin() && !$_GET['edit']) {?>
				<form action="#<?php echo $user->getURI() ?>/edit" method="post">
					<button type="submit"><?php echo $this->l10n->edit ?></button>
				</form>
				<?php } else if (UserManager::isAdmin()) { ?>
				<button type="submit" onClick="document.updateGroup.submit()"><?php echo $this->l10n->edit ?></button>
				<?php } ?>
			</div>
		</div>
	</fuji:content>
</fuji:item>