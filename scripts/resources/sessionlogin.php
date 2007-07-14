<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class SessionLogin extends Resource {
	
	function httpGet()
	{
		$this->template->applyView('session_login');
	}
	
	function httpPost()
	{
		setcookie('username', $_POST['username'], 0, $this->template->getForumRoot());
		setcookie('userkey', UserManager::createUserKey($_POST['username'], $_POST['password']), 0, $this->template->getForumRoot());
		
		$this->template->redirecturl = $this->template->getAbsoluteForumRoot();
		$this->template->applyView('session_loggedin');
		
		//header('Location: '.$this->template->getAbsoluteForumRoot());
		//header('HTTP/1.1 302 Found');
	}
	
}

?>