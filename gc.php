<?php
require_once 'auth.php';

$conn = db_connect();

$arr = scandir('./' . SystemVars::imagesPath . '/');
foreach ($arr as $id)
{
	if (!is_numeric($id) || !is_dir(SystemVars::getImagesPath($id)))
		continue;
	
	$sql = "SELECT Images FROM Category WHERE ID = " . $id;
	$result = mysqli_query($conn, $sql . " LIMIT 1");
	if (!$result)
		continue;
	$images = '';
	if (mysqli_num_rows($result) == 1)
	{
		$row = mysqli_fetch_assoc($result);
		$images = '' . $row["Images"];
	}
	if ($result !== true)
		mysqli_free_result($result);
	
	$item = new Product($id, "");
	$item->set_images($images);
	$arrImgDB = $item->get_images_arr();
	$arrImgFS = scandir(SystemVars::getImagesPath($id));
	foreach ($arrImgFS as $img)
	{
		$fpath = SystemVars::getImagesPath($id) . $img;
		if (is_dir($fpath))
			continue;
		$name = strpos($img, SystemVars::thumbPrefix) === 0 ? substr($img, strlen(SystemVars::thumbPrefix)) : $img;
		error_log($img . "\r\n", 3, './log.txt');
		if (!in_array($name, $arrImgDB))
		{
			$span = time() - filemtime($fpath);
			error_log($span . "\r\n", 3, './log.txt');
			if ($span > 3600 * 24 * 28)
			{
				//@touch($fpath);
				@unlink($fpath);
			}
		}
	}
	$arrImgFS = scandir(SystemVars::getImagesPath($id));
	if (count($arrImgFS) <= 2)
		@rmdir(SystemVars::getImagesPath($id));
}

db_close();
?>