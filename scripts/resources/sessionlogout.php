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
		setcookie('username', '', 0, $this->template->getForumRoot());
		setcookie('userkey', '', 0, $this->template->getForumRoot());
		
		$this->template->redirecturl = $this->template->getAbsoluteForumRoot();
		$this->template->applyView('session_loggedout');
		
		//header('Location: '.$this->template->getAbsoluteForumRoot());
		//header('HTTP/1.1 302 Found');
	}
	
}

?>