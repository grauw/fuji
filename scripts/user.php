<?php
require_once "dataclass.php";
require_once "database.php";
require_once "group.php";
require_once "entry.php";
require_once "rights.php";
require_once "usermanager.php";

class User extends Dataclass {
	
	static function getTableName() {
		return 'users';
	}
	static function getPrimaryKeyName() {
		return 'id';
	}
	static function getResourceUriFormat() {
		return 'profile/%s';
	}
	
	/**
	 * Get user by id
	 * @param {Int} $id the user to be returned
	 * @returns User object
	 */
	static function getUser($id) {
		return new User(array('id' => $id));
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
				if($data['password']) $data['password'] = md5($data['nickname'] . ':' . UserManager::$realm . ':' . $data['password']);
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
	 * Change the users password
	 * @param {String} $password the new password
	 */
	function setPassword($password)
	{
		$this->password = md5($this->nickname . ':' . UserManager::$realm . ':' . $password);
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
	 * Check if the user is in a certain group
	 * @param {String} $group the group to check
	 * @returns array with Groups the user is in
	 */
	function isInGroup($group)
	{
		$groups = $this->getGroups();
		foreach($groups as $usergroup) if($usergroup->name == $group) return true;
		return false;
	}
	
	/**
	 * Check if user is root/admin
	 * @returns true if root/admin, false otherwise
	 */
	function isAdmin()
	{
		$root = Entry::getRoot();
		$rights = $this->getRights($root);
		if($rights->may_read == 1 && $rights->may_post == 1 && $rights->may_edit == 1 && $rights->may_delete == 1)
			return true;
		else return false;
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
		if(count($groups) > 0)
		{
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
		else
			return new Rights(array('entryid' => $entry->id, 'userid' => $this->id, 'may_read' => '0', 'may_post' => '0', 'may_edit' => '0', 'may_delete' => '0'));
	}
	
	/**
	 * Returns the number of posts by this user
	 * @returns int the number of posts
	 */
	function getPosts()
	{
		$data = Database::getArray("SELECT COUNT(*) FROM " . Database::$db_tprefix . "entries WHERE userref = '$this->id'");
		return $data[0][0];
	}
	
	/**
	 * Removes a user from the users and groupmembers databases
	 */
	function remove()
	{
		Database::execute("DELETE FROM " . Database::$db_tprefix . "groupmembers WHERE userid = '$this->id'");
		parent::remove();
	}
}
?>
