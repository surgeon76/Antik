<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="antiqua.css">
</head>
<body>
<?php
require_once 'engine.php';
?>
<form action="" method="POST">
<div style="width: 100%; height: 90%; display: table; text-align: center;">
<div style="display: table-cell; vertical-align: middle;">
<div style="display: inline-block; padding: 15px; border: 2px solid #BDBDBD; background-color: #441406;">
<table align="center" valign="middle" class="<?php echo Css::tableLogin ?>">
<tr>
<td>
Логин:
</td>
<td>
<select style="width:130px" name="<?php echo Html::selectLogin ?>" autofocus>
  <option value="admin">Администратор</option>
  <option value="user">Модератор</option>
</select>
</td>
</tr>
<tr>
<td>
Пароль:
</td>
<td>
<input type="password" style="width:130px" name="<?php echo Html::editPassword ?>" />
</td>
</tr>
</table>
<table align="center" valign="middle" class="<?php echo Css::tableLogin ?>">
<tr>
<td style="text-align: center">
<input type="submit" value="Вход" />
</td>
</tr>
</table>
</div>
</div>
</div>
<?php
session_start();

if (isset($_POST[InputVar::login]) && isset($_POST[InputVar::password]))
{
	$conn = db_connect();
	$key = "";
	if ($_POST[InputVar::login] == 'admin')
		$key = 'AdminPass';
	else
		$key = 'ModeratorPass';
	$password = db_get_value('Settings', $key);
	db_close($conn);
	if ($_POST[InputVar::password] === $password)
	{
		$_SESSION[InputVar::admin] = $_POST[InputVar::login];
		if (!isset($_GET[InputVar::admin]) || strlen(trim($_GET[InputVar::admin])) == 0)
			header("Location: catalog.php");
		else
			header("Location: " . urldecode($_GET[InputVar::admin]));
	}
}
?>
</form>
</body>
</html>