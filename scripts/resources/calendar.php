<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Calendar extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$this->template->today = time();
		$uriparts = $this->request->getRequestURIParts();
		if (isset($uriparts[1]))
			$this->template->date = strtotime($uriparts[1].'-01');
		else
			$this->template->date = strtotime(date('Y-m-01', time()));
		$this->template->applyView('calendar');
	}
	
}

?>