<?php
	require_once "../scripts/usermanager.php";
	require_once "../scripts/l10nmanager.php";
	require_once "../scripts/group.php";
	
	UserManager::authenticate();	
	$user = null;
	
	if(isset($_GET['id']))
	{
		$user = User::getUser($_GET['id']);
	}
	else
	{
		$user = UserManager::getUser();
	}
	
	L10nManager::loadL10n();
	
	//echo XHTML::pageHeaders();
	
	if($_POST['updated']){
		$groups = Database::getArray("SELECT name FROM " . DbVar::$db_tprefix . "groups");
		foreach($groups as $group){
			$name = $group['name'];
			if($_POST[$name]) Group::getGroupByName($name)->addUser($user);
			else Group::getGroupByName($name)->removeUser($user);
		}
	}


?>
<?php echo $template->getPrefix() ?>
<fuji:item xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo L10n::$language ?>"
	loadcontext="threadsDiv">
	<fuji:content>	
		<h2 class="windowTitle"><?php echo L10n::$profile ?></h2>
		<div id="threadsTableDiv">
			<table id="threadsTable">
				<tr><td rowspan="4"><?php if(isset($user->picture)) { ?><img width="60" src="<?php echo $user->picture ?>" alt="picture"></img><?php } ?></td><td><?php echo L10n::$name ?>:</td><td><?php echo $user->nickname ?></td></tr>
				<?php if(isset($user->email) && $user->email_visible) { ?><tr><td><?php echo L10n::$email ?>:</td><td><?php echo $user->email; ?></td></tr><?php } ?>
				<tr><td><?php echo L10n::$posts ?>:</td><td><?php echo $user->getPosts() ?></td></tr>
				<tr><td><?php echo L10n::$member_of ?>:</td>
				<?php if(!$_GET['edit']){ /*not editting*/?>
				<td><?php $groups = $user->getGroups(); foreach($groups as $group) echo $group->name . "<br />" ?></td></tr>
				<?php }else{ /*editting*/?>
				<td><form name="updateGroup" action="../profile/<?php echo $user->id; ?>" method="post">
				<?php	$groups = Database::getArray("SELECT name FROM " . DbVar::$db_tprefix . "groups");
						foreach($groups as $group){?>
							<label><input type="checkbox" <?php echo 'name="'.$group['name'].'"'; if($user->IsInGroup($group['name'])) echo ' checked="checked"';  ?> />
							<?php echo $group['name']?></label><br />
						<?php } ?>
				<input type="hidden" name="updated" value="true" />
				</form></td></tr>
				<?php } ?>
			</table>
		</div>
		<?php if(UserManager::isAdmin() && !$_GET['edit']){?>
		<form action="../editprofile/<?php echo $user->id; ?>" method="post">
		<input type="submit" value="<?php echo L10n::$edit ?>" />
		</form>
		<?php }else if(UserManager::isAdmin()){ ?>
		<input type="submit" value="<?php echo L10n::$edit ?>" onClick="document.updateGroup.submit()" />
		<?php } ?>
	</fuji:content>
</fuji:item>