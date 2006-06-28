<?php
	require_once 'database.php';
	/**
	 * Implementation of the ADODB XML Schema (axmls) format
	 * (because adodb-xmlschema is way buggy :()
	 *
	 * It’s not very intelligent yet though (doesn’t merge with existing database).
	 *
	 * See ../adodb_lite/xmlschema.dtd for the (invalid) DTD
	 * 
	 * Elements currently supported: schema, table, DROP, descr, field, sql, query (only prefixmethod=NONE), data, row, f
	 * All field parameters: NOTNULL, KEY, PRIMARY, AUTO, AUTOINCREMENT, DEFAULT, DEFDATE, DEFTIMESTAMP, NOQUOTE, CONSTRAINT, descr
	 */
	class DatabaseAXMLS {
		private $db;
		private $dict;
		private $doc;
		private $xpath;
		
		function __construct($db, $filename)
		{
			$this->db = $db;
			$this->dict = NewDataDictionary($db);
			$this->doc = DOMDocument::load($filename);
			$this->xpath = new DOMXPath($this->doc);
		}
		
		/**
		 * Create tables using ADODB Schema (axmls)
		 * Do it manually, because axmls is way buggy :(
		 *
		 * @param {String} $filename the file to parse
		 */
		function ExecuteSchema() {
			$tables = $this->doc->getElementsByTagName('table');
			foreach ($tables as $table) {
				$this->processTable($table);
			}
			$sqls = $this->doc->getElementsByTagName('sql');
			foreach ($sqls as $sql) {
				$this->processSQL($sql);
			}
		}
		
		private function processTable($table) {
			$tablename = $table->getAttribute('name');
			if ($this->xpath->query('DROP', $table)->length) {
				// if drop table
				$this->dict->ExecuteSQLArray($this->dict->DropTableSQL($tablename));
			} elseif ($table->getElementsByTagName('field')->length) {
				// else create table
				$fieldsarr = array();
				$fields = $table->getElementsByTagName('field');
				foreach ($fields as $field) {
					$fieldsarr[] = $this->processField($field);
				}
				$this->dict->ExecuteSQLArray($this->dict->CreateTableSQL(Database::$db_tprefix . $tablename, $fieldsarr));
			}
			$datas = $table->getElementsByTagName('data');
			foreach ($datas as $data) {
				$this->processData($data, $tablename);
			}
		}
		
		private function processField($field) {
			$fieldarr = array();
			$fieldarr[] = $field->getAttribute('name');
			$fieldarr[] = $field->getAttribute('type');
			if ($field->hasAttribute('size'))
				$fieldarr[] = $field->getAttribute('size');
			$children = $field->getElementsByTagName('*');
			foreach ($children as $child) {
				if ($child->localName == 'descr')
					;
				elseif ($child->hasAttribute('value'))
					$fieldarr[$child->localName] = $child->getAttribute('value');
				else
					$fieldarr[] = $child->nodeName;
			}
			return $fieldarr;
		}
		
		private function processSQL($sql) {
			if ($sql->getAttribute('prefixmethod') == 'NONE') {
				$queries = $sql->getElementsByTagName('query');
				foreach ($queries as $query) {
					$this->processQuery($query);
				}
			}
		}
		
		private function processQuery($query) {
			Database::execute($query->textContent);
		}
		
		private function processData($data, $tablename) {
			$rows = $data->getElementsByTagName('row');
			foreach ($rows as $row) {
				$this->processRow($row, $tablename);
			}
		}
		
		private function processRow($row, $tablename) {
			$noname = TRUE;
			$fields = array();
			$fs = $row->getElementsByTagName('f');
			foreach ($fs as $f) {
				if ($f->hasAttribute('value')) {
					$value = $f->getAttribute('value');
				} else {
					$value = "'".addslashes($f->textContent)."'";
				}
				if ($f->hasAttribute('name')) {
					$fields[$f->getAttribute('name')] = $value;
					$noname = FALSE;
				} else {
					$fields[] = $value;
				}
			}
			$sql = "INSERT INTO " . Database::$db_tprefix . $tablename;
			if (!$noname) {
				$keys = '('.implode(array_keys($fields), ', ').')';
				$sql .= ' '.$keys;
			}
			$values = '('.implode(array_values($fields), ', ').')';
			$sql .= ' VALUES '.$values;
			Database::execute($sql);
		}
		
	}
?>
