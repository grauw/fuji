<?php
	require_once "styleparser.php";
	require_once "usermanager.php";
	
	$user;
	
	if(isset($_GET['id']))
	{
		$user = User::getUser($_GET['id']);
	}
	else
	{
		UserManager::authenticate();
		$user = UserManager::getUser();
	}
	
	if(isset($lang))
		require_once("../l10n/$lang.php");
	else
		require_once("../l10n/default.php");	
?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo L10n::$language ?>" xml:lang="<?php echo L10n::$language ?>">
<head>
	<title>Profile</title>
	<?php StyleXML::getStyleXML()->getStyleSheets() ?>
</head>
<body>
</body>
	<div id="profileDiv" class="window">
		<h2 class="windowTitle">Profile</h2>
		<div id="profileInfoDiv">
			<table>
				<tr><td rowspan="4"><?php if(isset($user->picture)) { ?><img width="60" src="<?php echo $user->picture ?>" alt="picture"></img><?php } ?></td><td>Name:</td><td><?php echo $user->nickname ?></td></tr>
				<tr><td><?php if(isset($user->email) && $user->email_visible) { ?>E-Mail:</td><?php } ?><td><?php if(isset($user->email) && $user->email_visible) echo $user->email; ?></td></tr>
				<tr><td>Posts:</td><td><?php echo $user->getPosts() ?></td></tr>
				<tr><td>Member of:</td><td><?php $groups = $user->getGroups(); foreach($groups as $group) echo $group->name . "<br />" ?></td></tr>
			</table>
		</div>
	</div>
</html>

