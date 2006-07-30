<?php
require_once 'database.php';

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
		// return table name
		abstract static function getTableName();
		// return primary key name
		abstract static function getPrimaryKeyName();
		// return resource uri format (e.g. 'item/%s')
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
		function getData() {
			return $this->data;
		}		
		
		private function getAttributeFromCache($name) {
			if(!isset($this->datacache[$name]) && $this->hasAttribute($this->getPrimaryKeyName())) {
				$data = $this->loadData();
				if (count($data) == 0) trigger_error("Resource not available in database");
				$this->datacache = $data[0];
			}
			if (isset($this->datacache[$name])) {
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
		 * Load object from database
		 */
		function load() {
			if(!$this->hasAttribute($this->getPrimaryKeyName())) {
				return false;
			}
			$data = $this->loadData();
			$this->data = $data[0];
			return true;
		}
		
		/**
		 * Returns the data array for this object.
		 */
		protected function loadData() {
			if ($this->hasAttribute($this->getPrimaryKeyName())); //TODO: return something?
			return Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . $this->getTableName() . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "'");
		}
		
		/**
		 * Save object to database
		 */
		function save() {
			if(!$this->hasAttribute($this->getPrimaryKeyName())) {
				$creata = array();
				foreach ($this->data as $key => $value) {
					$creata[$key] = "'" . addslashes($value) . "'";
					$this->setAttributeState($key, self::STATE_FRESH);
				}
				$result = $this->saveNew($creata);
				$this->setAttributeInitial($this->getPrimaryKeyName(), Database::insert_id());
			} else {
				$updata = array();
				foreach ($this->data as $key => $value) {
					if ($key != $this->getPrimaryKeyName() && $this->getAttributeState($key) == self::STATE_DIRTY) {
						$updata[$key] = "'" . addslashes($value) . "'";
						$this->setAttributeState($key, self::STATE_FRESH);
					}
				}
				$result = $this->saveUpdate($updata);
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
			$result = Database::execute("UPDATE " . Database::$db_tprefix . $this->getTableName() . " SET " . implode(',', $items) . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "'");
			return $result;
		}
		
		
		/**
		 * Remove object from database
		 */
		function remove() {
			if(!$this->hasAttribute($this->getPrimaryKeyName())) {
				return false;
			}
			Database::execute("DELETE FROM " . Database::$db_tprefix . $this->getTableName() . " WHERE " . $this->getPrimaryKeyName() . "='" . addslashes($this->getAttribute($this->getPrimaryKeyName())) . "'");
			return true;
		}
		
		
		/**
		 * Returns whether the resource exists in the database
		 */
		function exists() {
			if (count($this->loadData()) == 0) return false;
			else return true;
		}
		
		
		/**
		 * Gets the URI of this resource
		 */
		function getURI() {
			return sprintf($this->getResourceUriFormat(), $this->getAttribute($this->getPrimaryKeyName()));
		}
		/**
		 * Gets the absolute URI of this resource
		 */
		function getAbsoluteURI() {
			return ($_SERVER['HTTPS']?'https://':'http://').$_SERVER['HTTP_HOST'].$this->getForumRoot().$this->getURI();
		}
		
		/* ================== Utility functions ================== */
		/**
		 * Gets the root path of the forum
		 * TODO: bit flaky, must be in scripts/ path and must have not parent dir named similarly.
		 * Better way to determine?
		 */
		function getForumRoot() {
			return substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/scripts/') + 1);
		}
		
	}
?>
