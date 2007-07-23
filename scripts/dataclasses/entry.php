<?php
require_once 'scripts/dataclass.php';
require_once 'scripts/database.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/processor/processor.php';

class Entry extends Dataclass {
	
	static public $TYPE_SYSTEM = 1;
	static public $TYPE_FORUMCONTAINER = 2;
	static public $TYPE_FORUM = 3;
	static public $TYPE_POST = 4;
	static public $TYPE_POLL = 11;
	static public $TYPE_VOTE = 12;
	
	static function getTableName() {
		return 'entries';
	}
	static function getPrimaryKeyName() {
		return 'id';
	}
	static function getResourceUriFormat() {
		return 'item/%s';
	}
	
	/**
	 * returns an Entry-object given its id
	 * @param {Int} $id Entry to be returned
	 * @returns Entry
	 */
	static function getEntry($id) {
		return new Entry(array('id' => $id));
	}
	
	/**
	 * Get entry's parent
	 * @returns Parent entry (null if not present?)
	 */
	function getParent() {
		if ($this->ref === NULL) return NULL;
		return self::getEntry($this->ref);
	}
	
	/**
	 * Checks if an entry exists
	 * @param Number $id the id of the entry you want to check
	 * @returns True if entry exists, false otherwise
	 */
	static function isEntryId($id){
		return count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "entries WHERE id = '". $id ."' LIMIT 1")) > 0;
	}
	
	/**
	 * Checks if an entry exists
	 * @param String $title the title of the entry you want to check
	 * @returns True if entry exists, false otherwise
	 */
	static function isEntryTitle($title){
		return count(Database::getArray("SELECT * FROM " . Database::$db_tprefix . "entries WHERE title = '". $title ."' LIMIT 1")) > 0;
	}
	
	static function search($query, $field, $forumid = null, $orderby = null, $limit = null) {
		$childrenids_string = "";
		if(isset($forumid))
		{
			$forum = Entry::getEntry($forumid);
			if(isset($forum))
			{
				$children = $forum->getChildrenRecursive();
				$childrenids = array();
				if(isset($subfora))
				{
					foreach($children as $child)
					{
						array_push($childrenids, "ref=$child->id");
					}
				}
				if(count($childrenids) > 0)
					$childrenids_string = ' AND ' . implode(" OR ", $childrenids);
			}
		}
		$entries = array();
		$entriesdata = Database::getArray("SELECT *, MATCH($field) AGAINST ('$query' IN BOOLEAN MODE) AS score FROM " . DbVar::$db_tprefix . "entries WHERE MATCH($field) AGAINST ('$query' IN BOOLEAN MODE) AND type = '4'" . $childrenids_string . (isset($orderby) ? " ORDER BY $orderby" : "") . (isset($limit) ? " LIMIT $limit" : ""));
		foreach($entriesdata as $entrydata) {
			array_push($entries, new Entry($entrydata));
		}
		return $entries;
	}
	
	/**
	 * Get entries by type
	 * @param Number type The type of entries to get
	 * @param String orderby The column to order the entries by
	 * @param String limit The limit of entries
	 * @returns array of Entries
	 */
	static function getEntriesByType($type, $orderby = null, $limit = null) {
		$entries = array();
		$entriesdata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE type = '$type'" . (isset($orderby) ? " ORDER BY $orderby" : "") . (isset($limit) ? " LIMIT $limit" : ""));
		if ($entriesdata !== FALSE) {
			foreach($entriesdata as $entrydata) {
				array_push($entries, new Entry($entrydata));
			}
		}
		return $entries;
	}	
	
	/**
	 * Get entries by date
	 * @param Date datestart The starting date to get the entries for
	 * @param Date dateend The ending date to get the entries for
	 * @param String limit The limit of entries
	 * @returns array of Entries
	 */
	static function getEntriesByDate($startdate, $enddate, $limit = null) {
		$entries = array();
		$entriesdata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE type = 4 AND created >= '" . addslashes($startdate) . "' AND created < '" . addslashes($enddate) . "'" . (isset($limit) ? " LIMIT $limit" : ""));
		if ($entriesdata !== FALSE) {
			foreach($entriesdata as $entrydata) {
				array_push($entries, new Entry($entrydata));
			}
		}
		return $entries;
	}	
	
	/**
	 * Creates a new entry which is a reply to this entry.
	 * @param {Entry} $entry New entry to add to this entry.
	 * @returns the new Entry
	 * @deprecated
	 */
	function newReply() {
		$entry = new Entry();
		$entry->ref = $this->id;
		return $entry;
	}
	
	/**
	 * Checks if this entry is the root entry
	 * @returns True if this entry is the root entry, False otherwise
	 */
	function isRoot() {
		return !isset($this->ref);
	}
	
	/**
	 * Get the root entry
	 * @returns the root Entry
	 */
	static function getRoot() {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref IS NULL");
		if(count($data) != 1) trigger_error('No or more than one root elements found');
		$entrydata = $data[0];
		if($entrydata)
			return new Entry($entrydata);
	}
	
	/**
	 * Get entry's processed content
	 * @returns content as String
	 */
	function getProcessedContent() {
		$processor = Processor::getProcessor($this->processor);
		return $processor->parse($this->content);
	}
	
	/**
	 * Get entry's content
	 * @returns content as String
	 */
	function getContent() {
		return $this->content;
	}
	
	/**
	 * Update entry's content
	 */
	function updateContent($content) {
		$this->content = $content;
		$this->save();
	}
	
	/**
	 * Get entry's children
	 * @param String orderby The column to order the entries by
	 * @param String limit The limit of children
	 * @returns array of child Entries
	 */
	function getChildren($orderby = null, $limit = null) {
		$children = array();
		$childrendata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref = '$this->id'" . (isset($orderby) ? " ORDER BY $orderby" : "") . (isset($limit) ? " LIMIT $limit" : ""));
		foreach($childrendata as $entrydata)
		{
			array_push($children, new Entry($entrydata));
		}
		return $children;
	}
	
	function getSubfora() {
		$children = array();
		$childrendata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref = '$this->id' AND type < " . self::$TYPE_POST);
		foreach($childrendata as $entrydata)
			array_push($children, new Entry($entrydata));
		if(count($children > 0))
			foreach($children as $child)
				$children = array_merge($children, $child->getSubfora());
		return $children;
	}
	
	function getChildrenRecursive() {
		$children = array();
		$childrendata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref = '$this->id'");
		foreach($childrendata as $entrydata)
			array_push($children, new Entry($entrydata));
		if(count($children > 0))
			foreach($children as $child)
				$children = array_merge($children, $child->getChildrenRecursive());
		return $children;
	}
	
	/**
	 * Removes entry's descendents
	 */
	function removeChildren() {
		$children = $this->getChildren();
		foreach($children as $child) $child->remove();
		$this->save(); // updates modification time
	}
	
	/**
	 * Get the formatted date of this entry
	 * @param string field The field of the date (created/modified)
	 * @param string format The format of the date (see SQL documentation)
	 * @returns string The formatted date
	 */
	function getFormattedDate($field, $format)
	{
		$date = Database::getArray("SELECT DATE_FORMAT($field, '$format') FROM " . DbVar::$db_tprefix . "entries WHERE id = '$this->id'");
		return $date[0][0];
	}
	
	/**
	 * Gets the name of the view 
	 */
	function getView() {
		switch ($this->type) {
			case 1: return 'main';
			case 2: return 'forum';
			case 3: return 'forum';
			case 4: return 'thread';
		}
	}

	/**
	 * Get rights of current user
	 * @returns hash/assoc. array with rights this user has on this entry
	 */
	function getRights() {
	    return $this->getRightsByUser(UserManager::getUser());
	}
	
	/**
	 * Get group rights
	 * @param {Group} $group the group from which you want the rights
	 * @returns hash/assoc. array with rights this group has on this entry
	 */
	function getRightsByGroup($group) {
		return Rights::getRights($this, $group, true);
	}

	/**
	 * Get user rights
	 * @param {User} $user the user from which you want the rights
	 * @returns hash/assoc. array with rights this user has on this entry
	 */
	function getRightsByUser($user) {
		$groups = $user->getGroups();
		$userrights = array();
		if ($this->userref == $user->id) //should check if user has no rights in higher level
		{
		    //special owner rights
		    $rights = new Rights(array_merge(array(entryid => $this->id), Rights::$owner_rights));
		    return $rights;
	    }
		else
		    return Rights::getMergedRights($this, $groups, true);
	}
	
	/**
	 * Get all group rights
	 * @returns hash/assoc. array with rights groups have on this entry
	 */
	function getAllRights() {
	    $rights = array();
	    foreach(Group::getAllGroups() as $group)
	    {
	        array_push($rights, Rights::getRights($this, $group, true));
	    }
	    return $rights;
	}
	
	/**
	 *******************************
	 * Hook into Dataclass methods *
	 *******************************
	 */
	
	/**
	 * Override saveNew, add creation and modification time
	 */
	function saveNew($data) {
		$data['created'] = 'NOW()';
		$data['modified'] = 'NOW()';
		return parent::saveNew($data);
	}
	
	/**
	 * Override saveNew, add modification time
	 */
	function saveUpdate($data) {
		$data['modified'] = 'NOW()';
		return parent::saveUpdate($data);
	}
	
	/**
	 * Override save, propagate modification time
	 */
	function save() {
		$result = parent::save();
		$parent = $this->getParent();
		if ($parent !== NULL)
			$parent->save(); // updates modification time
		return $result;
	}
	
	/**
	 * Removes entry and its descendants from the database
	 */
	function remove()
	{
		$this->removeContent();
		$this->removeChildren();
		$parent = $this->getParent();
		parent::remove();
		if ($parent !== NULL)
			$parent->save(); // updates modification time
	}
	
}

?>
