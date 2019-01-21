<?php
class Mode
{
	const getParents = 0;
	const getChildren = 1;
	const getSiblings = 2;
	const getProducts = 3;
	
	const unitW = 226;
	const unitH = 264;
	const doorW = 2;
	const maxRowCount = 2;
	const indent = Mode::unitW;
	
	const vportX = 800;
	const vportY = 600;
	
	private $id = 0;
	private $parentID = 0;
	
	private $catalog = null;
	
	public function __construct(&$cat)
	{
		$this->catalog = &$cat;
		$this->id = $this->catalog->get_sel_id();
		$this->parentID = Catalog::get_cur_parent($this->catalog->get_branch());
	}
	
	private function Get($who = Mode::getParents)
	{
		global $conn;
		
		$sql = "SELECT cp.ParentID, cp.SortOrder, c.ID, c.Type, c.Name, c.Images, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.ParentID = c.ID WHERE cp.CatID = " . $this->id .
			" ORDER BY cp.SortOrder ASC, c.Added DESC";
		if ($who == Mode::getChildren)
		{
			$sql = "SELECT cp.CatID, cp.SortOrder, c.ID, c.Type, c.Name, c.Images, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.CatID = c.ID WHERE cp.ParentID = " . $this->id .
				" AND c.Type <> " . Item::typeProduct . " ORDER BY cp.SortOrder ASC, c.Added DESC";
		}
		else if ($who == Mode::getSiblings)
		{
			$sql = "SELECT cp.CatID, cp.SortOrder, c.ID, c.Type, c.Name, c.Images, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.CatID = c.ID WHERE cp.ParentID = " . $this->parentID .
				" AND c.Type <> " . Item::typeProduct . " ORDER BY cp.SortOrder ASC, c.Added DESC";
		}
		else if ($who == Mode::getProducts)
		{
			$sql = "SELECT cp.CatID, cp.SortOrder, c.ID, c.Type, c.Name, c.Images, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.CatID = c.ID WHERE cp.ParentID = " . $this->id .
				" AND c.Type = " . Item::typeProduct . " ORDER BY cp.SortOrder ASC, c.Added DESC";
		}
		$result = mysqli_query($conn, $sql);
		$arr = [];
		while ($result && $row = mysqli_fetch_assoc($result))
		{
			$itemType = $row["Type"];
			$itemID = $row["ID"];
			$itemName = $row["Name"];
			$item = Item::CreateItemEx($itemID, $itemName, $itemType);
			$item->set_images($row["Images"]);
			$arr[] = $item;
		}	
		if ($result && $result !== true)
			mysqli_free_result($result);
		
		return $arr;
	}
	
	private function aliquote($num, $mult = 12)
	{
		$rem = $num % $mult;
		return $num + ($rem > 0 ? $mult - $rem : 0);
	}
	
	private function DrawDoors($wall, $length, $what, $fwd = true)
	{
		$wallContent = '';
		$left = $length / 2 - count($wall) * Mode::doorW * Mode::unitW / 2;
		for ($i = 0; $i < count($wall); $i++)
		{
			$wallContent .= $this->DrawDoor($wall[$i], $left + (count($wall) - $i - 1) * Mode::doorW * Mode::unitW, $what, $fwd);
		}
		return $wallContent;
	}
	
	public function Draw()
	{
		$lDoors = $this->Get(Mode::getParents);
		$rDoors = $this->Get(Mode::getChildren);
		$siblings = $this->Get(Mode::getSiblings);
		$nDoors = [];
		$pDoors = [];
		$hallIndex = 0;
		for ($i = 0; $i < count($siblings); $i++)
		{
			if ($i > 0 && count($nDoors) == 0 && $siblings[$i - 1]->get_id() == $this->id)
				$nDoors[] = $siblings[$i];
			if ($i < count($siblings) - 1 && count($pDoors) == 0 && $siblings[$i + 1]->get_id() == $this->id)
				$pDoors[] = $siblings[$i];
			if ($siblings[$i]->get_id() == $this->id)
				$hallIndex = $i;
		}
		$products = $this->Get(Mode::getProducts);
		
		$width = 6;
		$length = $width * 2;
		$minL = (count($lDoors) > count($rDoors) ? count($lDoors) : count($rDoors)) * Mode::doorW;
		$minW = (count($nDoors) > count($pDoors) ? count($nDoors) : count($pDoors)) * Mode::doorW;
		if ($minW < $width)
			$minW = $width;
		if ($minL < $length)
			$minL = $length;
		if ($minL < $minW * 2)
			$minL = $minW * 2;
		else
			$minW = $minL / 2;
		$perimeter = $this->aliquote(($minL + $minW) * 2);
		$width = $perimeter / 6;
		$length = $perimeter / 3;
		
		$doorCount = count($lDoors) + count($rDoors) + count($nDoors) + count($pDoors);
		$columns = intval(count($products) / Mode::maxRowCount);
		if (count($products) % Mode::maxRowCount > 0)
			$columns++;
		$columns = $this->aliquote($columns + $doorCount * Mode::doorW);
		if ($columns < $perimeter)
			$columns = $perimeter;
		$freeColumns = $columns - $doorCount * Mode::doorW;
		$rows = intval(count($products) / $freeColumns);
		if ($rows == 0 || count($products) % $freeColumns > 0)
			$rows++;
		$width = $columns / 6;
		$length = $columns / 3;
		$height = $rows < 3 ? 3 : Mode::maxRowCount + 1;
		
		$lWallContent = $this->DrawDoors($lDoors, $length * Mode::unitW + Mode::indent * 2, Mode::getParents);
		$rWallContent = $this->DrawDoors($rDoors, $length * Mode::unitW + Mode::indent * 2, Mode::getChildren);
		$nWallContent = $this->DrawDoors($nDoors, $width * Mode::unitW + Mode::indent * 2, Mode::getSiblings);
		$pWallContent = $this->DrawDoors($pDoors, $width * Mode::unitW + Mode::indent * 2, Mode::getSiblings, false);
		
		if (count($products) == 0 && count($lDoors) == 0)
			$lWallContent = $this->DrawThumbs($length * Mode::unitW + Mode::indent * 2, $height * Mode::unitH);
		
		$height0 = Mode::unitH * ($height - $rows) / 2;
		$lWallStartIndex = 0;
		$nWallStartIndex = $lWallStartIndex + $length - count($lDoors) * Mode::doorW;
		$rWallStartIndex = $nWallStartIndex + $width - count($nDoors) * Mode::doorW;
		$pWallStartIndex = $rWallStartIndex + $length - count($rDoors) * Mode::doorW;
		for ($i = 0; $i < count($products); $i++)
		{
			$col = $i % $freeColumns;
			$row = intval($i / $freeColumns);
			$top = $height0 + $row * Mode::unitH;
			$left = 0;
			if ($col < $nWallStartIndex / 2)
			{
				$left = $col * Mode::unitW;
				$lWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < $nWallStartIndex)
			{
				$left = ($col + count($lDoors) * Mode::doorW) * Mode::unitW;
				$lWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < ($rWallStartIndex + $nWallStartIndex) / 2)
			{
				$left = ($col - $nWallStartIndex) * Mode::unitW;
				$nWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < $rWallStartIndex)
			{
				$left = ($col - $nWallStartIndex + count($nDoors) * Mode::doorW) * Mode::unitW;
				$nWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < ($pWallStartIndex + $rWallStartIndex) / 2)
			{
				$left = ($col - $rWallStartIndex) * Mode::unitW;
				$rWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < $pWallStartIndex)
			{
				$left = ($col - $rWallStartIndex + count($rDoors) * Mode::doorW) * Mode::unitW;
				$rWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else if ($col < ($freeColumns + $pWallStartIndex) / 2)
			{
				$left = ($col - $pWallStartIndex) * Mode::unitW;
				$pWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
			else
			{
				$left = ($col - $pWallStartIndex + count($pDoors) * Mode::doorW) * Mode::unitW;
				$pWallContent .= $this->DrawImage3D($products[$i], $top, $left);
			}
		}
		echo $this->DrawScene($width * Mode::unitW + Mode::indent * 2, $height * Mode::unitH, $length * Mode::unitW + Mode::indent * 2,
			['left' => $lWallContent, 'next' => $nWallContent, 'right' => $rWallContent, 'prev' => $pWallContent], $hallIndex);
			
		echo '<div style="position: relative; height: 0;">' . $this->DrawArrows($width * Mode::unitW + Mode::indent * 2) . 
			$this->DrawHelp() . '</div>';
	}
	
	private function DrawImage3D($item, $top, $left)
	{
		$div = sprintf('<div id="%s" style="position: absolute; display: inline-block; top: %g; left: %g;">', 'p' . $item->get_id(), $top, $left + Mode::indent);
		$br = $this->catalog->get_branch();
		$br[] = $item->get_id();
		return $div . $item->DrawImage(basename($_SERVER['PHP_SELF']), $br, 'inline-block', false, true) . '</div>';
	}
	
	private function DrawLink3D($item, $what)
	{
		$match = false;
		$br = $this->catalog->get_branch();
		if ($what == Mode::getSiblings)
		{
			$br[count($br) - 1] = $item->get_id();
		}
		else if ($what == Mode::getChildren)
		{
			$br[] = $item->get_id();
		}
		else
		{
			if ($item->get_id() == Catalog::get_cur_parent($br))
				$match = true;
			$alt = $this->catalog->get_alt_branches();
			for ($i = 0; $i < count($alt); $i++)
			{
				if ($item->get_id() == $alt[$i][count($alt[$i]) - 2])
				{
					$br = $alt[$i];
					array_splice($br, -1, 1);
					break;
				}
			}
		}
		
		return $item->DrawLink(basename($_SERVER['PHP_SELF']), $br, $match ? Item::selBold | Item::selSel : Item::selSel, false, Item::branchMode3D, false);
	}
	
	private function DrawDoor($item, $left, $what, $fwd = true)
	{
		$arrowDoorL = 'up_l.png';
		$arrowDoorR = 'up_r.png';
		if ($what == Mode::getChildren)
		{
			$arrowDoorL = 'down_l.png';
			$arrowDoorR = 'down_r.png';
		}
		else if ($what == Mode::getSiblings)
		{
			if ($fwd)
			{
				$arrowDoorL = 'fwd_l.png';
				$arrowDoorR = 'fwd_r.png';
			}
			else
			{
				$arrowDoorL = 'back_l.png';
				$arrowDoorR = 'back_r.png';
			}
		}
		
		$h = Mode::unitH * 2.5;
		$borderW = 20;
		$doorBorderW = 2;
		$indent = 10;
		$w = intval(Mode::unitW * 2) - 10 - $borderW - $indent * 2;
		$breaks = array("<br />", "<br>", "<br/>");  
		$title = htmlspecialchars(str_ireplace($breaks, "\r\n", $item->get_name()));
			
		$frame = sprintf('<div id="%s" title="%s" class="door" style="display: inline-block; position: absolute; cursor: pointer; border-width: 20px 20px 0px 20px; border-style: solid; height: %g; width: %g; bottom: 0; left: %g;">',
			'd' . $item->get_id(), $title, $h, $w - $borderW, $left + $indent);
		$caption = sprintf('<div style="display: table; width: 100%%;"><div style="display: table-row;"><div style="position: relative; display: table-cell; text-align: center; vertical-align: middle; height: %g;">',
			Mode::unitH * 0.5);
		$caption .= sprintf('<div id="%s" style="position: relative; display: inline-block;">', 'c' . $item->get_id());
		$doors = sprintf('<div style="display: inline-block; position: relative; overflow: hidden; background-color: orange; width: %g; height: %g;">', $w - $borderW, Mode::unitH * 2);
		$ldoor = sprintf('<div class="ldoor" style="position: absolute; background-color: #777571; left: 0; bottom: 0; width: %g; height: %g; background-size: 30px 60px; background-image: url(\'%s\'); background-repeat: no-repeat; background-position: %gpx %gpx;">',
			$w / 2 - $borderW / 2, Mode::unitH * 2, '/' . SystemVars::imagesPath . '/' . $arrowDoorL, $w / 2 - $borderW / 2 - 30, $h - Mode::unitH * 2 + $borderW / 2 + 60);
		$rdoor = sprintf('<div class="rdoor" style="position: absolute; background-color: #54493E; right: 0; bottom: 0; width: %g; height: %g; background-size: 30px 60px; background-image: url(\'%s\'); background-repeat: no-repeat; background-position: %gpx %gpx;">',
			$w / 2 - $borderW / 2, Mode::unitH * 2, '/' . SystemVars::imagesPath . '/' . $arrowDoorR, 0, $h - Mode::unitH * 2 + $borderW / 2 + 60);
		$lwindow = sprintf('<div style="position: relative; width: %g; height: %g;"><div class="lwindow" style="position: absolute; border: 1px solid #2B1609; background-color: black; right: %gpx; bottom: %gpx; width: 40px; height: 60px;"></div></div>',
			$w / 2 - $borderW / 2, Mode::unitH * 2, $w / 8, $h / 5 * 2);
		$rwindow = sprintf('<div style="position: relative; width: %g; height: %g;"><div class="rwindow" style="position: absolute; border: 1px solid #2B1609; background-color: black; left: %gpx; bottom: %gpx; width: 40px; height: 60px;"></div></div>',
			$w / 2 - $borderW / 2, Mode::unitH * 2, $w / 8, $h / 5 * 2);
		
		return $frame . $caption . $this->DrawLink3D($item, $what) . '</div></div></div></div>' . $doors . $ldoor . $lwindow . '</div>' . $rdoor . $rwindow . '</div></div></div>';
	}
	
	private $colorSet =
	[
		['#440000', '#4D0000', '#ECE9E3', '#190C07'],
		['#004400', '#004D00', '#ECE9E3', '#190C07'],
		['#000044', '#00004D', '#ECE9E3', '#190C07'],
		['#444400', '#4D4D00', '#ECE9E3', '#190C07'],
		['#004444', '#004D4D', '#ECE9E3', '#190C07'],
		['#440044', '#4D004D', '#ECE9E3', '#190C07'],
		['#444444', '#4D4D4D', '#ECE9E3', '#190C07']
	];
	private function DrawScene($w, $h, $l, $walls, $hallIndex = 0)
	{
		$hallIndex %= count($this->colorSet);
		$colors = $this->colorSet[$hallIndex];
		
		$scene .= sprintf('<div id="%s" style="display: inline-block; position:relative; overflow: hidden; perspective: %gpx; width: %g; height: %g;">', Html::vport, Mode::unitW * 4, Mode::vportX, Mode::vportY);
		$scene .= sprintf('<div id="%s" style="display: inline-block; position: relative; width: %g; height: %g; left: %g; top: %g; transform-style: preserve-3d; -webkit-transform-style: preserve-3d; transform: translateZ(%gpx);">',
			Html::scene, $w, $h, Mode::vportX / 2 - $w / 2, Mode::vportY / 2 - $h / 2, Mode::unitW * 2);
			
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: rotateY(90deg) translateX(%gpx) translateZ(%gpx); width: %g; height: %g;">',
			Html::lWall, Css::wall, $colors[0], 0, -$w + Mode::indent, $l, $h);
		$scene .= $walls['left'];
		$scene .= '</div>';
		
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: translateZ(%gpx); width: %g; height: %g;">',
			Html::nWall, Css::wall, $colors[1], -$l / 2, $w, $h);
		$scene .= $walls['next'];
		$scene .= '</div>'; 	
		
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: rotateY(-90deg) translateZ(%gpx); width: %g; height: %g;">',
			Html::rWall, Css::wall, $colors[0], -Mode::indent, $l, $h);
		$scene .= $walls['right'];
		$scene .= '</div>';
		
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: rotateY(180deg) translateZ(%gpx); width: %g; height: %g;">',
			Html::pWall, Css::wall, $colors[1], -$l / 2, $w, $h);
		$scene .= $walls['prev'];
		$scene .= '</div>';
		
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: rotateX(-90deg) translateY(%gpx) translateZ(%gpx); width: %g; height: %g;">',
			Html::tWall, Css::wall, $colors[2], 0, -$l / 2, $w, $l);
		$scene .= '</div>';
		
		$scene .= sprintf('<div id="%s" class="%s" style="position: absolute; left: 0; top: 0; background-color: %s; transform: rotateX(90deg) translateY(%gpx) translateZ(%gpx); width: %g; height: %g;">',
			Html::bWall, Css::wall, $colors[3], 0, $l / 2 - $h, $w, $l);
		$scene .= '</div>';
		
		$scene .= '</div></div>';
		
		return $scene;
	}
	
	public function DrawLinkBack($path)
	{
		$back = array_slice($this->catalog->get_branch(), 0, -1);
		$from = '&' . InputVar::from . '=' . $this->catalog->get_sel_id();
		$href = '/' . $path . '?' . InputVar::branch . '=' . Catalog::branch_to_str($back) . $from;
		echo '<div style="margin: 2px 2px 6px 2px;"><a id="linkBack" style="font-size: 19px; font-weight: bold;" href="' . $href . '">&#8592; в галерею</a></div>';
	}
	
	private function DrawArrows($w)
	{
		$div = sprintf('<div id="arrowHolder" style="pointer-events: none; display: inline-block; position: absolute; width: 150px; height: 150px; left: %g; bottom: 4px; perspective: 150px;">',
			Mode::vportX / 2 - 75);
		$div .= '<div style="position: relative; display: inline-block; width: 150px; height: 150px; transform: rotateX(45deg); transform-style: preserve-3d; -webkit-transform-style: preserve-3d;">';
		
		$lArrow = sprintf('<div id="leftArrow" class="modeWalkArrows" style="pointer-events: auto; background-image: url(%s); background-size: 50px 50px;"></div>', '/' . SystemVars::imagesPath . '/arrow_l.png');
		$fArrow = sprintf('<div id="forwardArrow" class="modeWalkArrows" style="pointer-events: auto; background-image: url(%s); background-size: 50px 50px;"></div>', '/' . SystemVars::imagesPath . '/arrow_f.png');
		$rArrow = sprintf('<div id="rightArrow" class="modeWalkArrows" style="pointer-events: auto; background-image: url(%s); background-size: 50px 50px;"></div>', '/' . SystemVars::imagesPath . '/arrow_r.png');
		$bArrow = sprintf('<div id="backwardArrow" class="modeWalkArrows" style="pointer-events: auto; background-image: url(%s); background-size: 50px 50px;"></div>', '/' . SystemVars::imagesPath . '/arrow_b.png');
		
		return $div . $lArrow . $fArrow . $rArrow . $bArrow . '</div></div>';
	}
	
	private function DrawHelp()
	{
		$text = 'Для перемещения и поворотов при помощи клавиатуры используйте клавиши стрелок или клавиши \'a\', \'w\', \'s\', \'d\'.

Удерживайте клавишу \'Shift\' для смены режима поворота/перемещения.

Двигайте мышь с зажатой левой клавишей для поворотов.

Используйте вращение колесика мыши для перемещения.';
		return sprintf('<div id="help" title="%s" style="right: 4px; bottom: 4px; background-image: url(%s);">',
			$text, '/' . SystemVars::imagesPath . '/help.png');
	}
	
	public function DrawThumbs($l, $h)
	{
		$item = Item::CreateItem($this->id);
		if ($item->get_type() != Item::typeChapter && $item->get_type() != Item::typeUndefined)
			return;
		if ($item->get_image_count() == 0)
		{
			$itemID = Item::get_root_id();
			$item = Item::CreateItem($itemID);
		}
		$ret = '<input type="hidden" id="' . Html::hidImagesID . '" name="' . Html::hidImagesID . '" value="'. $item->get_images() . '" />';
		$imgLen = $item->get_image_count() * (SystemVars::rootImagesWidth + 8);
		if ($imgLen > $l)
			$imgLen = $l;
		$style = sprintf('overflow: hidden; white-space: nowrap; position: absolute; left: %g; top: %g; width: %g; max-width: %g; height: %g;',
			$l / 2 - $imgLen / 2, $h / 2 - SystemVars::rootImagesHeight / 2, $l, $l, SystemVars::rootImagesHeight);
		$ret .= '<div style="display: inline-block; position: relative;"><div id="' . Html::divThumbsID . '" style="' . $style . '"></div></div>';
		
		return $ret;
	}
}

?>

<script>
var divVport = "<?php echo Html::vport ?>";
var divScene = "<?php echo Html::scene ?>";
var minDist = <?php echo (Mode::unitW * 2.1); ?>;
var lWallID = "<?php echo Html::lWall ?>";
var rWallID = "<?php echo Html::rWall ?>";
var nWallID = "<?php echo Html::nWall ?>";
var pWallID = "<?php echo Html::pWall ?>";
var tWallID = "<?php echo Html::tWall ?>";
var bWallID = "<?php echo Html::bWall ?>";
var wallClass = "<?php echo Css::wall ?>";
var modeWalkHid = "<?php echo Html::selectModeHid ?>";
</script>
