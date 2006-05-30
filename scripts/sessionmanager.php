<?php //sessions.php

require_once "database.php";

class SessionManager {
	static $sess_name;

	static function session_start($path, $name) {
	  self::$sess_name = $name;
	}
	
	static function session_end() {
	  return true;
	}
	
	static function session_read($id) {
	  $q = 'SELECT data FROM sessions WHERE id=\''.$id."'";
	  if ($row = Database::getArray($q)) {
		return $row[0][0];
	  }
	  else return "";
	}
	
	static function session_write($id, $data) {
	  //global $sessname;
	  $q = 'SELECT * FROM sessions WHERE id=\''.$id."'";
	  $result = Database::getArray($q);
	  $num = count($result);
	  if ($num==0)
	  {
		$q = 'INSERT INTO sessions VALUES (\''.$id.'\', \''.$data.'\', NOW())';
		//echo $q;
		$result = Database::execute($q);
	  }
	  else {
		$q = 'UPDATE sessions SET data=\''.$data.'\', lastused=NOW() WHERE id=\''.$id."'";
		//echo $q;
		$result = Database::execute($q);
	  }
	}
	
	static function session_destroy($id) {
	  $q = 'DELETE FROM sessions WHERE id=\''.$id."'";
	  $result = Database::execute($q);
	}
	
	static function session_gc($lifetime) {
	  $q = 'DELETE FROM sessions WHERE lastused < ( NOW() - interval \'1 hour\')';
	  $result = Database::execute($q);
	}

}

session_set_save_handler(
  array("SessionManager", "session_start"),
  array("SessionManager", "session_end"),
  array("SessionManager", "session_read"),
  array("SessionManager", "session_write"),
  array("SessionManager", "session_destroy"),
  array("SessionManager", "session_gc")
);
session_start();

?>
