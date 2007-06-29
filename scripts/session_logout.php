<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Logout extends Resource {
	
	function httpGet()
	{
		$this->httpPost();
	}
	
	function httpPost()
	{
		$template = Template::getTemplate();
		
		setcookie('username', '', 0, $template->getForumRoot());
		setcookie('userkey', '', 0, $template->getForumRoot());
		
		$template->redirecturl = $template->getAbsoluteForumRoot();
		$template->applyView('session_loggedout');
		
		//header('Location: '.$template->getAbsoluteForumRoot());
		//header('HTTP/1.1 302 Found');
	}
	
}	
	
new Logout();
	
?>