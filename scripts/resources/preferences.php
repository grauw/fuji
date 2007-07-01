<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/resource.php';

class Preferences extends Resource {
	
	function httpPut()
	{
		UserManager::authenticate();
		$user = UserManager::getUser();
		if($user->nickname != 'anonymous')
		{
			if(isset($_POST['pass1']) && $_POST['pass1'] != '' && $_POST['pass1'] == $_POST['pass2'])
				$user->setPassword($_POST['pass1']);
			if(isset($_POST['language']))
				$user->language = $_POST['language'] == 'NULL' ? FALSE : $_POST['language'];
			if(isset($_POST['style']))
				$user->style = $_POST['style'] == 'NULL' ? FALSE : $_POST['style'];
			if(isset($_POST['email']) && $_POST['email'] != '')
				$user->email = $_POST['email'];
			if(isset($_POST['showEmail']))
				$user->email_visible = ($_POST['showEmail'] == 'on' ? '1' : '0');
			else
				$user->email_visible = '0';
			if(isset($_POST['visualEffects']))
				$user->visual_effects = ($_POST['visualEffects'] == 'on' ? '1' : '0');
			else
				$user->visual_effects = '0';
			if(isset($_POST['picture']) && $_POST['picture'] != '')
				$user->picture = $_POST['picture'];
			$user->save();
		}
	}
	
}

?>