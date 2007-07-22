<?php
require_once 'scripts/dataclass.php';
require_once 'scripts/database.php';
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/rights.php';
require_once 'scripts/utils/functions.php';

class Group extends Dataclass {
	
	static function getTableName() {
		return 'groups';
	}
	static function getPrimaryKeyName() {
		return 'id';
	}
	static function getResourceUriFormat() {
		return 'groups/%s';
	}
	
	/**
	 * Get group by id
	 * @param {Int} $id the group to be returned
	 * @returns Group object
	 */
	static function getGroup($id) {
		return new Group(array('id'=>$id));			
	}
	
	/**
	 * Checks if a group exists
	 * @param {String} $name the name of the group you want to check
	 * @returns True if group exists, false otherwise
	 */
	static function isGroupName($name){
		if(count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups WHERE name = '". $name ."'")) == 0) return false;
		else return true;
	}
	
	/**
	 * Checks if a group exists
	 * @param Number $id the id of the group you want to check
	 * @returns True if group exists, false otherwise
	 */
	static function isGroupId($name){
		if (count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groups WHERE id = '". $id ."'")) == 0) return false;
		else return true;
	}
	
	/**
	 * Get all group names
	 * @returns String array with group names
	 */
	static function getGroupNames()
	{
		$data = Database::getArray("SELECT name FROM " . Database::$db_tprefix . "groups");
		$return = Array();
		foreach ($data as $row)
			array_push($return, $row['name']);
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
		Database::execute("DELETE FROM " . Database::$db_tprefix . "groupmembers WHERE groupid = '$this->id' AND userid = '$user->id'");
	}
	
	/**
	 * Checks if a user is member of this group
	 * @param {User} $user the user to check
	 * @returns {Boolean} true if the user is a member
	 */
	function isMember($user) {
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "groupmembers WHERE groupid = '$this->id' AND userid = '$user->id'");
		return count($data) > 0;
	}
	
	/**
	 * Gets an array with all users in the group
	 */
	function getUsers() {
		$userids = Database::getArray("SELECT userid FROM " . Database::$db_tprefix . "groupmembers WHERE groupid=" . $this->id);
		$users = array();
		foreach ($userids as $userid) {
			$user = User::getUser($userid['userid']);
			if ($user)
				$users[] = $user;
		}
		return $users;
	}
	
	/**
	 * Get group rights
	 * @param {Entry} $entry the entry from which you want the rights
	 * @returns hash/assoc. array with rights this group has on this entry
	 */
	function getRights($entry) {
	    return Rights::getRights($entry, $this, true);
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
	static function getAllGroups()
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
