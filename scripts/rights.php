<?php
require_once "dataclass.php";
require_once "database.php";

	class Rights extends Dataclass {
	
		static $owner_rights = array('may_read' => 1, 'may_post' => 1, 'may_edit' => 1, 'may_delete' => 1);
		static $no_rights = array('may_read' => 0, 'may_post' => 0, 'may_edit' => 0, 'may_delete' => 0);
		
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
		
		/**
		* Get group rights
		* @param Number $entryid the entryid of the entry you want the rights of or all if empty
		* @param Number $groupid the groupid of the group you want the rights of or all if empty
		* @returns hash/assoc. array with rights this group has on this entry
		*/
		static function getRights($entryid, $groupid) {
			$data;
			if(isset($entryid) || isset($groupid)) {
				if(isset($entryid) && isset($groupid)) {
					$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE entryid = '$entryid' AND groupid = '$groupid'");
				} else  {
					if(isset($entryid)) {
						$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE entryid = '$entryid'");
					} else {
						$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights WHERE groupid = '$groupid'");
					}
				}
			} else {
				$data = Database::getArray("SELECT * FROM " . Database::$db_tprefix . "grouprights");
			}
			//TODO: should return array of rights
			$rightsdata = $data[0];
			if($rightsdata)
				return new Rights($rightsdata);
			else
				return new Rights(self::$no_rights);
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
