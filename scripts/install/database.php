<?php

require_once "../database.php";

class InstallDb
{
	function __construct()
	{		
	}

	function createTables()
	{
		Database::connect(true);
		$dict = NewDataDictionary(Database::$db);
		$entries = array(	array(Database::$db_tprefix . 'entries',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('ref', 'I', 'UNSIGNED'),
							array('userref', 'I', 'UNSIGNED', 'NOTNULL'),
							array('title', 'C', 255, 'NOTNULL'),
//							array('processor', 'I1', 'NOTNULL') ) ),
							array('contentref', 'I', 'UNSIGNED'),
							array('created', 'datetime'),
							array('modified', 'datetime'),
							array('type', 'I1', 'NOTNULL') ) ),
							
							array(Database::$db_tprefix . 'content',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('content', 'XL', 'NOTNULL'),
							array('processor', 'I1', 'NOTNULL') ) ),

							array(Database::$db_tprefix . 'views',
							array(
							array('id', 'I1', 'UNSIGNED', 'AUTO', 'KEY'),
							array('description', 'C', 255, 'NOTNULL'),
							array('publicuri', 'C', 255, 'NOTNULL') ) ),

							array(Database::$db_tprefix . 'templates', 
							array(
							array('id', 'I1', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'C', 255, 'NOTNULL'),
							array('public', 'L', 'NOTNULL'),
							array('basedir', 'C', 255, 'NOTNULL') ) ),

							array(Database::$db_tprefix . 'viewtemplates',
							array(
							array('id', 'I2', 'UNSIGNED', 'AUTO', 'KEY'),
							array('templateref', 'I1', 'UNSIGNED', 'NOTNULL'),
							array('typeref', 'I1', 'UNSIGNED', 'NOTNULL'),
							array('location', 'C', 255, 'NOTNULL') ) ),
							
							array(Database::$db_tprefix . 'users',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('nickname', 'C', 255, 'NOTNULL'),
							array('email', 'C', 255, 'NOTNULL'),
							array('email_visible', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('password', 'C', 40, 'NOTNULL'),
							array('language', 'C', 5),
							array('style', 'C', 255),
							array('picture', 'C', 255),
							array('visual_effects', 'L', 'NOTNULL', 'DEFAULT' => '1'),
							array('templateref', 'I', 'UNSIGNED', 'NOTNULL', 'DEFAULT' => '2'),
							array('inputmethod', 'I', 'UNSIGNED', 'NOTNULL', 'DEFAULT' => '2') ) ),

							array(Database::$db_tprefix . 'groups',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'C', 255, 'NOTNULL') ) ),
							
							array(Database::$db_tprefix . 'groupmembers',
							array(
							array('groupid', 'I', 'UNSIGNED', 'KEY'),
							array('userid', 'I', 'UNSIGNED', 'KEY') ) ),
							
							array(Database::$db_tprefix . 'grouprights',
							array(
							array('entryid', 'I', 'UNSIGNED', 'KEY'),
							array('groupid', 'I', 'UNSIGNED', 'KEY'),
							array('may_read', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_post', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_edit', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_delete', 'L', 'NOTNULL', 'DEFAULT' => '0') ) ),
					
							array(Database::$db_tprefix . 'postpostprocessors',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'X', 'NOTNULL') ) ),
							
							array(Database::$db_tprefix . 'styles',
							array(
							array('name', 'C', 255, 'KEY'),
							array('file', 'C', 255, 'NOTNULL'))),
							
							array(Database::$db_tprefix . 'languages',
							array(
							array('id', 'C', 5, 'KEY'),
							array('name', 'C', 255, 'NOTNULL'))),
							
							array(Database::$db_tprefix . 'defaults',
							array(
							array('name', 'C', 255, 'KEY'),
							array('value', 'C', 255, 'NOTNULL')))							
						);
		foreach($entries as $entry)
		{
			$result = $dict->ExecuteSQLArray($dict->CreateTableSQL(DbVar::$db_tprefix . $entry[0], $entry[1]));
		}
	}
	
	function addDefaultRows()
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('1', NULL, '1', '2', 'root', NOW(), NOW(), '1')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('2', '1', '2', '2', 'Fora', NOW(), NOW(), '2')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('3', '2', '3', '2', 'Forum 1', NOW(), NOW(), '3')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "content (id, content, processor) VALUES ('1', '', '0')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "content (id, content, processor) VALUES ('2', '', '0')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "content (id, content, processor) VALUES ('3', '', '0')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "users (nickname, email, password, templateref, inputmethod) VALUES ('anonymous', 'anonymous@localhost', '" . sha1('') . "', '1', '1')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "groups (name) VALUES ('Operators')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "groups (name) VALUES ('Users')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "groups (name) VALUES ('Guests')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "groupmembers (groupid, userid) VALUES ('1', '2')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "groupmembers (groupid, userid) VALUES ('3', '1')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '1', '1', '1', '1', '1')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '2', '1', '1', '0', '0')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '3', '1', '0', '0', '0')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "templates (name, public, basedir) VALUES ('XHTML', '1', 'templates/xhtml/functions.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "templates (name, public, basedir) VALUES ('AJAX', '1', 'templates/ajax/functions.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "templates (name, public, basedir) VALUES ('RSS', '1', 'templates/rss/functions.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "templates (name, public, basedir) VALUES ('Atom', '1', 'templates/atom/functions.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "templates (name, public, basedir) VALUES ('WML', '1', 'templates/wml/functions.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('1', '1', 'main.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('1', '2', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('1', '3', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('1', '4', 'thread.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('2', '1', 'main.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('2', '2', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('2', '3', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('2', '4', 'thread.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('3', '1', 'main.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('3', '2', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('3', '3', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('3', '4', 'thread.php')");			
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('4', '1', 'main.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('4', '2', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('4', '3', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('4', '4', 'thread.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('5', '1', 'main.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('5', '2', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('5', '3', 'forum.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "viewtemplates (templateref, typeref, location) VALUES ('5', '4', 'thread.php')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "styles (name, file) VALUES ('Default Fuji', 'style/default/css/default.css')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "styles (name, file) VALUES ('Dark Fuji', 'style/dark/css/dark.css')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "styles (name, file) VALUES ('Fuji Lite', 'style/lite/css/lite.css')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "styles (name, file) VALUES ('Modern', 'style/modern/css/modern.css')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "styles (name, file) VALUES ('Basic', 'style/basic/css/basic.css')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "languages (id, name) VALUES ('en-GB', 'English (Great Britain)')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "languages (id, name) VALUES ('nl-NL', 'Nederlands (Nederland)')");
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
	
	function addWelcomeMessage($lang)
	{
		Database::execute("INSERT INTO " . Database::$db_tprefix . "entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('4', '3', '4', '2', 'Welcome', NOW(), NOW(), '4')");
		Database::execute("INSERT INTO " . Database::$db_tprefix . "content (id, content, processor) VALUES ('4', 'Welcome to your Fuji Forum!', '0')");				
	}	
}
?>
