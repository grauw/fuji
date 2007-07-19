<?php
require_once 'scripts/database.php';

/**
 * Generic base class for database data objects
 * 
 * This is a base class which can be inherited by database objects, to offer a
 * wrapper around the SQL functions with easy access to the attributes, etc.
 * 
 * In terms of the MVC design pattern, the Dataclass class can be considered the 'model'
 * of the "Dataclass, Template, Resource" threesome.
 * 
 * This class is abstract, and each derived class must implement the following methods:
 *  - getTableName(); returns the name of the database table to use.
 *  - getPrimaryKeyName(); returns the name of the table’s primary key.
 *    NOTE: more than one primary keys don’t really work yet. TODO.
 *  - getResourceUriFormat(); returns a pattern to uniquely identify an instance,
 *    in the form of an URI (e.g. 'item/%s'), which is also sometimes used for output.
 * 
 * On an instance of this class, the following methods are available to access attributes:
 *  - getAttribute($name); gets an attribute (if unavailable, loads from db).
 *  - getAttributeState($name); gets the state of an attribute (NONE, FRESH, DIRTY).
 *  - setAttribute($name, $value); sets an attribute (and marks it for saving).
 *  - setAttributeInitial($name, $value); sets an attribute without marking it for saving.
 *  - setAttributeState($name, $value); sets the state of an attribute.
 *  - hasAttribute($name); checks whether an attribute is loaded on the object.
 *  - removeAttribute($name); removes an attribute from the object.
 *  - getData(); gets you the data object. Avoid.
 * 
 * Q: Maybe getAttributeState and setAttributeState should be private methods?
 * 
 * Also, the following attribute access getter/setter shorthands are available:
 *  - __get($name); shorthand for getAttribute($name).
 *  - __set($name, $value); shorthand for setAttribute($name, $value).
 *  - __isset($name); shorthand for hasAttribute($name).
 *  - __unset($name); shorthand for removeAttribute($name).
 * 
 * Finally, you can use the following methods on a Dataclass-based object:
 *  - load(); explicitly loads data from the database. Should be no need to use,
 *    the object is populated automatically when values are read. Calls loadData(),
 *    which can be hooked if necessary.
 *  - save(); save modified attributes into the database. Creates a new row in the DB for
 *    objects without a primary key value set. saveNew() and saveUpdate() are functions that
 *    can be hooked in case you need to set additional values (such as modification dates).
 *  - remove(); removes the object from the database. Primary key must be set.
 *  - exists(); checks whether the object exists in the database (based on primary key).
 *  - getURI(); gets the unique URI for this resource based on getResourceUriFormat().
 *  - getAbsoluteURI(); same as getURI, but then a full URI.
 * 
 */
abstract class Dataclass {
	
	private $data = array();
	private $datastate = array();
	private $datacache = array();
	
	const STATE_NONE  = 0;
	const STATE_FRESH = 1;
	const STATE_DIRTY = 2;
	
	/**
	 * Constructor can optionally have array for initial attributes
	 */
	function __construct($data = array()) {
		$this->data = $data;
		// TODO: set datastate to STATE_FRESH or STATE_DIRTY ???
	}
	/**
	 * Destructor will warn if there are unsaved attributes (for debugging purposes)
	 */
	function __destruct() {
		foreach ($this->datastate as $key => $value) {
			if ($this->getAttributeState($key) == self::STATE_DIRTY)
				trigger_error("Warning: key $key with value $value for ".$this->getURI()." not saved!");
		}
	}
	
	/**
	 * Abstract functions which the deriving class needs to implement
	 */
	abstract static function getTableName();
	abstract static function getPrimaryKeyName();
	abstract static function getResourceUriFormat();
	
	
	/**
	 * Attribute accessor functions
	 */
	public function getAttribute($name) {
		if(!$this->hasAttribute($name)) {
			$value = $this->getAttributeFromCache($name);
			$this->setAttributeInitial($name, $value);
		}
		return $this->data[$name];
	}
	public function getAttributeState($name) {
		if(!$this->hasAttribute($name))
			return self::STATE_NONE;
		return $this->datastate[$name];
	}
	public function setAttribute($name, $value) {
		$this->data[$name] = $value;
		$this->datastate[$name] = self::STATE_DIRTY;
	}
	public function setAttributeInitial($name, $value) {
		$this->data[$name] = $value;
		$this->datastate[$name] = self::STATE_FRESH;
	}
	public function setAttributeState($name, $value) {
		$this->datastate[$name] = $value;
	}
	public function hasAttribute($name) {
		return isset($this->data[$name]);
	}
	public function removeAttribute($name) {
		unset($this->data[$name]);
		unset($this->datastate[$name]);
	}
	protected function getData() {
		return array_merge($this->datacache, $this->data);
	}
	
	private function getAttributeFromCache($name) {
		if(!isset($this->datacache[$name])) {
			if (!$this->loadToCache()) {
				if ($this->hasAttribute($this->getPrimaryKeyName())) {
					trigger_error("No primary key in object, data can not be found.");
				} else {
					trigger_error("Resource not available in database. Check with exists() before use.");
				}
			}
		}
		if (array_key_exists($name, $this->datacache)) {
			return $this->datacache[$name];
		} else {
			trigger_error("Undefined variable $name requested");
			return NULL;
		}
	}
	
	
	/**
	 * Getters and setters for nonexistant variables
	 */
	function __get($name) {
		return $this->getAttribute($name);
	}
	function __set($name, $value) {
		$this->setAttribute($name, $value);
	}
	function __isset($name) {
		return $this->hasAttribute($name);
	}
	function __unset($name) {
		removeAttribute($name);
	}
	
	
	/**
	 * Load object from database, overwrite all modifications.
	 * 
	 * @return false if not available, true if successful
	 */
	function load() {
		if(!$this->hasAttribute($this->getPrimaryKeyName()))
			return false;
		$data = $this->loadData();
		if (count($data) == 0)
			return false;
		$this->data = $data[0];
		// TODO: reset all datastates to STATE_FRESH 
		return true;
	}
	
	/**
	 * Load object from database into the datacache
	 * 
	 * @return false if not available, true if successful
	 */
	private function loadToCache() {
		if(!$this->hasAttribute($this->getPrimaryKeyName()))
			return false;
		$data = $this->loadData();
		if (count($data) == 0)
			return false;
		$this->datacache = $data[0];
		return true;
	}
	
	/**
	 * Returns the data array for this object.
	 */
	private function loadData() {
		if ($this->hasAttribute($this->getPrimaryKeyName())); //TODO: return something?
		return Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . $this->getTableName() . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "' LIMIT 1");
	}
	
	/**
	 * Save object to database
	 */
	function save() {
		$creata = array();
		while (list($key, $value) = each($this->data)) {
			if (is_numeric($key)) continue;
			if ($this->getAttributeState($key) == self::STATE_DIRTY) {
				if ($value === FALSE)
					$creata[$key] = 'NULL';
				else
					$creata[$key] = "'" . addslashes($value) . "'";
				$this->setAttributeState($key, self::STATE_FRESH);
			}
		}
		if(!$this->hasAttribute($this->getPrimaryKeyName())) {
			$result = $this->saveNew($creata);
			$this->setAttributeInitial($this->getPrimaryKeyName(), Database::insert_id());
		} else {
			unset($data[$this->getPrimaryKeyName()]);
			$result = $this->saveUpdate($creata);
		}
		return $result;
	}
	
	
	/**
	 * Save new object to database (helper function, override when needed)
	 * @param {Array} data Data hash array, with SQL datatype (strings quoted, etc)
	 */
	protected function saveNew($data) {
		$keys = implode(',', array_keys($data));
		$values = implode(',', $data);
		$result = Database::execute("INSERT INTO " . Database::$db_tprefix . $this->getTableName() . " ($keys) VALUES ($values)");
		return $result;
	}
	
	
	/**
	 * Save updated object to database (helper function, override when needed)
	 * @param {Array} data Data hash array, with SQL datatype (strings quoted, etc)
	 */
	protected function saveUpdate($data) {
		$items = array();
		foreach ($data as $key => $value) {
			$items[] = "$key=$value";
		}
		$result = Database::execute("UPDATE " . Database::$db_tprefix . $this->getTableName() . " SET " . implode(',', $items) . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "' LIMIT 1");
		return $result;
	}
	
	
	/**
	 * Remove object from database
	 */
	function remove() {
	    error_log('removing');
		error_log("DELETE FROM " . Database::$db_tprefix . $this->getTableName() . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "' LIMIT 1");
		if(!$this->hasAttribute($this->getPrimaryKeyName())) {
			return false;
		}
		Database::execute("DELETE FROM " . Database::$db_tprefix . $this->getTableName() . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "' LIMIT 1");
		return true;
	}
	
	
	/**
	 * Check whether the resource exists in the database
	 * 
	 * @return true if found, false if not found.
	 */
	function exists() {
		return $this->loadToCache();
	}
	
	
	/**
	 * Gets the URI of this resource
	 */
	function getURI() {
		return sprintf($this->getResourceUriFormat(), $this->getAttribute($this->getPrimaryKeyName()));
	}
	/**
	 * Gets the root-based URI of this resource
	 */
	function getRootedURI() {
		return HttpRequest::$request->getContextPath().'/'.$this->getURI();
	}
	/**
	 * Gets the absolute URI of this resource
	 */
	function getAbsoluteURI() {
		return HttpRequest::$request->getAbsoluteContextPath().'/'.$this->getURI();
	}
	
}

?>
