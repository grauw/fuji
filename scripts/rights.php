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
			$l10n = new L10nManager();
			$string = '';
			if($this->may_read) $string .= $l10n->read . ', ';
			if($this->may_post) $string .= $l10n->post . ', ';
			if($this->may_edit) $string .= $l10n->edit . ', ';
			if($this->may_delete) $string .= $l10n->delete . ', ';
			$string = rtrim($string, ', ');
			return $string;
		}
}
?>
