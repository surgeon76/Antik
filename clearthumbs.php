<?php
require_once 'auth.php';

$arr = scandir('./' . SystemVars::imagesPath . '/');
foreach ($arr as $id)
{
	if (!is_numeric($id) || !is_dir(SystemVars::getImagesPath($id)))
		continue;
	
	$arrImgFS = scandir(SystemVars::getImagesPath($id));
	foreach ($arrImgFS as $img)
	{
		$fpath = SystemVars::getImagesPath($id) . $img;
		if (is_dir($fpath) || strpos($img, SystemVars::thumbPrefix) !== 0)
			continue;

		@unlink($fpath);
	}
}
?>