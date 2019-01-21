<?php
require_once 'db.php';

function db_connect()
{
	global $servername, $username, $password, $dbname;
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error)
		die("Connection failed: " . $conn->connect_error);
	
	return $conn;
}

function db_close()
{
	global $conn;
	
	$conn->close();
}

function db_get_value($table, $key, $cond = '')
{
	global $conn;
	
	$sql = "SELECT " . $key . " FROM " . $table . " " . $cond;
	return db_get_value_ex($key, $sql);
}

function db_get_value_ex($key, $sql)
{
	global $conn;
	
	$result = mysqli_query($conn, $sql . " LIMIT 1");
	if (!$result)
		return null;
	$ret = null;
	if (mysqli_num_rows($result) == 1)
	{
		$row = mysqli_fetch_assoc($result);
		$ret = $row[$key];
	}
	if ($result !== true)
		mysqli_free_result($result);
	return $ret;
}

?>