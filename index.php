<?php
require_once 'config.php';
require_once 'scripts/httprequest.php';
require_once 'routes.php';

$request = new HttpRequest($routes);
$request->doRequest();

?>