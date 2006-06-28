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
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('4', '3', '4', '2', '$title', NOW(), NOW(), '4')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "content (id, content, processor) VALUES ('4', '$message', '0')");				
	}	
}
?>
