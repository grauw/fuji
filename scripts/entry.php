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
	
	static function getTable() {
		return 'entries';
	}
	
	// URI format (hardcoded for now)
	private $item_uri_format = "item/%s";
	private $item_page_uri_format = "item/%s/%s";
	
	/**
	 * returns an Entry-object given its id
	 * @param {Int} $id Entry to be returned
	 * @returns Entry
	 */
	static function getEntry($id) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE id = '$id'");
		$entrydata = $data[0];
		if($entrydata)
			return new Entry($entrydata);
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
		foreach($entriesdata as $entrydata)
		{
			array_push($entries, new Entry($entrydata));
		}
		return $entries;
	}	
	
	/**
	 * adds an Entry to the entry table. If content is given it adds content to the content table.
	 * @param {array}  $data hash/assoc array. Key = column name in table, value is value.
	 * @returns the new Entry
	 */
	static function addEntry($data) {
		$default_proc = 1;
		if(count($data) > 0)
		{
			if(array_key_exists('content', $data) && $data['content'] != null && $data['content'] !== '')
			{
				$content = $data['content'];
				unset($data['content']);
				if(array_key_exists('processor', $data))
				{
					$processor = $data['processor'];
					unset($data['processor']);
				}
				else $processor = $default_proc;
				
				Database::execute("INSERT INTO " . DbVar::$db_tprefix . "content VALUES ('', '$content', '$processor')");
				$data['contentref'] = Database::insert_id();
			}
			$keys = implode(',', array_keys($data));
			$values = implode('\',\'', $data);
			
			Database::execute("INSERT INTO " . Database::$db_tprefix . "entries ($keys, created, modified) VALUES ('$values', NOW(), NOW())");
			$data['id'] = Database::insert_id();
			if(isset($data->ref))
				Entry::getEntry($data->ref)->updateDate();
			return new Entry($data);
		}
	}
	
	/**
	 * Get the root entry
	 * @returns the root Entry
	 */
	static function getRoot() {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref IS NULL");
		if(count($data) != 1) echo 'fout';
		$entrydata = $data[0];
		if($entrydata)
			return new Entry($entrydata);
	}
	
	/**
	 * Get entry's processed content
	 * @returns content as String
	 */
	function getProcessedContent() {
		if(isset($this->contentref))
		{
			$data = Database::getArray("SELECT content, processor FROM " . DbVar::$db_tprefix . "content WHERE id = '$this->contentref'");
			$contentdata = $data[0];
			if ($contentdata['processor'] == '0') {
				return $contentdata['content'];
			}
			else if ($contentdata['processor'] == '1') {
				$bbcode = new BBCode();
				return $bbcode->parse($contentdata['content']);
			}
		}
	}
	
	/**
	 * Get entry's content
	 * @returns content as String
	 */
	function getContent() {
		if(isset($this->contentref))
		{
			$data = Database::getArray("SELECT content FROM " . DbVar::$db_tprefix . "content WHERE id = '$this->contentref'");
			// TODO: not returning 'processor' information
			return $data[0]['content'];
		}
	}
	
	/**
	 * Remove entry's content
	 */
	function removeContent() {
		Database::execute("DELETE FROM " . Database::$db_tprefix . "content WHERE id = '$this->contentref'");
		$this->updateDate();
	}
	
	/**
	 * Update entry's content
	 */
	function updateContent($data) {
		Database::execute("UPDATE " . Database::$db_tprefix . "content SET content = '$data' WHERE id = '$this->contentref'");
		$this->updateDate();
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
		$this->updateDate();
	}
	
	/**
	 * Get entry's parent
	 * @returns parent as Entry
	 */
	function getParent() {
		if($this->ref)
		{
			$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE id = '$this->ref'");
			$parentdata = $data[0];
			return new Entry($parentdata);
		}
	}
	
	/**
	 * removes entry + descendents from entry + content database
	 */
	function remove()
	{
		$this->removeContent();
		$this->removeChildren();
		Database::execute("DELETE FROM " . Database::$db_tprefix . "entries WHERE id = '$this->id'");
		$parent = $this->getParent();
		if(isset($parent))
			$parent->updateDate();
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
	
	function getURI() {
		return sprintf($this->item_uri_format, $this->id);
	}
	
	function getURIWithPage() {
		// TODO: implement
		return sprintf($this->item_page_uri_format, $this->id, '');
	}
	
	function __set($name, $value) {
		parent::__set($name, $value);
		$this->updateDate();
	}
	
	function updateDate() {
		Database::execute("UPDATE " . Database::$db_tprefix . $this->getTable() . " SET modified=NOW() WHERE id='$this->id'");
		$parent = $this->getParent();
		if(isset($parent))
			$parent->updateDate();
	}
}
?>
