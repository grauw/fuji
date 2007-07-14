<?php
include_once 'scripts/adodb_lite/adodb.inc.php';
include_once 'scripts/adodb_lite/adodb-exceptions.inc.php'; 

class Install1 {
    
	function execute()
	{
		if (isset($_POST['db_host']))
		{
			require_once 'scripts/install/database.php';
			$this->tryDatabase();
		}
		else
		{
			$this->getInfo();
		}
	}

	function tryDatabase() {		
		$db->createdatabase = $_POST['db_create'] == 'true';

		try{
			$db = ADONewConnection($_POST['db_type']);
			$db->Connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pwd'], $_POST['db_name']);
			$this->configDatabase();
			$idb = new InstallDb();
			$idb->createTables();
			$idb->addDefaultRows();
			echo "<p>A functioning database has been found and configured at the supplied address.</p>";
		}
		catch(exception $e) {
			if ($db->createDatabase = true)
				echo "<p>A database could not be created at the supplied address:</p>";
			else
				echo "<p>A database could not be located at the supplied address:</p>";
			?>
			<table>
			<tr><th>Type</th><th>Host</th><th>User</th><th>Port</th><th>Name</th></tr>
			<tr><td><?php echo $_POST['db_type']; ?></td><td><?php echo $_POST['db_host']; ?></td><td><?php echo $_POST['db_user']; ?></td><td><?php echo $_POST['db_port']; ?></td><td><?php echo $_POST['db_name'] ?></td></tr>
			</table>
			<?php global $target_phase, $current_phase;
			  $target_phase = $current_phase;
		}
	}
	
	function configDatabase()
	{
		$file = fopen('../configuration/database.php', 'wb');
		fwrite($file, '<?php '."\n"); 
		fwrite($file, 'class DbVar { '."\n");
		fwrite($file, 'static $db_type =\'' . $_POST['db_type'] . "';\n");
		fwrite($file, 'static $db_host =\'' . $_POST['db_host'] . "';\n"); 
		fwrite($file, 'static $db_user =\'' . $_POST['db_user'] . "';\n");
		fwrite($file, 'static $db_pwd = \'' . $_POST['db_pwd'] . "';\n");
		fwrite($file, 'static $db_port =\'' . $_POST['db_port'] . "';\n");
		fwrite($file, 'static $db_name = \'' . $_POST['db_name'] . "';\n");
		fwrite($file, 'static $db_tprefix = \'' . $_POST['db_tprefix'] . "';\n");
		fwrite($file, '}'."\n");
		fwrite($file, '?>');	
	}
	
	function getInfo($default=true)
	{
		?>
		<table>
			<tr><td>Type</td><td><select name='db_type'><option value='mysql'>mysql</option><option value='postgre'>postgre(sql)</option><option value='mssql'>MSSQL</option></select></td></tr>
			<tr><td>Host</td><td><input name='db_host' type='text' value='localhost' /></td></tr>
			<tr><td>User</td><td><input name='db_user' type='text' value='root' /></td></tr>
			<tr><td>Password</td><td><input name='db_pwd' type='password' value='' /></td></tr>
			<tr><td>Port</td><td><input name='db_port' type='text' value='' /></td></tr>
			<tr><td>Database name</td><td><input name='db_name' type='text' value='forum' /></td></tr>
			<tr><td>Table prefix</td><td><input name='db_tprefix' type='text' value='' /></td></tr>
			<tr><td>Create database</td><td><input name='db_create' type='checkbox' checked='Checked' value='' /></td></tr>
		</table>
		<?php global $target_phase, $current_phase;
			  $target_phase = $current_phase;
	}
}

?>