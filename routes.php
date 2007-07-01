<?php

/**
 * This associative array describes the routing from request to Resource class.
 * 
 * A request’s URI is divided into parts by splitting on / characters, and
 * each part is considered to indicate a path to the final Resource class that
 * will be loaded.
 * 
 * The parts are matched against the keys in the routing array. A key can
 * either be a simple key or a regular expression (if the key starts with a /).
 * Needless to say, a simple key is much faster. Simple keys are also always
 * matched before the regular expression keys.
 * 
 * When a match is found, the value for that key is inspected. It can either
 * be an array or a string. If it’s a string with a Resource class name, that
 * file will be opened. If it’s an array, the whole process will be repeated,
 * but for the next part of the URI. So basically, every array starts
 * a branch, and the tree is walked until a leaf (string) is reached.
 * 
 * If no match is found, the response will be 404 Not Found.
 * 
 * Use '' to match an empty part. That is, in 'example/', the final part is empty.
 * Note that 'example' also has an implicit empty final part, that is, it is equal
 * to 'example/'. The empty part route must be a leaf, '' => array() makes no sense.
 * 
 * Plugins can create their own routing array, and merge it into this main routing
 * array. Care must be taken that if an item for a certain path already exists, that
 * it is branched using an array and not overwritten. The PHP function
 * array_merge_recursive() does exactly this.
 */
$routes = array(
		'' => 'Index',
		'item' => array(
				'/^\d+$/' => array(
						'' => 'Item',	// GET, POST, PUT, DELETE
						'/^\d+$/' => 'Item',	// GET
						'source'   => 'ItemSource',	// GET
						'rights'   => 'ConfigRights'	// POST
					)
			),
		'menu' => array(
				'' => 'Menu',	// GET
				'/^\w+$/' => 'Menu'	// GET
			),
		'register' => 'Register',	// GET
		'preferences' => 'Preferences',	// POST
		'serversettings' => 'ServerSettings', // POST
		'pluginsettings' => 'Plugins', // GET, POST
		'login' => 'Login',	// GET (should be POST?)
		'logout' => 'Logout',	// GET (should be POST?)
		'session-login' => 'SessionLogin',	// GET (should be POST?)
		'session-logout' => 'SessionLogout',	// GET (should be POST?)
		'users' => array(
				'' => 'Users', // GET, POST // Should be separate userlist.php :/
				'/^\d+$/' => array(
						'' => 'Users', // GET, PUT, DELETE
						'profile' => 'Profile'	// GET
					),
			),
		'groups' => array(
				'' => 'Groups', // GET, POST // Should be separate grouplist.php :/
				'/^\d+$/' => array(
						'' => 'Groups', // GET, POST
						'users' => 'Users',	// GET
						'rights' => 'ConfigRights'	// GET
					),
			),
		'calendar' => array(
				'' => 'Calendar',	// GET
				'/^\d{4}-\d{2}$/' => 'Calendar',	// GET
				'/^\d{4}-\d{2}-\d{2}$/' => 'CalendarDay'	// GET
			)
	);

?>