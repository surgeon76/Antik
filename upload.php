<?php
require_once 'auth.php';

$data = array();

$id = 0;
if(isset($_GET['id']))
	$id = $_GET['id'];
else
	die(0);

$rootImgWidth = 0;
if(isset($_GET['root']))
	$rootImgWidth = $_GET['root'];

$error = false;
$files = array();
$uploaddir = SystemVars::getImagesPath($id);
@mkdir($uploaddir, 0777, true);
$date = date("Y-m-d_H-i-s");
foreach($_FILES as $file)
{
	$postfix = rand(10000, 99999);
	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
	if (strlen($ext) > 0)
		$ext = '.' . $ext;
	$fname = $date . '_' . $postfix . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploaddir . $fname))
    {
        $files[] = $fname;
		if ($rootImgWidth == 0)
			Thumbnailer::Create($fname, $id);
		else
			Thumbnailer::Create($fname, $id, $rootImgWidth, 0);
    }
    else
    {
        $error = true;
    }
}
$data = $error ? array('error' => 'Upload error') : array('files' => $files);

header("Content-Type: application/json; charset=utf-8", true);
echo json_encode($data);
error_log(json_encode($data) . "\r\n", 3, './log.txt');
?>