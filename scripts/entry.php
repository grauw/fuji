<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'usermanager.php';

class Entry extends Dataclass {
	
	static public $TYPE_SYSTEM = 1;
	static public $TYPE_FORUMCONTAINER = 2;
	static public $TYPE_FORUM = 3;
	static public $TYPE_POST = 4;
	
	static function getTable() {
		return 'entries';
	}
	
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
	 * adds an Entry to the entry table. If content is given it adds content to the content table.
	 * @param {array}  $data hash/assoc array. Key = column name in table, value is value.
	 * @returns the new Entry
	 */
	static function addEntry($data) {
		$default_proc = 0;
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
	 * Get entry's content
	 * @returns content as String
	 */
	function getContent() {
		if(isset($this->contentref))
		{
			$data = Database::getArray("SELECT content FROM " . DbVar::$db_tprefix . "content WHERE id = '$this->contentref'");
			$contentdata = $data[0];
			if($contentdata['content'])
				return $contentdata['content'];
		}
	}
	
	/**
	 * Remove entry's content
	 */
	function removeContent() {
		Database::execute("DELETE FROM " . Database::$db_tprefix . "content WHERE id = '$this->contentref'");
	}
	
	/**
	 * Update entry's content
	 */
	function updateContent($data) {
		Database::execute("UPDATE " . Database::$db_tprefix . "content SET content = '$data' WHERE id = '$this->contentref'");
	}
	
	/**
	 * Get entry's children
	 * @returns array of child Entries
	 */
	function getChildren() {
		$children = array();
		$childrendata = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "entries WHERE ref = '$this->id'");
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
	}
}
?>
