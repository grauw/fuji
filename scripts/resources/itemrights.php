<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class ItemRights extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if (UserManager::isAdmin()) {
			$uriparts = $this->request->getRequestURIParts();
			if ($uriparts[0] == 'item') {
				$id = intval($uriparts[1]);
            	if ($entry = Entry::getEntry($uriparts[1])) {
					$this->template->entry = $entry;
					$this->template->applyView('rights');
					header('HTTP/1.1 200 OK');
				} else header('HTTP/1.1 404 Not Found');
			}
		} else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if($entry = $this->getEntry())
		{
    		if(UserManager::isAdmin()) {
        		$groups = Group::getAllGroups();
        		foreach($groups as $group){
        			$groupid = $group->id;
        			$currentRights = Rights::getRights($entry, $group, true);
        			$newRead = $_POST['mayRead' . $groupid] == 'on' ? 1 : 0;
        			$newPost = $_POST['mayPost' . $groupid] == 'on' ? 1 : 0;
        			$newEdit = $_POST['mayEdit' . $groupid] == 'on' ? 1 : 0;
        			$newDelete = $_POST['mayDelete' . $groupid] == 'on' ? 1 : 0;
        			if($_POST['applyRecursive'] == 'on')
        			{
        			    Rights::deleteChildrenRights($entry, $group);
        			}
        			if($currentRights->may_read != $newRead || 
        			   $currentRights->may_post != $newPost ||
        			   $currentRights->may_edit != $newEdit ||
        			   $currentRights->may_delete != $newDelete)
        			{
       			        if($currentRights->entryid == $entry->id && Rights::hasRights($entry, $group))
       			        {
       			            if($newRead == 0 && $newPost == 0 && $newEdit == 0 && $newDelete == 0)
       			                $currentRights->remove();
       			            else
       			            {
           			            $currentRights->may_read = $newRead;
           			            $currentRights->may_post = $newPost;
           			            $currentRights->may_edit = $newEdit;
           			            $currentRights->may_delete = $newDelete;
           			            $currentRights->save();
           			        }
       			        }
       			        else
       			        {
       			            $rights = new Rights();
       			            $rights->entryid = $entry->id;
       			            $rights->groupid = $groupid;
       			            $rights->may_read = $newRead;
       			            $rights->may_post = $newPost;
       			            $rights->may_edit = $newEdit;
       			            $rights->may_delete = $newDelete;
       			            $rights->save();
        			    }
        			}
        		}
        	}
        }
	}
	
	function getEntry() {
		$uriparts = $this->request->getRequestURIParts();
		if (!isset($uriparts[1])) return null;
		return Entry::getEntry(intval($uriparts[1]));
	}
}

?>
