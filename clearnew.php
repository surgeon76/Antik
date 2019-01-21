<?php
require_once 'dbengine.php';

$newID = 22;
$miscID = 67;

$conn = db_connect();

$sql = "DELETE FROM CatParent WHERE ParentID = " . $newID . " AND EXISTS (SELECT ID FROM Category WHERE Type = 3 AND TIMESTAMPDIFF(DAY, `Added`, NOW()) > 30 AND ID = CatParent.CatID)";
$result = mysqli_query($conn, $sql);
if (!$result)
{
	db_close();
	die();
}

$sql = "INSERT INTO CatParent (CatID, ParentID) SELECT ID, " . $miscID . " FROM Category WHERE Type = 3 AND NOT EXISTS (SELECT CatID FROM CatParent WHERE CatID = Category.ID)";
$result = mysqli_query($conn, $sql);
//print_r($result);

db_close();
?>