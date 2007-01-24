<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Register extends Resource {
	
	function httpGet()
	{
		$template = Template::getTemplate();
		$template->applyView('register');
	}
	
	function httpPost()
	{
		if (User::isUser($_POST['nickname']))
			$message = 'Nickname already in use.';
		else
			if (strlen($_POST['nickname']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['nickname']))
				if (strlen($_POST['email']) > 1 && preg_match('/^[^@]+@([^@.]+\.)+[a-zA-Z]{2,}$/', $_POST['email']))
					if ($_POST['password1'] == $_POST['password2']) {
						$userdata = array('nickname' => $_POST['nickname'], 'email' => $_POST['email'], 'password' => $_POST['password1']);
						$user = User::addUser($userdata);
						if ($user) {
							$message = 'Registration successful.';
							header('Location: '.$user->getAbsoluteURI());
							header('HTTP/1.1 201 Created');
						} else
							$message = 'Registration failed.';
					} else
						$message = 'Passwords do not match.';
				else
					$message = 'Invalid e-mail address.';
			else
				$message = 'Invalid nickname.';
		$template = Template::getTemplate();
		$template->message = $message;
		$template->applyView('register-ok');
	}
	
}

new Register();

?>