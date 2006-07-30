<?php
require_once "dataclass.php";
require_once "database.php";

	class Rights extends Dataclass {
	
		static $owner_rights = array('may_read' => 1, 'may_post' => 1, 'may_edit' => 1, 'may_delete' => 1);
		
		static function getTableName() {
			return 'grouprights';
		}
		static function getPrimaryKeyName() {
			// XXX: there is no single key!
			return 'entryid';
		}
		static function getResourceUriFormat() {
			return 'group/%s/rights';
		}
		
		function toString(){
			$string = '';
			if($this->may_read) $string .= L10n::$read . ', ';
			if($this->may_post) $string .= L10n::$post . ', ';
			if($this->may_edit) $string .= L10n::$edit . ', ';
			if($this->may_delete) $string .= L10n::$delete . ', ';
			$string = rtrim($string, ', ');
			return $string;
		}
}
?>
