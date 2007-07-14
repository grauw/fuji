<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class SessionLogout extends Resource {
	
	function httpGet()
	{
		$this->httpPost();
	}
	
	function httpPost()
	{
		setcookie('username', '', 0, HttpRequest::$request->getContextPath().'/');
		setcookie('userkey', '', 0, HttpRequest::$request->getContextPath().'/');
		
		$this->template->redirecturl = HttpRequest::$request->getAbsoluteContextPath();
		$this->template->applyView('session_loggedout');
		
		//header('Location: '.HttpRequest::$request->getAbsoluteContextPath());
		//header('HTTP/1.1 302 Found');
	}
	
}

?>