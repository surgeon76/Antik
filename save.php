<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="localstorage.js"></script>
</head>
<body>
<?php
require_once 'auth.php';
require_once 'localstorage.php';

if (!isset($_POST[InputVar::branch]))
{
	header("Location: catalog.php");
	die(0);
}

$conn = db_connect();

$branch = "" . $_POST[InputVar::branch];

$name = "" . $_POST[InputVar::editName];

$sortOrder = 0;
if (isset($_POST[InputVar::editSortOrder]))
	$sortOrder = 0 + $_POST[InputVar::editSortOrder];

$type = 0;
if (isset($_POST[InputVar::type]))
	$type = $_POST[InputVar::type];

$action = 0;
if (isset($_POST[InputVar::action]))
	$action = $_POST[InputVar::action];

$desc = trim("" . $_POST[InputVar::desc]);

$props = array();
$price = trim("" . $_POST[InputVar::price]);
if (strlen($price) > 0)
	$props[Item::propPrice] = $price;

$author = trim("" . $_POST[InputVar::author]);
if (strlen($author) > 0)
	$props[Item::propAuthor] = $author;

$country = trim("" . $_POST[InputVar::country]);
if (strlen($country) > 0)
	$props[Item::propCountry] = $country;

$year = trim("" . $_POST[InputVar::year]);
if (strlen($year) > 0)
	$props[Item::propYear] = $year;

$images = "" . $_POST[InputVar::images];

$catType = Item::typeProduct;
if (isset($_POST[InputVar::catType]))
	$catType = $_POST[InputVar::catType];

$id = 0;
if ($action == 0)
{
	$id = $_POST[InputVar::id];
	//if ($type == Item::typeChapter || $type == Item::typeCategory)
	//	$type = $catType;
}
else
{
	$type = $action;
	if ($type == Item::typeChapter)
		$name = "Новый раздел";
	else if ($type == Item::typeCategory)
		$name = "Новый раздел";
	else
		$name = "Новый товар";
	$sortOrder = 0;
	$desc = "";
	$props = array();
}

$newParents = array();
if ($action == 0 && isset($_POST[InputVar::checkBoxes]))
{
	$arrCheck = $_POST[InputVar::checkBoxes];
	foreach ($arrCheck as $check)
	{
		$newParents[] = $check;
	}
}
if (count($newParents) == 0 && $type != Item::typeUndefined)
{
	if ($action > 0)
		$newParents[] = Catalog::get_cur_id(Catalog::branch_to_arr($branch));
	else
		$newParents[] = Catalog::get_cur_parent(Catalog::branch_to_arr($branch));
}

$item = Item::CreateItemEx($id, $name, $type, $desc);
$item->set_properties_arr($props);
$item->set_images($images);
$res = Catalog::Save($item, $newParents);

$branchArr = Catalog::branch_to_arr($branch);
$oldParent = Catalog::get_cur_parent($branchArr);

$branchArr = Catalog::ValidateBranch($branchArr);

if ($action == 0 && $res > 0 && count($branchArr) > 1 && in_array($oldParent, $newParents))
{
	Catalog::SaveSortOrder($sortOrder, $res, Catalog::get_cur_parent($branchArr));
	array_splice($branchArr, -1, 1);
}

$branch = Catalog::branch_to_str($branchArr);

db_close();

if ($res > 0)
{
	$path = "catalog.php?" . InputVar::branch . "=" . ($action == 0 ? $branch : $branch . "-" . $res);
	echo 
'<script>
	if (ls_supported())
	{
		ls_set_modified(' . $res . ', false);
		ls_clear(' . $res . ', lsInputArr);
	}
	window.location.href="' . $path . '";
</script>';
}
else
{
	$_SESSION[InputVar::error] = $res;
	header("Location: catalog.php?" . InputVar::branch . "=" . $branch);
}
?>
</body>
</html>
