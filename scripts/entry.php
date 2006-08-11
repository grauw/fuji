<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'usermanager.php';
// Hardcoded BBCode parser, may need change.
require_once 'processor/bbcode.php';

class Entry extends Dataclass {
	
	static public $TYPE_SYSTEM = 1;
	static public $TYPE_FORUMCONTAINER = 2;
	static public $TYPE_FORUM = 3;
	static public $TYPE_POST = 4;
	
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
	
	static function search($query, $field, $orderby = null, $limit = null) {
		$entries = array();
		$entriesdata = Database::getArray("SELECT *, MATCH($field) AGAINST ('$query' IN BOOLEAN MODE) AS score FROM " . DbVar::$db_tprefix . "entries WHERE MATCH($field) AGAINST ('$query' IN BOOLEAN MODE) AND type > '3'" . (isset($orderby) ? " ORDER BY $orderby" : "") . (isset($limit) ? " LIMIT $limit" : ""));
		foreach($entriesdata as $entrydata) {
			array_push($entries, new Entry($entrydata));
		}
		return $entries;		
	}
	
	/**
	 * Get entries by type
	 * @param String orderby The column to order the entries by
	 * @param String limit The limit of entries
	 * @returns array of Entries
	 */
	static function getEntriesByType($type, $orderby = null, $limit = null) {
		$entries = array();
		$entriesdata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE type = '$type'" . (isset($orderby) ? " ORDER BY $orderby" : "") . (isset($limit) ? " LIMIT $limit" : ""));
		foreach($entriesdata as $entrydata) {
			array_push($entries, new Entry($entrydata));
		}
		return $entries;
	}	
	
	/**
	 * Creates a new entry which is a reply to this entry.
	 * @param {Entry} $entry New entry to add to this entry.
	 * @returns the new Entry
	 */
	function newReply() {
		$entry = new Entry();
		//$entry->setAttributeInitial('ref', $this->id); Doesn't work, why?
		$entry->ref = $this->id;
		return $entry;
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
		//TODO: Use the database instead of hardcoded processors
		$processor = $this->processor;
		if ($processor == '0') {
			return $this->content;
		}
		else if ($processor == '1') {
			$bbcode = new BBCode();
			return $bbcode->parse($this->content);
		}
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
