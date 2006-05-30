<?php
/*include_once '../adodb_lite/adodb.inc.php';
include_once '../adodb_lite/adodb-exceptions.inc.php'; */
include_once '../user.php';

class Register2 {   
    
  function execute()
  {
  	$this->tryRegister();
  }

   function tryRegister() {		
		if(User::isUser($_POST['nickname'])) echo 'Nickname already in use';
		else {
			if(User::addUser(array('nickname' => $_POST['nickname'], 'email' => $_POST['email'], 'password' => $_POST['password'])))
				echo 'Registration succesfull';
			else echo 'Registration failed';
		}
	}
}
