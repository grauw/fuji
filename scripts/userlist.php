<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'httprequest.php';

class Userlist extends HttpRequest {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$template = Template::getTemplate('AJAX');
			if(isset($_GET['groupid'])) $template->applyUserlist($template, $_GET['groupid']);
			else $template->applyUserlist($template);
		}
		else {
			$template = Template::getTemplate('AJAX');
			$template->failInsufficientRights($template);
		}
	}
	
	function httpPost() {
		$this->httpMethodNotAllowed();
	}
	
	function httpDelete() {
		$this->httpMethodNotAllowed();
	}
	
	function httpPut() {
		$this->httpMethodNotAllowed();
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET', true, '405');
	}
	
}
	
new Userlist();
	
?>