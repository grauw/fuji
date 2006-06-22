<?php
include_once '../user.php';

class Register2 {   
    
  function execute()
  {
  	$this->tryRegister();
  }

   function tryRegister() {		
		if(User::isUser($_POST['nickname'])) echo 'Nickname already in use';
		else
			if(strlen($_POST['nickname']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['nickname']))
				if(strlen($_POST['email']) > 1 && preg_match('/^[^@]+@([^@.]+\.)+[a-zA-Z]{2,}$/', $_POST['email']))
					if($_POST['password1'] == $_POST['password2'])
					{
						$userdata = array('nickname' => $_POST['nickname'], 'email' => $_POST['email'], 'password' => $_POST['password1']);
						if($_POST['language'] != 'Server default')	$userdata += array('language' => $_POST['language']);
						if($_POST['style'] != 'Server default')	$userdata += array('style' => $_POST['style']);
						if(User::addUser($userdata))
							echo 'Registration successful';
						else
							echo 'Registration failed';
					}
					else
						echo 'Passwords do not match';
				else
					echo 'Invalid e-mail address';
			else
				echo 'Invalid nickname';
	}
}
