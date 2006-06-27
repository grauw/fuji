<?php

require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'httprequest.php';

class Profile extends HttpRequest {

	function httpGet()
	{
		$template = Template::getTemplate('AJAX');
		$template->applyProfile($template);
	}
	
}
	
new Profile();

?>