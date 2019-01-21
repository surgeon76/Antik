<?php
require_once 'engine.php';

session_start();

if (!isset($_SESSION[InputVar::admin]) && isset($_GET[InputVar::admin]))
{
	$uri = urlencode(RemoveAdminParam($_SERVER[REQUEST_URI]));
	header("Location: login.php?" . InputVar::admin . "=" . $uri);
}
?>