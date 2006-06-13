<?php
require_once "dataclass.php";
require_once "database.php";

	class Rights extends Dataclass {
		static $owner_rights = array('may_read' => 1, 'may_post' => 1, 'may_edit' => 1, 'may_delete' => 1);
		static function getTable() {
			return 'grouprights';
		}
		
		function toString(){
			$string = '';
			if($this->may_read) $string .= 'read, ';
			if($this->may_post) $string .= 'post, ';
			if($this->may_edit) $string .= 'edit, ';
			if($this->may_delete) $string .= 'delete, ';
			$string = rtrim($string, ', ');
			return $string;
		}
}
?>
