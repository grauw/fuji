<?php
require_once 'usermanager.php';

new Preferences();
	
class Preferences {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST') {
			$this->httpPost();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		$user = UserManager::getUser();
		if($user->nickname != 'anonymous')
		{
			if(isset($_POST['pass1']) && $_POST['pass1'] != '' && $_POST['pass1'] == $_POST['pass2'] && sha1($_POST['pass1']) != $user->password)
				$user->setPassword($_POST['pass1']);
			if($_POST['language'] != $user->language)
				$user->language = $_POST['language'];
			if($_POST['style'] != $user->style)
				$user->style = $_POST['style'];
			if(isset($_POST['email']) && $_POST['email'] != '' && $_POST['email'] != $user->email)
				$user->email = $_POST['email'];
			if(isset($_POST['showEmail']))
				$user->email_visible = ($_POST['showEmail'] == 'on' ? '1' : '0');
			else
				$user->email_visible = '0';	
			if(isset($_POST['visualEffects']))
				$user->visual_effects = ($_POST['visualEffects'] == 'on' ? '1' : '0');
			else
				$user->visual_effects = '0';							
			//if(isset($_POST['picture']) && $_POST['picture'] != '' && $_POST['picture'] != $user->picture)
			if((isset($_POST['picture']) && (!isset($_POST['picture']) || $_POST['picture'] != $user->picture)) || (!isset($_POST['picture']) && isset($_POST['picture'])))
				$user->picture = $_POST['picture'];
		}
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: POST', true, '405');
	}
	
}	
	
?>