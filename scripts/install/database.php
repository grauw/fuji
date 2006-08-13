<?php

require_once "../database.php";

class InstallDb
{
	private $schemapath = 'database.xml';
	
	function __construct()
	{
	}

	function createTables()
	{
		Database::connect(true);
		Database::LoadAndExecuteSchema($this->schemapath);
	}
	
	function addDefaultRows()
	{
		//none; all in database.xml
	}
	
	function addDefaultLanguage($lang)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "defaults (name, value) VALUES ('language', '$lang')");
	}
	
	function addDefaultStyle($style)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "defaults (name, value) VALUES ('style', '$style')");		
	}
	
	function addDefaultGZIP($gzip)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "defaults (name, value) VALUES ('gzip', '$gzip')");
	}
	
	function addRootUser($user, $pwd, $email)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "users (nickname, email, password, templateref, inputmethod) VALUES ('$user', '$email', '" . sha1($pwd) . "', '1', '1')");
	}	
	
	function addWelcomeMessage($title, $message)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, userref, title, content, processor, created, modified, type) VALUES ('4', '3', '2', '$title', '$message', 'xhtml', NOW(), NOW(), '4')");
	}	
}
?>
