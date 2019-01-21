<?php
require_once 'engine.php';
session_start();

$selectMode = Mode3D::modeClassic;
if (isset($_GET[InputVar::selectMode]))
{
	$selectMode = $_GET[InputVar::selectMode];
	$_SESSION[InputVar::selectMode] = $selectMode;
}

echo $selectMode;
?>