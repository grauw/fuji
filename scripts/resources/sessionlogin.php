<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class SessionLogin extends Resource {
	
	function httpGet()
	{
		$template = Template::getTemplate();
		$template->applyView('session_login');
	}
	
	function httpPost()
	{
		$template = Template::getTemplate();
		
		setcookie('username', $_POST['username'], 0, $template->getForumRoot());
		setcookie('userkey', UserManager::createUserKey($_POST['username'], $_POST['password']), 0, $template->getForumRoot());
		
		$template->redirecturl = $template->getAbsoluteForumRoot();
		$template->applyView('session_loggedin');
		
		//header('Location: '.$template->getAbsoluteForumRoot());
		//header('HTTP/1.1 302 Found');
	}
	
}

?>