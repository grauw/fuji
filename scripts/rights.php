<?php
require_once "dataclass.php";
require_once "database.php";

	class Rights extends Dataclass {
		static $owner_rights = array('may_read' => 1, 'may_post' => 1, 'may_edit' => 1, 'may_delete' => 1);
		static function getTable() {
			return 'grouprights';
		}
}
?>
