<?php

require_once('../database.php');
require_once('../security.php');
require_once('box.php');

class Install2 {

	function execute()
	{
		if(isset($_POST['admin']))
		{
			if($_POST['pw'] === $_POST['pw2'])
			{
				if(Security::isSafe($_POST['pw']) && ($_POST['pw'] != $_POST['admin'])) //safety check.
				{
					Database::connect();
				}
				else
				{
					?>
						Password is unsafe (<a href="passwordpolicy.html" target="_blank">?</a>), please choose another password
					<?php
					$this->showField(true);	
				}
			}
			else
			{
				echo "Passwords did not match, please retype:";
				$this->showField(true);
			}
		}
		else $this->showField();
	}
	
	function showField($isset=false)
	{
		?>
		<table>
			<tr><td>Username</td><td><input type="text" name="admin" value="<?php echo ($isset ? $_POST['admin'] : ""); ?>" /></td></tr>
			<tr><td>Password</td><td><input type="password" name="pw" /></td></tr>
			<tr><td>Re-type password</td><td><input type="password" name="pw2" /></td></tr>
		</table>		
		<?php
	}
}

?>
