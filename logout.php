<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
require_once 'engine.php';
session_start();

unset($_SESSION[InputVar::admin]);
if (!isset($_GET[InputVar::admin]))
	header("Location: catalog.php");
else
	header("Location: " . urldecode($_GET[InputVar::admin]));
?>
</body>
</html>