<?php

require_once "../database.php";

class InstallDb
{
	function __construct()
	{
		$this->createTables();
		$this->addDefaultRows();
	}

	function createTables()
	{
		Database::connect(true);
		$dict = NewDataDictionary(Database::$db);
		$entries = array(	array('entries',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('ref', 'I', 'UNSIGNED'),
							array('userref', 'I', 'UNSIGNED', 'NOTNULL'),
							array('title', 'C', 255, 'NOTNULL'),
							array('contentref', 'I', 'UNSIGNED'),
							array('created', 'D'),
							array('modified', 'D'),
							array('type', 'I1', 'NOTNULL') ) ),
							
							array('content',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('content', 'XL', 'NOTNULL'),
							array('processor', 'I1', 'NOTNULL') ) ),

							array('views',
							array(
							array('id', 'I1', 'UNSIGNED', 'AUTO', 'KEY'),
							array('description', 'C', 255, 'NOTNULL'),
							array('publicuri', 'C', 255, 'NOTNULL') ) ),

							array('templates', 
							array(
							array('id', 'I1', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'C', 255, 'NOTNULL'),
							array('public', 'L', 'NOTNULL'),
							array('basedir', 'C', 255, 'NOTNULL') ) ),

							array('viewtemplates',
							array(
							array('id', 'I2', 'UNSIGNED', 'AUTO', 'KEY'),
							array('templateref', 'I1', 'UNSIGNED', 'NOTNULL'),
							array('typeref', 'I1', 'UNSIGNED', 'NOTNULL'),
							array('location', 'C', 255, 'NOTNULL') ) ),
							
							array('users',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('nickname', 'C', 255, 'NOTNULL'),
							array('email', 'C', 255, 'NOTNULL'),
							array('email_visible', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('password', 'C', 40, 'NOTNULL'),
							array('language', 'C', 5),
							array('style', 'I'),
							array('picture', 'C', 255),
							array('templateref', 'I', 'UNSIGNED', 'NOTNULL', 'DEFAULT' => '2'),
							array('inputmethod', 'I', 'UNSIGNED', 'NOTNULL', 'DEFAULT' => '2') ) ),

							array('groups',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'C', 255, 'NOTNULL') ) ),
							
							array('groupmembers',
							array(
							array('groupid', 'I', 'UNSIGNED', 'KEY'),
							array('userid', 'I', 'UNSIGNED', 'KEY') ) ),
							
							array('grouprights',
							array(
							array('entryid', 'I', 'UNSIGNED', 'KEY'),
							array('groupid', 'I', 'UNSIGNED', 'KEY'),
							array('may_read', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_post', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_edit', 'L', 'NOTNULL', 'DEFAULT' => '0'),
							array('may_delete', 'L', 'NOTNULL', 'DEFAULT' => '0') ) ),
					
							array('postpostprocessors',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'X', 'NOTNULL') ) ),
							
							array('styles',
							array(
							array('id', 'I', 'UNSIGNED', 'AUTO', 'KEY'),
							array('name', 'X', 'NOTNULL'),
							array('file', 'X', 'NOTNULL'))),
							
							array('languages',
							array(
							array('id', 'C', 5, 'KEY'),
							array('name', 'C', 255, 'NOTNULL'))),
							
							array('defaults',
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
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('1', NULL, '1', '1', 'root', NOW(), NOW(), '1')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('2', '1', '2', '1', 'Fora', NOW(), NOW(), '2')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('3', '2', '3', '1', 'forum 1', NOW(), NOW(), '3')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('4', '2', '4', '1', 'forum 2', NOW(), NOW(), '3')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('5', '3', '5', '1', 'First post!!1!1', NOW(), NOW(), '4')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('6', '3', '6', '1', 'Second post!!1!1', NOW(), NOW(), '4')");
		Database::execute("INSERT INTO entries (id, ref, contentref, userref, title, created, modified, type) VALUES ('7', '4', '7', '1', 'Third post!!1!1', NOW(), NOW(), '4')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('1', '<p>SUPAHF0RAH!</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('2', '<p>Hallo</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('3', '<p>Hallo hallo</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('4', '<p>Hallo hallo hallo</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('5', '<p>Hallo hallo hallo hallo</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('6', '<p>Hallo hallo hallo hallo hallo</p>', '0')");
		Database::execute("INSERT INTO content (id, content, processor) VALUES ('7', '<p>Tset tste tset</p>', '0')");
		Database::execute("INSERT INTO users (nickname, email, password, templateref, inputmethod) VALUES ('root', 'root@localhost', '" . sha1('root') . "', '1', '1')");
		Database::execute("INSERT INTO users (nickname, email, password, templateref, inputmethod) VALUES ('anonymous', 'anonymous@localhost', '" . sha1('') . "', '1', '1')");
		Database::execute("INSERT INTO groups (name) VALUES ('Operators')");
		Database::execute("INSERT INTO groups (name) VALUES ('Users')");
		Database::execute("INSERT INTO groups (name) VALUES ('Guests')");
		Database::execute("INSERT INTO groupmembers (groupid, userid) VALUES ('1', '1')");
		Database::execute("INSERT INTO groupmembers (groupid, userid) VALUES ('3', '2')");
		Database::execute("INSERT INTO grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '1', '1', '1', '1', '1')");
		Database::execute("INSERT INTO grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '2', '1', '1', '0', '0')");
		Database::execute("INSERT INTO grouprights (entryid, groupid, may_read, may_post, may_edit, may_delete) VALUES ('1', '3', '1', '0', '0', '0')");
		Database::execute("INSERT INTO templates (id, name, public, basedir) VALUES ('1', 'XHTML', 'true', 'templates/xhtml/functions.php')");
		Database::execute("INSERT INTO templates (id, name, public, basedir) VALUES ('2', 'AJAX', 'true', 'templates/ajax/functions.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('1', '1', 'main.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('1', '2', 'forum.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('1', '3', 'forum.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('1', '4', 'thread.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('2', '1', 'main.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('2', '2', 'forum.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('2', '3', 'forum.php')");
		Database::execute("INSERT INTO viewtemplates (templateref, typeref, location) VALUES ('2', '4', 'thread.php')");		
		Database::execute("INSERT INTO styles (name, file) VALUES ('Default Fuji', 'default.xml')");
		Database::execute("INSERT INTO styles (name, file) VALUES ('Dark Fuji', 'dark.xml')");
		Database::execute("INSERT INTO languages (id, name) VALUES ('en-GB', 'English (Great Britain)')");
		Database::execute("INSERT INTO languages (id, name) VALUES ('nl-NL', 'Nederlands (Nederland)')");
		Database::execute("INSERT INTO defaults (name, value) VALUES ('language', 'en-GB')");
		Database::execute("INSERT INTO defaults (name, value) VALUES ('style', 'Default Fuji')");
	}
}
?>
