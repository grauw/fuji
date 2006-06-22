<?php
require_once "dataclass.php";
require_once "database.php";
require_once "entry.php";
require_once "rights.php";
require_once "utils/functions.php";

class Group extends Dataclass {
	
	static function getTable() {
		return 'groups';
	}
	
	/**
	 * Get group by id
	 * @param {Int} $id the group to be returned
	 * @returns Group object
	 */
	static function getGroup($id)
	{		
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups WHERE id = '$id'");
		$groupdata = $data[0];
		if($groupdata)
			return new Group($groupdata);			
	}
	
	/**
	 * Add a group to the database
	 * @param {array} $data hash/assoc. array. Key = column name in database. Value = value.
	 * @returns Group object
	 */
	static function addGroup($data) {
		if(count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups WHERE name = '". $data['name'] ."'")) == 0)
		{
			if(count($data) > 0)
			{
				$keys = implode(',', array_keys($data));
				$values = implode('\',\'',$data);
				Database::execute("INSERT INTO " . Database::$db_tprefix . "groups ($keys) VALUES ('$values')");
				$data += array('id' => Database::insert_id());
				$id = $data['id'];
				Database::execute("INSERT INTO " . Database::$db_tprefix . "grouprights VALUES ('1', '$id', '', '', '', '')");
				return new Group($data);
			}
		}
	}
	
	/**
	 * Get all group names
	 * @returns String array with group names
	 */
	static function getGroupNames()
	{		
		$data = Database::getArray("SELECT name FROM " . Database::$db_tprefix . "groups");
		$return = Array();
		foreach($data as $row) array_push($return, $row['name']);
		return $return;			
	}
	
	/**
	 * Add a user to the group
	 * @param {User} $user the user to be added
	 */
	function addUser($user) {
		Database::execute("INSERT INTO groupmembers (groupid, userid) VALUES ('$this->id', '$user->id')");
	}
	
	/**
	 * Remove a user from the group
	 * @param {User} $user the user to be removed
	 */
	function removeUser($user) {
		Database::execute("DELETE FROM groupmembers WHERE groupid = '$this->id' AND userid = '$user->id'");
	}
	
	/**
	 * Change the group rights
	 * @param {array} $data hash/assoc. array with new values. Key = column name. Value = value.
	 */
	//TODO geeft fout bij false als input omdat false == '' , terwijl 0 =='0'
	function updateRights($data) {
		if(array_key_exists('groupid', $data)) unset($data['groupid']);
		//if entryid is given only the values for that entry and group are updated, otherwise for all entries in that group
		if($entry_id = $data['entryid']) unset($data['entryid']);
		$s_update = Functions::implode_with_keys(', ' , $data);
		
		if($entry_id) Database::execute("UPDATE " . Database::$db_tprefix . "grouprights SET $s_update WHERE groupid = '$this->id' AND entryid = '$entry_id'");
		else Database::execute("UPDATE " . Database::$db_tprefix . "grouprights SET $s_update WHERE groupid = '$this->id'");
	}
	
	/**
	 * Get group rights
	 * @param {Entry} $entry the entry from which you want the rights
	 * @returns hash/assoc. array with rights this group has on this entry
	 */
	function getRights($entry) {
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE entryid = '$entry->id' AND groupid = '$this->id'");
		$rightsdata = $data[0];
		if($rightsdata)
			return new Rights($rightsdata);
		elseif($entry->ref)
			return $this->getRights($entry->getParent());
		else
			return new Rights(array());
	}
	
	/**
	 * Get group member count
	 * @returns int value of number of members in group
	 */
	function getNumMembers() {
		$data = Database::getArray("SELECT COUNT(*) AS num_members FROM " . Database::$db_tprefix . "groupmembers WHERE groupid = '$this->id'");
		return $data[0]['num_members'];
	}
	
	/**
	 * Get group by name
	 * @param {String} $name the group to be returned
	 * @returns Group object
	 */
	static function getGroupByName($name)
	{
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups WHERE name = '$name'");
		$groupdata = $data[0];
		return new Group($groupdata);				
	}
	
	/**
	 * Get all groups
	 * @returns Group-array
	 */
	function getAllGroups()
	{
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups");
		$return = Array();
		foreach($data as $row) array_push($return, new Group($row));
		return $return;			
	}
	
	/**
	 * Delete the group
	 */
	function delete()
	{
		Database::execute("DELETE FROM " . Database::$db_tprefix . "groups WHERE id = $this->id");
		Database::execute("DELETE FROM " . Database::$db_tprefix . "grouprights WHERE groupid = $this->id");
		Database::execute("DELETE FROM " . Database::$db_tprefix . "groupmembers WHERE groupid = $this->id");
	}
}
?>
