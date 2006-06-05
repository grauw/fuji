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
		if($_POST['pass1'] && $_POST['pass1'] == $_POST['pass2'] && sha1($_POST['pass1']) != $user->password)
			$user->password = sha1($_POST['pass1']);
		if((!isset($user->language) && $_POST['language']) || ($_POST['language'] != $user->language))
			$user->language = $_POST['language'];
		if((!isset($user->style) && $_POST['style']) || ($_POST['style'] != $user->style))
			$user->style = $_POST['style'];
		if($_POST['email'] && $_POST['email'] != $user->email)
			$user->email = $_POST['email'];
		if($_POST['showEmail'] && $_POST['showEmail'] != $user->email_visible)
			$user->email_visible = $_POST['showEmail'];							
		if($_POST['photo'] && $_POST['photo'] != $user->picture)
			$user->picture = $_POST['photo'];
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: POST', true, '405');
	}
	
}	
	
?>