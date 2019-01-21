<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="localstorage.js"></script>
</head>
<body>
<?php
require_once 'auth.php';
require_once 'localstorage.php';

if (!isset($_GET[InputVar::branch]))
{
	header("Location: catalog.php");
	die(0);
}

$conn = db_connect();

$branch = "" . $_GET[InputVar::branch];
$id = Catalog::get_cur_id(Catalog::branch_to_arr($branch));

$res = Catalog::Delete($id);

db_close();

echo 
'<script>
	if (ls_supported())
	{
		ls_set_modified(' . $id . ', false);
		ls_clear(' . $id . ', lsInputArr);
	}
</script>';

if ($res)
{
	$arr = Catalog::branch_to_arr($branch);
	if (count($arr) > 1)
		array_splice($arr, -1, 1);
	$path = "catalog.php?" . InputVar::branch . "=" . Catalog::branch_to_str($arr);
	echo 
'<script>
	window.location.href="' . $path . '";
</script>';
}
else
{
	$_SESSION[InputVar::error] = $id;
	header("Location: catalog.php?" . InputVar::branch . "=" . $branch);
}
?>
</body>
</html>
