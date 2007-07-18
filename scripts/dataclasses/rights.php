<?php
require_once 'scripts/dataclass.php';
require_once 'scripts/database.php';

class Rights extends Dataclass {

	static $owner_rights = array('may_read' => 1, 'may_post' => 1, 'may_edit' => 1, 'may_delete' => 1);
	static $no_rights = array('may_read' => 0, 'may_post' => 0, 'may_edit' => 0, 'may_delete' => 0);
	
	static function getTableName() {
		return 'grouprights';
	}
	static function getPrimaryKeyName() {
		// XXX: there is no single key!
		return 'entryid';
	}
	static function getResourceUriFormat() {
		return 'group/%s/rights';
	}
	
	/**
	* Get group rights
	* @param {Entry} $entry the entry you want the rights of or all if null
	* @param {Group} $group the group you want the rights of or all if null
	* @param Boolean $recursive checks the rights recursively when true (all the way to the root)
	* @returns hash/assoc. array with rights this group has on this entry
	*/
	static function getRights($entry, $group, $recursive = false) {
		$data;
		if(isset($entry) || isset($group)) {
			if(isset($entry) && isset($group)) {
				$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE entryid = '$entry->id' AND groupid = '$group->id'");
			} else  {
				if(isset($entryid)) {
					$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE entryid = '$entry->id'");
				} else {
					$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE groupid = '$group->id'");
				}
			}
		} else {
			$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights");
		}
		//TODO: should return array of rights
		$rightsdata = $data[0];
		if($rightsdata)
			return new Rights($rightsdata);
		else
		{
		    $parent = $entry->getParent();
		    if($recursive && $parent)
		        return Rights::getRights($parent, $group, true);
		    else
			    return new Rights(self::$no_rights);
	    }
	}

	/**
	* Get rights for an array of groups and merges them
	* @param {Entry} $entry the entry you want the rights
	* @param {Array} $groups the array of groups you want the rights
	* @param Boolean $recursive checks the rights recursively when true (all the way to the root)
	* @returns hash/assoc. array with rights these groups have on this entry
	*/
	static function getMergedRights($entry, $groups, $recursive = false) {
	    $rights = array();
	    array_push($rights, self::$no_rights);
  	    foreach($groups as $group)
   	    {
   	        array_push($rights, self::getRights($entry, $group, $recursive));
   	    }
		return self::max($rights);
	}

	/**
	* Combines an array of rights (uses the highest values)
	* @param {Array} $rightsset the array of rights you want to combine
	* @returns hash/assoc. array with highest rights
	*/
	static function max($rightsset) {
	    $may_read = 0; $may_post = 0; $may_edit = 0; $may_delete = 0;
	    foreach($rightsset as $rights)
	    {
	        if($rights->may_read > $may_read) $may_read = $rights->may_read;
	        if($rights->may_post > $may_post) $may_post = $rights->may_post;
	        if($rights->may_edit > $may_edit) $may_edit = $rights->may_edit;
	        if($rights->may_delete > $may_delete) $may_delete = $rights->may_delete;
	    }
	    return new Rights(array('may_read' => $may_read, 'may_post' => $may_post, 'may_edit' => $may_edit, 'may_delete' => $may_delete));
	}
	
	function toString(){
		$l10n = new L10nManager();
		$string = '';
		if($this->may_read) $string .= $l10n->read . ', ';
		if($this->may_post) $string .= $l10n->post . ', ';
		if($this->may_edit) $string .= $l10n->edit . ', ';
		if($this->may_delete) $string .= $l10n->delete . ', ';
		$string = rtrim($string, ', ');
		return $string;
	}
}

?>
