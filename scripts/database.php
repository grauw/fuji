<?php
	require_once 'adodb_lite/adodb.inc.php';
	require_once 'configuration/database.php';

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
			if(!self::$db || !self::$db->IsConnected()) self::connect();
				return self::$db->GetArray($query);
		}
		
		static function execute($query)
		{
			/*echo $query;*/
			if(!self::$db || !self::$db->IsConnected()) self::connect();
				return self::$db->Execute($query);
		}
		
		static function insert_id()
		{
			if(!self::$db || !self::$db->IsConnected()) self::connect();
				return self::$db->Insert_ID();
		}
	}
?>
