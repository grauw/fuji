<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/dataclasses/user.php';
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/utils/functions.php';
require_once 'scripts/resource.php';

class Groups extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if ($entry->getRights()->may_read) {
			$uriparts = $this->request->getRequestURIParts();
		    if($group = $this->getGroup())
		    {
		        $this->template->group = $group;
		        $this->template->applyView('groups');
		    }
		    else header('HTTP/1.1 404 Not Found');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if (strlen($_POST['name']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['name'])) {
			if (!Group::isGroupName($_POST['name'])) {
				if (UserManager::isAdmin()) {
					$newgroupdata = array (
							'name' => $_POST['name'],
							);
                    $group = new Group($newgroupdata);
                    $group->save();
    	            $memberids = split(',', $_POST['members']);
    	            foreach($memberids as $memberid)
    	                if($user = User::getUser(intval($memberid)) && !Group::isMember($user))
    	                    $group->addUser($user);
					header('Location: '.$newgroup->getAbsoluteURI());
					header('HTTP/1.1 201 Created');
				} else header('HTTP/1.1 401 Unauthorized');
			}
		} else header('HTTP/1.1 404 Not Found'); //incorrect error
	}
	
	function httpPut()
	{
		UserManager::authenticate();
		$uriparts = $this->request->getRequestURIParts();
	    if($group = $this->getGroup())
	    {
	        if($group->isMember(User::getUser()))
	        {
	            if($_POST['name'] != $group->name && strlen($_POST['name']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['name']))
	            {
	                $group->name = $_POST['name'];
	                $group->save();
	            }
	            $memberids = split(',', $_POST['members']);
	            foreach($group->getUsers() as $user)
	                if(!in_array($user->id, $memberids))
	                    $group->removeUser($user);
	            foreach($memberids as $memberid)
	                if($user = User::getUser(intval($memberid)) && !Group::isMember($user))
	                    $group->addUser($user);
    	    }
    		else header('HTTP/1.1 401 Unauthorized');
	    }
	    else header('HTTP/1.1 404 Not Found');
	}
	
	function httpDelete()
	{
		UserManager::authenticate();
		$uriparts = $this->request->getRequestURIParts();
		if ($uriparts[1]) {
			Group::getGroup($uriparts[1])->delete();
		}
	}
	
	function getGroup() {
		$uriparts = $this->request->getRequestURIParts();
		if (!isset($uriparts[1])) return null;
		return Group::getGroup(intval($uriparts[1]));
	}
}

?>
