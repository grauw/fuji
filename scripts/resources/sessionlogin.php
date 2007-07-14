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
		setcookie('username', $_POST['username'], 0, HttpRequest::$request->getContextPath().'/');
		setcookie('userkey', UserManager::createUserKey($_POST['username'], $_POST['password']), 0, HttpRequest::$request->getContextPath().'/');
		
		$this->template->redirecturl = HttpRequest::$request->getAbsoluteContextPath();
		$this->template->applyView('session_loggedin');
		
		//header('Location: '.HttpRequest::$request->getAbsoluteContextPath());
		//header('HTTP/1.1 302 Found');
	}
	
}

?>