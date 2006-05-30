<?php
require_once "dataclass.php";
require_once "database.php";
require_once "group.php";
require_once "entry.php";
require_once "rights.php";

class User extends Dataclass {
	
	static function getTable() {
		return 'users';
	}
	
	/**
	 * Get user by id
	 * @param {Int} $id the user to be returned
	 * @returns User object
	 */
	static function getUser($id)
	{		
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "users WHERE id = '$id'");
		$userdata = $data[0];
		if($userdata)
			return new User($userdata);
	}
	
	/**
	 * Add a user to the database
	 * @param {array} $data hash/assoc. array. Key = column name in database. Value = value.
	 * @returns User object
	 */
	static function addUser($data) {
		if(!User::isUser($data['nickname']))
		{
			if(count($data) > 0)
			{
				
				
				if($data['password']) $data['password'] = sha1($data['password']);
				
				$keys = implode(',', array_keys($data));
				$values = implode('\',\'',$data);
				Database::execute("INSERT INTO " . Database::$db_tprefix . "users ($keys) VALUES ('$values')");
				$data += array('id' => Database::insert_id());
				$id = $data['id'];
				
				$result = Database::getArray("SELECT id FROM " . Database::$db_tprefix . "groups WHERE name = 'users'");
				$user_group_id = $result[0]['id'];
				Database::execute("INSERT INTO " . Database::$db_tprefix . "groupmembers VALUES ('$user_group_id', '$id')");
				
				return new User($data);
			}
		}
		else return false;
	}
	
	/**
	 * Checks if a user exists
	 * @param {String} $nickname the nickname from the user you want to check
	 * @returns True if user exists, false otherwise
	 */
	static function isUser($nickname){
		if(count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "users WHERE nickname = '". $nickname ."'")) == 0) return false;
		else return true;
	}
	
	/**
	 * Get user by name
	 * @param {String} $nickname the nickname from the user to be returned
	 * @returns User object
	 */
	static function getUserByName($nickname)
	{
		$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "users WHERE nickname = '$nickname'");
		$userdata = $data[0];
		if($userdata)
			return new User($userdata);				
	}
	
	/**
	 * Check if the user used the right password
	 * @param {String} $password the password the user used
	 * @returns true if password is right, false otherwise
	 */
	function checkPassword($password)
	{
		return sha1($password) == $this->password;
	}
	
	/**
	 * Change the users password
	 * @param {String} $password the new password
	 */
	function setPassword($password)
	{
		$this->password = sha1($password);
	}
	
	/**
	 * Check in which groups the user is
	 * @returns array with Groups the user is in
	 */
	function getGroups()
	{
		$groups = array();
		$groupsdata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "groupmembers WHERE userid = $this->id");
		foreach($groupsdata as $groupdata)
		{
			array_push($groups, Group::getGroup($groupdata['groupid']));
		}
		return $groups;
	}
	
	/**
	 * Get the rights this user has at a specified entry
	 * @param {Entry} $enty the entry to be checked
	 * @returns Rights about the entry/user
	 */
	function getRights($entry)
	{
		$groups = $this->getGroups();
		$userrights = array();
		if($entry->userref == $this->id)
			$userrights += Rights::$owner_rights;
		foreach($groups as $group)
		{
			$rights = $group->getRights($entry);
			$rightsdata = $rights->getData();
			$userrights = array_merge_recursive($userrights, $rightsdata);
		}
		$userrights = array_map(create_function('$value', 'if(is_array($value)) return max($value); else return $value;'), $userrights);
		unset($userrights['groupid']);
		$userrights += array('userid' => $this->id);
		return new Rights($userrights);
	}
	
	/**
	 * Removes a user from the users and groupmembers databases
	 */
	function remove()
	{
		Database::execute("DELETE FROM " . Database::$db_tprefix . "groupmembers WHERE userid = '$this->id'");
		Database::execute("DELETE FROM " . Database::$db_tprefix . "users WHERE id = '$this->id'");
	}
}
?>
