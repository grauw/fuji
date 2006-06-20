<?php
require_once 'usermanager.php';
require_once 'database.php';

new Preferences();
	
class Preferences {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST') {
			$this->httpPost();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		$user = UserManager::getUser();
		if(isset($_POST['pass1']) && $_POST['pass1'] != '' && $_POST['pass1'] == $_POST['pass2'] && sha1($_POST['pass1']) != $user->password)
			$user->password = sha1($_POST['pass1']);
		if((!isset($user->language) && $_POST['language']) || ($_POST['language'] != $user->language))
			$user->language = $_POST['language'];
		if((!isset($user->style) && $_POST['style']) || ($_POST['style'] != $user->style))
			$user->style = $_POST['style'];
		if(isset($_POST['email']) && $_POST['email'] != '' && $_POST['email'] != $user->email)
			$user->email = $_POST['email'];
		if(isset($_POST['showEmail']) && $_POST['showEmail'] != $user->email_visible)
			$user->email_visible = ($_POST['showEmail'] == 'on' ? '1' : '0');							
		if(isset($_POST['photo']) && $_POST['photo'] != '' && $_POST['photo'] != $user->picture)
			$user->picture = $_POST['photo'];
		if($user->isAdmin())
		{
			$default_lang = Database::getArray("SELECT value FROM defaults WHERE name = 'language'");
			$default_lang = $default_lang[0][0];
			$default_style = Database::getArray("SELECT value FROM defaults WHERE name = 'style'");
			$default_style = $default_style[0][0];
			$gzip = Database::getArray("SELECT value FROM defaults WHERE name = 'gzip'");
			$gzip = $gzip[0][0];
			$new_default_lang = $_POST['default_language'];
			$new_default_style = $_POST['default_style'];
			$new_gzip = $_POST['gzip'];
			if($new_default_lang != $default_lang)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_default_lang' WHERE name='language'");
			if($new_default_style != $default_style)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_default_style' WHERE name='style'");
			if($new_gzip != $gzip)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_gzip' WHERE name='gzip'");					
		}
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: POST', true, '405');
	}
	
}	
	
?>