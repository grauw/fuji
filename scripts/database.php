<?php
	require_once 'adodb_lite/adodb.inc.php';
	require_once 'configuration/database.php';
	require_once 'database_axmls.php';

	class Database extends DbVar{
		
		static $db = null;

		static function connect($create=false)
		{
			if(!self::$db || !self::$db->IsConnected())
			{
				self::$db = ADONewConnection(DbVar::$db_type);
				self::$db->createdatabase = $create;
				if(self::$db_port) self::$db->port = self::$db_port;
				return self::$db->Connect(self::$db_host, self::$db_user, self::$db_pwd, self::$db_name);
			}
		}

		static function getArray($query)
		{
			self::connect();
			return self::$db->GetArray($query);
		}
		
		static function getAll($query)
		{
			self::connect();
			return self::$db->GetAll($query);
		}
		
		static function execute($query)
		{
			self::connect();
			return self::$db->Execute($query);
		}
		
		static function insert_id()
		{
			self::connect();
			return self::$db->Insert_ID();
		}
		
		/**
		 * Create tables using ADODB Schema (axmls)
		 * Do it manually, because axmls is way buggy :(
		 *
		 * @param {String} $filename the file to parse
		 */
		static function LoadAndExecuteSchema($filename) {
			self::connect();
			$axmls = new DatabaseAXMLS(Database::$db, $filename);
			$axmls->ExecuteSchema();
		}
		
	}
?>
