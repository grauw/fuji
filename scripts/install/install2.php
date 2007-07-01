<?php

class Install2 {

	function execute()
	{
		require_once 'scripts/database.php';
		require_once 'scripts/install/database.php';
		
		if(isset($_POST['admin']) && strlen($_POST['e-mail']) > 0)
		{
			if(strlen($_POST['e-mail']) > 1 && preg_match('/^[^@]+@([^@.]+\.)+[a-zA-Z]{2,}$/', $_POST['e-mail']))
			{
				if($_POST['pw'] === $_POST['pw2'])
				{
					$idb = new InstallDb();
					$idb->addAnonymousUser();
					$idb->addRootUser($_POST['admin'], $_POST['pw'], $_POST['e-mail']);
					$this->showSuccess();
				}
				else
				{
					echo "Passwords did not match, please retype";		
					global $target_phase, $current_phase;
				  	$target_phase = $current_phase;	
				}
			}
			else
			{
				echo "E-mail address is missing";		
				global $target_phase, $current_phase;
				$target_phase = $current_phase;					
			}
		}
		else $this->showField();
	}
	
	function showField($isset=false)
	{
		?>
		<table>
			<tr><td>Username</td><td><input type="text" name="admin" value="<?php echo ($isset ? $_POST['admin'] : ""); ?>" /></td></tr>
			<tr><td>E-mail</td><td><input type="text" name="e-mail" value="<?php echo ($isset ? $_POST['e-mail'] : ""); ?>" /></td></tr>
			<tr><td>Password</td><td><input type="password" name="pw" /></td></tr>
			<tr><td>Re-type password</td><td><input type="password" name="pw2" /></td></tr>
		</table>		
		<?php
		global $target_phase, $current_phase;
		$target_phase = $current_phase;
	}
	
	function showSuccess()
	{
		echo "Your administrative account was created successfully";
	}
}

?>
