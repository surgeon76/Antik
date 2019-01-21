<?php
require_once 'dbengine.php';
require_once './wideimage/WideImage.php';

error_reporting(E_ALL & ~E_NOTICE);

function DrawHeader($edit, $selectMode = '')
{
	echo '<table align="center" class="' . Css::tableFrame . '" id="' . Css::tableFrame . '"><tr><td class="' . Css::tdLogo . '">'; //for logo
	echo '<div class="' . Css::divLogo . '">Антикварный&nbsp;салон</div>';
	$exit = '   <a href="logout.php?' . InputVar::admin . '=' . urlencode(RemoveAdminParam($_SERVER['REQUEST_URI'])) . '">выход</a>';
	echo '<div class="' . Css::divAdmin . '">' . ($edit ? (($_SESSION[InputVar::admin] == 'admin' ? '<b>Администратор</b>' : '<b>Модератор</b>') . $exit) : '' ) . '</div>';
	echo '<div class="' . Css::divInfo . '"><a href="http://www.antik-krym.ru">www.antik-krym.ru</a><br>
Россия, Республика Крым<br />г. Симферополь, ул. Толстого, д.9<br />+7 (978) 050 26 23<br>
E-mail: <a href="mailto:antikvarkrym@mail.ru" style="text-decoration: none">antikvarkrym@mail.ru</a></div>';
	echo '</td></tr><tr><td class="' . Css::tdMenu . '">'; //menu
	echo $selectMode;
	echo '<a href="catalog.php">Каталог</a>&nbsp;&nbsp;&nbsp;&diams;&nbsp;&nbsp;&nbsp;<a href="delivery.php">Оплата и доставка</a>&nbsp;&nbsp;&nbsp;&diams;&nbsp;&nbsp;&nbsp;<a href="about.php">О салоне</a>';
	echo '</td></tr><tr><td>'; // for content
}

function DrawFooter()
{
	echo '</td></tr>';
	echo '<tr><td class="' . Css::tdFooter . '">Антикварный салон';
	echo '</td></tr></table>';
}

function RemoveAdminParam($uri)
{
	$parts = parse_url($uri);

	$queryParams = array();
	parse_str($parts['query'], $queryParams);

	if (isset($queryParams[InputVar::admin]))
		unset($queryParams[InputVar::admin]);

	$queryString = http_build_query($queryParams);
	return $parts['path'] . '?' . $queryString;
}

function GetKeyPhrases()
{
	return '<div style="font-size: 12px; color: #160300;">антикварный салон, антикварный магазин, антикварная лавка, толстого 9, антикварный, антикварная, антиквариат, антиквар, симферополь, крым</div>';
}

class Mode3D
{
	const modeClassic = 0;
	const mode3d = 1;
}

class SystemVars
{
	const imagesPath = "images";
	const thumbPrefix = "thumb_";
	const rootImagesWidth = 360;
	const rootImagesHeight = 250;
	const thumbImagesWidth = 200;
	const thumbImagesHeight = 200;
	
	static public function getImagesPath($id)
	{
		return './' . SystemVars::imagesPath . '/' . $id . '/';
	}
}

class Css
{
	const wall = "wall";
	const treeLine = "treeLine";
	const treeLineSel = "treeLineSel";
	const treeLineBold = "treeLineBold";
	const divProductLink = "divProductLink";
	const tableCommon = "tableCommon";
	const divOverLink = "divOverLink";
	const divDelLink = "divDelLink";
	const divImage = "divImage";
	const divImageEmpty = "divImageEmpty";
	const tableProduct = "tableProduct";
	const tdPropLabel = "tdPropLabel";
	const divProps = "divProps";
	const divDesc = "divDesc";
	const tableProps = "tableProperties";
	const divInfo = "divInfo";
	const tdFooter = "tdFooter";
	const tableLogin = "tableLogin";
	const divAdmin = "divAdmin";
	const tdMenu = "tdMenu";
	const tableCat = "tableCat";
	const divLogo = "divLogo";
	const tdLogo = "tdLogo";
	const tdTree = "tdTree";
	const tdItem = "tdItem";
	const tableFrame = "tableFrame";
	const bold = "bold";
	const selected = "selected";
	const curr = "current";
	const divTreeItem = "divTreeItem";
	const divTreeItem2 = "divTreeItem2";
	const checkBoxGrayed = "checkBoxGrayed";
	const divProduct = "divProduct";
	const root = "root";
	const chapter = "chapter";
	const category = "category";
	const product = "product";
	const branchSel = "branchSel";
	const imgProduct = "imgProduct";
	const divProductText = "divProductText";
}

class Html
{
	const lWall = "lWall";
	const rWall = "rWall";
	const nWall = "nWall";
	const pWall = "pWall";
	const tWall = "tWall";
	const bWall = "bWall";
	const scene = "scene";
	const vport = "vport";
	const selectModeHid = "selectModeHid";
	const selectMode = InputVar::selectMode;
	const rootImagesWidthID = "rootImagesWidth";
	const divItemsContainer = "divItemsContainer";
	const tdProduct = "tdProduct";
	const selectType = "selectType";
	const hidAdminMode = "hidAdminMode";
	const addImgID = "addImg";
	const divImage = Css::divImage;
	const divThumb = "divThumb";
	const divThumbSel = "divThumbSel";
	const imgThumb = "imgThumb";
	const imageID = "image";
	const divThumbsID = "divThumbs";
	const hidImagesID = "hidImages";
	const editDesc = "itemDesc";
	const editYear = "itemYear";
	const editCountry = "itemCountry";
	const editAuthor = "itemAuthor";
	const editPrice = "itemPrice";
	const checkBoxPrefix = "cb_";
	const linkPrefix = "a_";
	const editName = "itemName";
	const editSortOrder = "itemSortOrder";
	const checkBoxes = "checkBoxes";
	const selectNew = "new";
	const hidBranch = InputVar::branch;
	const hidID = InputVar::id;
	const buttonSave = "buttonSave";
	const selectLogin = InputVar::login;
	const editPassword = InputVar::password;
	const hidType = InputVar::type;
}

class InputVar
{
	const from = "from";
	const selectMode = "selectMode";
	const limitProducts = "limitProducts";
	const back = "back";
	const catType = Html::selectType;
	const gc = "gc";
	const images = Html::hidImagesID;
	const error = "error";
	const desc = Html::editDesc;
	const year = Html::editYear;
	const country = Html::editCountry;
	const author = Html::editAuthor;
	const price = Html::editPrice;
	const admin = "admin";
	const branch = "branch";
	const action = "new";
	const checkBoxes = Html::checkBoxes;
	const id = "id";
	const login = "login";
	const password = "password";
	const editName = Html::editName;
	const editSortOrder = Html::editSortOrder;
	const type = "type";
}

abstract class Item
{
	const typeUndefined = 0;
	const typeChapter = 1;
	const typeCategory = 2;
	const typeProduct = 3;
	
	const selSel = 0b001;
	const selCur = 0b010;
	const selBold = 0b100;
	
	const checkBoxU = 0b001; //unchecked
	const checkBox = 0b010; //checked
	const checkBoxG = 0b100; //grayed
	
	const propType = "Тип";
	const propPrice = "Цена";
	const propAuthor = "Автор";
	const propCountry = "Страна";
	const propYear = "Год";
	
	const branchModePlain = 0;
	const branchModeTree = 1;
	const branchModeItems = 2;
	const branchMode3D = 3;
	
	protected $id = 0;
	protected $name = "";
	protected $type = typeUndefined;
	protected $sortOrder = 0;
	protected $desc = "";
	protected $properties = array();
	protected $images = array();
	
	public static function get_root_id()
	{
		return db_get_value('Category', 'ID', 'WHERE `Type` = ' . Item::typeUndefined);
	}
	
	public static function CreateItem($itemID)
	{
		global $conn;
	
		$item = null;
		$sql = "SELECT Name, `Type`, `Desc`, Properties, Images FROM Category WHERE ID = " . $itemID;
		$result = mysqli_query($conn, $sql);
		if ($result && $row = mysqli_fetch_assoc($result))
		{
			$itemName = $row["Name"];
			$itemType = $row["Type"];
			$itemDesc = $row["Desc"];
			$item = Item::CreateItemEx($itemID, $itemName, $itemType, $itemDesc);
			$itemProps = $row["Properties"];
			$itemImages = $row["Images"];
			$item->set_properties($itemProps);
			$item->set_images($itemImages);
		}
		if ($result && $result !== true)
			mysqli_free_result($result);
		return $item;
	}
	
	public static function CreateItemEx($itemID, $itemName, $itemType, $itemDesc = "")
	{
		$item = null;
		
		if ($itemType == Item::typeChapter)
			$item = new Chapter($itemID, $itemName, $itemDesc);
		else if ($itemType == Item::typeCategory)
			$item = new Category($itemID, $itemName, $itemDesc);
		else if ($itemType == Item::typeProduct)
			$item = new Product($itemID, $itemName, $itemDesc);
		else
			$item = new Root($itemID, $itemName, $itemDesc);
		
		return $item;
	}

	public function __construct($itemID, $itemName, $itemType, $itemDesc = "")
	{
		$this->id = $itemID;
		$this->name = $itemName;
		$this->type = $itemType;
		$this->desc = $itemDesc;
	}

	public final function get_id()
	{
		return $this->id;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	public function get_sort_order()
	{
		return $this->sortOrder;
	}
	
	public function set_sort_order($parentID)
	{
		$this->sortOrder = db_get_value('CatParent', 'SortOrder', 'WHERE CatID = ' . $this->id . ' AND ParentID = ' . $parentID);
		if (is_null($this->sortOrder))
			$this->sortOrder = 0;
	}
	
	public function get_desc()
	{
		return $this->desc;
	}
	
	public function get_prop_count()
	{
		return count($this->properties);
	}
	
	public function get_property($key)
	{
		if (isset($this->properties[$key]))
			return $this->properties[$key];
		return "";
	}
	
	public function set_properties($props)
	{
		array_splice($this->properties, 0);
		
		$str = trim(trim($props), '>');
		if (strlen($str) == 0)
			return;
		
		$arr = explode('>', $str);
		foreach($arr as $pair)
		{
			$p = explode('<', $pair);
			if (count($p) > 1)
			{
				$key = html_entity_decode($p[0]);
				$val = html_entity_decode($p[1]);
				$this->properties[$key] = $val;
			}
		}
	}
	
	public function set_properties_arr($props)
	{
		$this->properties = $props;
	}
	
	public function get_properties()
	{
		$arr = array();
		foreach ($this->properties as $key => $value)
		{
			$arr[] = htmlentities($key) . '<' . htmlentities($value);
		}
		
		return implode('>', $arr);
	}
	
	public function get_image_count()
	{
		return count($this->images);
	}
	
	public function get_image($index)
	{
		if (isset($this->images[$index]))
			return $this->images[$index];
		return "";
	}
	
	public function set_images($imgs)
	{
		array_splice($this->images, 0);
		
		$str = trim(trim($imgs), '>');
		if (strlen($str) == 0)
			return;
		
		$this->images = explode('>', $str);
		foreach($this->images as &$img)
		{
			$img = html_entity_decode($img);
		}
	}
	
	public function set_images_arr($imgs)
	{
		$this->images = $imgs;
	}
	
	public function get_images_arr()
	{
		return $this->images;
	}
	
	public function get_images()
	{
		$arr = $this->images;
		foreach ($arr as &$img)
		{
			$img = htmlentities($img);
		}
		
		return implode('>', $arr);
	}
	
	public function add_image($img)
	{
		$this->images[] = $img;
	}
	
	public function set_heading_image($img)
	{
		$index = array_search($img, $this->images, true);
		if ($index)
			set_heading_image_index($index);
	}
	
	public function set_heading_image_index($index)
	{
		if (count($this->images) < 2 || $index <= 0 || $index >= count($this->images))
			return;
		
		$save = $this->images[$index];
		for ($i = $index; $i > 0; $i--)
		{
			$this->images[$i] = $this->images[$i - 1];
		}
		$this->images[0] = $save;
	}
	
	public function DrawInTree($level, $checkBox, $sel, $branch)
	{
		if ($level > 0)
		{
			if ($sel & Item::selBold)
				echo '<div class="' . Css::treeLineBold . '"></div>';
			else if ($sel & Item::selSel)
				echo '<div class="' . Css::treeLineSel . '"></div>';
			else
				echo '<div class="' . Css::treeLine . '"></div>';
		}
	
		echo '<table class="' . Css::tableCommon . '" style="width: 100%"><tr>';
		if ($checkBox > 0)
		{
			$c = '<input type="checkbox" id="' . Html::checkBoxes . '" name="' . Html::checkBoxes . '[]" value="' . Catalog::get_cur_id($branch) . '" ';
			if ($checkBox & Item::checkBox)
				$c .= 'checked ';
			if ($checkBox & Item::checkBoxG)
				$c .= 'disabled class="' . Css::checkBoxGrayed . '" ';
			else
				$c .= 'title="' . 'Отметьте галочками один или несколько родительских разделов для текущего' . '" ';
			$c .= '/>';
			echo '<td>' . $c . '</td>';
		}

		$this->DrawLink(basename($_SERVER['PHP_SELF']), $branch, $sel, (($sel & Item::selBold) > 0) && (($sel & Item::selCur) > 0), Item::branchModeTree);
	}
	
	public function Save($oldParents, $newParents)
	{
		global $conn;
		
		$result = false;
		$ret = 0;
		$toDelete = array_values(array_diff($oldParents, $newParents));
		$toAdd = array_values(array_diff($newParents, $oldParents));
		try
		{
			mysqli_autocommit($conn, false);
			
			$realName = mysqli_real_escape_string($conn, $this->name);
			$realDesc = mysqli_real_escape_string($conn, $this->desc);
			$realProps = mysqli_real_escape_string($conn, $this->get_properties());
			$realImages = mysqli_real_escape_string($conn, $this->get_images());
			$sql = "";
			if ($this->id == 0)
				$sql = sprintf("INSERT INTO `Category` (Name, `Type`, `Desc`, Properties, Images) VALUES ('%s', %d, '%s', '%s', '%s')", $realName, $this->type, $realDesc, $realProps, $realImages);
			else
				$sql = sprintf("UPDATE `Category` SET Name = '%s', `Type` = %d, `Desc` = '%s', Properties = '%s', Images = '%s' WHERE ID = " . $this->id, $realName, $this->type, $realDesc, $realProps, $realImages);
			
			$result = mysqli_query($conn, $sql);
			if (!$result)
				throw new Exception(1);
			if ($this->id == 0)
				$this->id = mysqli_insert_id($conn);
			$ret = $this->id;
			if ($result !== true)
				mysqli_free_result($result);
			
			if (count($toDelete) > 0)
			{
				$where = "";
				for ($i = 0; $i < count($toDelete) - 1; $i++)
				{
					$where .= " (ParentID = " . $toDelete[$i] . " AND CatID = " . $this->id . ") OR";
				}
				$where .= " (ParentID = " . $toDelete[count($toDelete) - 1] . " AND CatID = " . $this->id . ")";
				$sql = "DELETE FROM `CatParent` WHERE" . $where;
				if (!mysqli_query($conn, $sql))
					throw new Exception(2);
			}
			
			if (count($toAdd) > 0)
			{
				$values = "";
				for ($i = 0; $i < count($toAdd) - 1; $i++)
				{
					$values .= "(" . $this->id . "," . $toAdd[$i] . "),";
				}
				$values .= "(" . $this->id . "," . $toAdd[count($toAdd) - 1] . ")";
				$sql = "INSERT INTO `CatParent` (CatID, ParentID) VALUES " . $values;
				if (!mysqli_query($conn, $sql))
					throw new Exception(3);
			}
			
			mysqli_commit($conn);
		}
		catch (Exception $e)
		{
			mysqli_rollback($conn);
			$ret = 0;
		}
		
		if ($result && $result !== true)
			mysqli_free_result($result);
		
		mysqli_autocommit($conn, true);
		
		return $ret;
	}
	
	public function Draw($edit)
	{
		if ($edit || $this->type == Item::typeProduct)
		{
			echo '<table class="'. Html::tdProduct . '"><tr class="'. Html::tdProduct . '"><td class="'. Html::tdProduct . '" style="text-align: justify; vertical-align: top;">';
			echo '<div style="float: left; margin-right: 6px;"><table class="'. Css::tableProps . '">';
		}
		
		if ($edit)
		{
			if ($this->get_type() == Item::typeProduct)
			{
				echo '<tr><td class="' . Css::tdPropLabel . '">Название</td><td><textarea id="' . Html::editName . '" name="' . Html::editName . '" rows="3" cols="26" maxlength="200" required>' . htmlentities($this->get_name()) . '</textarea></td></tr>';
			}
			else
			{
				echo '<tr><td class="' . Css::tdPropLabel . '">Название</td><td><input type="text" id="' . Html::editName . '" name="' . Html::editName . '" style="width:230px" maxlength="200" value="' . htmlentities($this->get_name()) . '" required /></td></tr>';
			}
			if ($this->get_type() == Item::typeProduct)
			{
				echo '<tr><td class="' . Css::tdPropLabel . '">'. Item::propPrice . '</td><td><input type="text" id="' . Html::editPrice . '" name="' . Html::editPrice . '" style="width:230px" maxlength="200" value="' . htmlentities($this->get_property(Item::propPrice)) . '" /></td></tr>';
				echo '<tr><td class="' . Css::tdPropLabel . '">'. Item::propAuthor . '</td><td><input type="text" id="' . Html::editAuthor . '" name="' . Html::editAuthor . '" style="width:230px" maxlength="200" value="' . htmlentities($this->get_property(Item::propAuthor)) . '" /></td></tr>';
				echo '<tr><td class="' . Css::tdPropLabel . '">'. Item::propCountry . '</td><td><input type="text" id="' . Html::editCountry . '" name="' . Html::editCountry . '" style="width:230px" maxlength="200" value="' . htmlentities($this->get_property(Item::propCountry)) . '" /></td></tr>';
				echo '<tr><td class="' . Css::tdPropLabel . '">'. Item::propYear . '</td><td><input type="text" id="' . Html::editYear . '" name="' . Html::editYear . '" style="width:230px" maxlength="200" value="' . htmlentities($this->get_property(Item::propYear)) . '" /></td></tr>';
			}
			echo '<tr><td class="' . Css::tdPropLabel . '">Порядок</td><td><input type="text" id="' . Html::editSortOrder . '" name="' . Html::editSortOrder . '" maxlength="10" style="width:40px" value="' . $this->get_sort_order() . '" /></td></tr>';
			if ($this->get_type() == Item::typeProduct || $this->get_type() == Item::typeUndefined || $this->get_type() == Item::typeChapter)
				echo '<tr><td class="' . Css::tdPropLabel . '">Добавить фото</td><td><input type="file" id="' . Html::addImgID . '" name="' . Html::addImgID . '" style="width: 232px" multiple /></td></tr>';
		}
		else
		{
			if ($this->get_type() == Item::typeProduct)
			{
				$style = ' style="max-width: 225px; text-align: justify;"';
				echo '<tr><td class="' . Css::tdPropLabel . '">Название</td><td' . $style . '><b>' . nl2br($this->get_name()) . '</b></td></tr>';
				foreach ($this->properties as $key => $val)
				{
					echo '<tr><td class="' . Css::tdPropLabel . '">' . $key . '</td><td' . $style . '>' . $val . '</td></tr>';
				}
			}
			else
			{
				if (strlen($this->get_desc()) > 0)
				{
					echo '<div class="' . Css::divDesc . '" style="border: 0; margin-top: 0; text-align: justify;">';
					echo nl2br($this->get_desc());
					echo '</div>';
				}
			}
		}
		
		if ($edit || $this->type == Item::typeProduct)
		{
			echo '</table></div>';
			if ($edit)
			{
				$rows = $this->type == Item::typeProduct ? 12 : 5;
				echo '</td><td class="'. Html::tdProduct . '" style="width: 100%">';
				echo '<textarea id="' . Html::editDesc . '" name="' . Html::editDesc . '" rows="' . $rows . '" cols="56" title="Описание">' . htmlentities($this->get_desc()) . '</textarea>';
			}
			else
			{
				echo nl2br($this->get_desc());
			}
			echo '</td></tr></table>';
		}
		
		for ($i = 0; $i < $this->get_image_count(); $i++)
		{
			$path = SystemVars::getImagesPath($this->get_id()) . SystemVars::thumbPrefix . $this->get_image($i);
			if (!file_exists($path))
			{
				if ($this->type == Item::typeProduct)
					Thumbnailer::Create($this->get_image($i), $this->get_id());
				else
					Thumbnailer::Create($this->get_image($i), $this->get_id(), SystemVars::rootImagesWidth, 0);
			}
		}
	}
	
	public function DrawLink($path, $br, $sel, $noact = false, $mode = Item::branchModePlain, $toResponse = true)
	{
		$class = "";
		$style = '';
		if ($mode == Item::branchModeTree || $mode == Item::branchModeItems || $mode == Item::branchMode3D)
		{
			if ($this->get_type() == Item::typeProduct)
				$class = Css::product;
			else if ($this->get_type() == Item::typeChapter)
				$class = Css::chapter;
			else if ($this->get_type() == Item::typeCategory)
				$class = Css::category;
			else  if ($this->get_type() == Item::typeUndefined)
				$class = Css::root;
			
			if ($sel & Item::selCur)
				$class .= ' ' . Css::curr;
			
			if ($this->get_type() == Item::typeCategory || $this->get_type() == Item::typeChapter)
			{
				if ($mode != Item::branchModeItems)
					$style = 'font-size: 15px;';
				else
					$style = 'font-size: 19px; font-weight: bold; text-decoration: none;';
			}
		}
		else if ($mode == Item::branchModePlain)
		{
			$style = 'font-size: 19px;';
		}
		
		if ($sel & Item::selSel)
			$class .= ' ' . Css::selected;
		if ($sel & Item::selBold)
				$class .= ' ' . Css::bold;
		
		$prefix = "";
		if ($this->get_type() == Item::typeCategory || $this->get_type() == Item::typeUndefined)
		{
			if (($sel & Item::selCur || $sel & Item::selBold) && $mode == Item::branchModeTree)
				$prefix = '&#9826;';
			else
				$prefix = '&diams;';
		}
		if ($mode == Item::branchModePlain)
		{
			$prefix = '';
		}
		
		$title = '';
		$prefixStyle = 'display: inline-block;';
		if ($mode == Item::branchMode3D)
		{
			$breaks = array("<br />", "<br>", "<br/>");  
			$title = htmlspecialchars(str_ireplace($breaks, "\r\n", $this->get_name()));
			$style = 'font-size: 27px;';
			$prefixStyle = 'font-size: 23px;';
		}
		else
		{
			if ($mode != Item::branchModeItems && $mode != Item::branchModePlain)
				$prefixStyle .= strpos($prefix, 'diams') > 0 ? 'font-size: 15px;' : 'font-size: 13px;';
			else
				$prefixStyle .= 'font-size: 19px;';
			
			if ($mode == Item::branchModeTree)
				$prefix .= '&nbsp;';
		}
		
		$href = "javascript:void(0);";
		global $catalog;
		$from = '&' . InputVar::from . '=' . $catalog->get_sel_id();
		if (!$noact)
			$href = '/' . $path . '?' . InputVar::branch . '=' . Catalog::branch_to_str($br) . $from;
		else
			$style .= 'text-decoration: none;';
		$a = '<a href="' . $href . '" class="' . ($mode == Item::branchModeTree ? 'treeLink' : $class) . '" style="' . $style . '" title="' . $title . '">' . strip_tags($this->get_name()) . '</a>';
		
		$link = '';
		if ($mode == Item::branchModeTree)
			$link .= '<td>';
		$link .= '<div class="' . $class . '" style="' . $prefixStyle . '">' . $prefix . '</div>';
		if ($mode == Item::branchModeTree)
		{
			$link .= '</td>';
			$link .= '<td width="100%">';
		}
		$link .= '<div class="' . $class . '" style="display: inline-block;">';
		$link .= $a;
		$link .= '</div>';
		if ($mode == Item::branchModeTree)
			$link .= '</td></tr></table>';
		
		if ($toResponse)
			echo $link;

		return $link;
	}
}

class Root extends Item
{
	public function __construct($itemID, $itemName, $itemDesc = "")
	{
		parent::__construct($itemID, $itemName, Item::typeUndefined, $itemDesc);
	}
	
	public function Draw($edit)
	{
		parent::Draw($edit);
	}
	
	public function get_name()
	{
		global $selectMode;
		
		if ($selectMode == Mode3D::mode3d)
			return "Начало";
		return parent::get_name();
	}
}

class Chapter extends Item
{
	public function __construct($itemID, $itemName, $itemDesc = "")
	{
		parent::__construct($itemID, $itemName, Item::typeChapter, $itemDesc);
	}
}

class Category extends Item
{
	public function __construct($itemID, $itemName, $itemDesc = "")
	{
		parent::__construct($itemID, $itemName, Item::typeCategory, $itemDesc);
	}
}

class Product extends Item
{
	public function __construct($itemID, $itemName, $itemDesc = "")
	{
		parent::__construct($itemID, $itemName, Item::typeProduct, $itemDesc);
	}
	
	public function DrawImage($path, $br, $display = 'inline-block', $toResponse = true, $tooltip = false)
	{
		global $catalog;
		$from = '&' . InputVar::from . '=' . $catalog->get_sel_id();
		$href = '/' . $path . '?' . InputVar::branch . '=' . Catalog::branch_to_str($br) . $from;
		$src = $this->get_image_count() > 0 ? SystemVars::getImagesPath($this->get_id()) . SystemVars::thumbPrefix . $this->get_image(0) : '';
		if (strlen($src) > 0 && !file_exists($src))
		{
			if (!Thumbnailer::Create($this->get_image(0), $this->get_id()))
				$src = SystemVars::getImagesPath($this->get_id()) . $this->get_image(0);
		}
		$blank = './' . SystemVars::imagesPath . '/blank.bmp';
		$text = nl2br($this->get_name());
		$title = '';
		if ($tooltip)
		{
			$breaks = array("<br />", "<br>", "<br/>");  
			$title = htmlspecialchars(str_ireplace($breaks, "\r\n", $this->get_name()));
		}
		$a = '<a href="' . $href . '" class="' . Css::product . '">';
		//$a .= '<div class="' . Css::divProduct . '"><input type="hidden" value="' . $src . '" /><img src="' . $blank . '" class="' . Css::imgProduct . '" style="visibility: hidden;" /></div>' .
		$a .= '<div class="' . Css::divProduct . '"><input type="hidden" value="' . $src . '" /><img src="' . $src . '" class="' . Css::imgProduct . '" style="visibility: visible;" /></div>' .
			'<div class="' . Css::divProductText . '" style="height: 36px;">' . $text . '</div>'.
			'<div class="' . Css::divProductText . '" style="position: absolute; left: 0; top: 216px; visibility: hidden; z-index: 1; text-decoration: underline;">' . $text . '</div></a>';
		$res = '<div  class="' . Css::divProductLink . '"style="display:' . $display . '; margin: 0 10px 12px 0;" title="' . $title . '">' . $a . '</div>';
		
		if ($toResponse)
			echo $res;
		
		return $res;
	}
}

class Catalog
{
	//current selection
	private $branch = array();
	private $parents = array();
	private $children = array();
	private $closeParents = array();
	private $altBranches = array();
	
	private $canEdit = false;
	private $root = null;
	
	public static function branch_to_str($arr)
	{
		return implode("-", $arr);
	}
	
	public static function branch_to_arr($str)
	{
		if (strlen(trim($str)) == 0)
			return array();
		return explode("-", $str);
	}
	
	public static function get_cur_id($arr)
	{
		if (count($arr) > 0)
			return $arr[count($arr) - 1];
		return 0;
	}
	
	public static function get_cur_parent($arr)
	{
		if (count($arr) > 1)
			return $arr[count($arr) - 2];
		return 0;
	}
	
	public function __construct($curBranch, $edit)
	{
		global $conn;
		
		$curBranch = Catalog::ValidateBranch($curBranch);
		
		$rootID = $curBranch[0];
		if (!is_null($rootID))
			$this->root = Item::CreateItem($rootID);
			
		$this->canEdit = $edit;
		$this->branch = $curBranch;
		if (count($this->branch) == 0 && !is_null($this->root))
			$this->branch[] = $rootID;
			
		$selID = $this->get_sel_id();
		
		//get parents
		$arr = array($selID);
		$closeParent = true;
		while (count($arr) > 0)
		{
			$where = "";
			for ($i = 0; $i < count($arr) - 1; $i++)
			{
				$where .= " CatID = " . $arr[$i] . " OR";
			}
			$where .= " CatID = " . $arr[count($arr) - 1];
			$sql = "SELECT ParentID FROM `CatParent` WHERE" . $where;
			array_splice($arr, 0);
			$result = mysqli_query($conn, $sql);
			while ($result && $row = mysqli_fetch_assoc($result))
			{
				$id = $row["ParentID"];
				$this->parents[] = $id;
				if ($closeParent)
					$this->closeParents[] = $id;
				$arr[] = $id;
			}
			if ($result && $result !== true)
				mysqli_free_result($result);
			
			$closeParent = false;
		}
		
		//get children
		$arr[] = $selID;
		while (count($arr) > 0)
		{
			$where = "";
			for ($i = 0; $i < count($arr) - 1; $i++)
			{
				$where .= " ParentID = " . $arr[$i] . " OR";
			}
			$where .= " ParentID = " . $arr[count($arr) - 1];
			$sql = "SELECT CatID FROM `CatParent` WHERE" . $where;
			array_splice($arr, 0);
			$result = mysqli_query($conn, $sql);
			while ($result && $row = mysqli_fetch_assoc($result))
			{
				$id = $row["CatID"];
				$this->children[] = $id;
				$arr[] = $id;
			}
			if ($result && $result !== true)
				mysqli_free_result($result);
		}
	}
	
	public function get_branch()
	{
		return $this->branch;
	}
	
	public function get_alt_branches()
	{
		return $this->altBranches;
	}
	
	public function get_sel_id()
	{
		if (count($this->branch) > 0)
			return $this->branch[count($this->branch) - 1];
		
		return 0;
	}
	
	public function DrawTreeNode($item, $level, $curBranch)
	{
		global $conn;
		
		$branchMatch = false;
		for ($i = 0; $i < count($curBranch); $i++)
		{
			if ($i >= count($this->branch) || $curBranch[$i] != $this->branch[$i])
			{
				$branchMatch = false;
				break;
			}
			$branchMatch = true;
		}
		
		$selID = $this->get_sel_id();
		$curID = Catalog::get_cur_id($curBranch);
		
		$isSelected = false;
		if ($selID == $curID && $selID > 0)
		{
			$isSelected = true;
			$this->altBranches[] = $curBranch;
		}
		
		if ($item->get_type() == Item::typeProduct)
			return;
		
		$sel = 0; //not selected
		if ($branchMatch)
		{
			if ($isSelected)
				$sel = Item::selSel | Item::selCur | Item::selBold;
			else
				$sel = Item::selSel | Item::selBold;
		}
		else
		{
			if ($isSelected)
			{
				$sel = Item::selSel | Item::selCur;
			}
			else
			{
				for ($i = 0; $i < count($this->parents); $i++)
				{
					if ($this->parents[$i] == $curID)
					{
						$sel = Item::selSel;
						break;
					}
				}
			}
		}
		
		$check = 0; //no checkbox
		if ($this->canEdit)
		{
			for ($i = 0; $i < count($this->closeParents); $i++)
			{
				if ($this->closeParents[$i] == $curID)
				{
					$check = Item::checkBox;
					break;
				}
			}
			
			if ($check == 0)
			{
				for ($i = 0; $i < count($this->children); $i++)
				{
					if ($this->children[$i] == $curID)
					{
						$check = Item::checkBoxG | Item::checkBoxU;
						break;
					}
				}
			}
			
			if ($check == 0)
			{
				if ($curID != $selID)
					$check = Item::checkBoxU;
				else
					$check = Item::checkBoxG;
			}
		}
		
		$styleF = ' style="%s"';
		$style = "";
		if ($level == 0)
			$style .= 'padding-left: 5px;';
		$colorStart = "#1B1917";
		$colorEnd = "#483F36";
		$style .= "background-color: " . Catalog::GetColor($colorStart, $colorEnd, $level);
		echo('<div class="' . Css::divTreeItem . '"' . sprintf($styleF, $style) . '>');
		
		//draw node content
		$item->DrawInTree($level, $check, $sel, $curBranch);
		
		//draw childs
		$sql = "SELECT cp.CatID, cp.ParentID, cp.SortOrder, c.ID, c.Type, c.Name, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.CatID = c.ID WHERE cp.ParentID = " . $item->get_id() .
			" ORDER BY CASE WHEN c.Type = " . Item::typeProduct . " THEN c.Type ELSE 0 END DESC, cp.SortOrder ASC, c.Added DESC";
		$result = mysqli_query($conn, $sql);
		while ($result && $row = mysqli_fetch_assoc($result))
		{
			$id = $row["CatID"];
			$type = $row["Type"];
			$name = $row["Name"];
			if ($type == Item::typeChapter)
				$child = new Chapter($id, $name);
			else if ($type == Item::typeCategory)
				$child = new Category($id, $name);
			else
				$child = new Product($id, $name);
			
			$br = $curBranch;
			$br[] =  $id;
			$this->DrawTreeNode($child, $level + 1, $br);
		}
		if ($result && $result !== true)
			mysqli_free_result($result);
		
		echo('</div>');
	}
	
	public function DrawTree()
	{
		global $conn; 

		if (is_null($this->root))
			return;
		
		$curBranch = array($this->root->get_id());
		$this->DrawTreeNode($this->root, 0, $curBranch);
	}
	
	public function DrawAltBranches()
	{
		for ($i = 0; $i < count($this->altBranches); $i++)
		{
			echo "<div>";
			$this->DrawBranch($i);
			echo "</div>";
		}
	}
	
	public function DrawBranch($alt = -1)
	{
		$br = $alt < 0 ? $this->branch : $this->altBranches[$alt];
		
		if ($alt < 0)
			echo '<div class="' . Css::branchSel . '">';
		
		for ($i = 0; $i < count($br); $i++)
		{
			$item = Item::CreateItem($br[$i]);
			
			if ($i < count($br) - 1 || $alt < 0)
			{
				$sel = 0;
				if ($alt < 0)
				{
					$sel = Item::selSel | Item::selBold;
					if ($i == count($br) - 1)
						$sel |= Item::selCur;
				}
				$arrow = $i == 0 ? "" : " &gt; ";
				echo($arrow);
				
				$item->DrawLink(basename($_SERVER['PHP_SELF']), array_slice($br, 0, $i + 1), $sel, $i == count($br) - 1);
			}
		}
		
		if ($alt < 0)
			echo '</div>';
	}
	
	public function Draw($parentID = 0, $br = null, $limitProd = 0, $limitCatChap = 0, $limitNest = 0, $levelNest = 0)
	{
		global $conn;
		
		if ($limitNest > 0 && $levelNest >= $limitNest)
			return;
		
		$styleF = ' style="%s"';
		$style = "";
		if ($levelNest == 0)
			$style .= 'padding-left: 2px;';
		$colorStart = "#170201";
		$colorEnd = "#42302F";
		$style .= "background-color: " . Catalog::GetColor($colorStart, $colorEnd, $levelNest);
		echo('<div class="' . Css::divTreeItem2 . '"' . sprintf($styleF, $style) . '>');
		
		if ($parentID == 0)
			$parentID = $this->get_sel_id();
		if (is_null($br))
			$br = $this->branch;
		
		$sql = "SELECT cp.CatID, cp.ParentID, cp.SortOrder, c.ID, c.Type, c.Name, c.Images, c.Added FROM `CatParent` cp INNER JOIN `Category` c ON cp.CatID = c.ID WHERE cp.ParentID = " . $parentID .
			" ORDER BY CASE WHEN c.Type = " . Item::typeProduct . " THEN c.Type ELSE 0 END DESC, cp.SortOrder ASC, c.Added DESC";
		$result = mysqli_query($conn, $sql);
		$countProd = 0;
		$countCatChap = 0;
		$more = false;
		while ($result && $row = mysqli_fetch_assoc($result))
		{
			$itemType = $row["Type"];
			if ($itemType == Item::typeProduct)
			{
				$countProd++;
				if ($levelNest > 0 && $limitProd > 0 && $countProd > $limitProd)
					continue;
			}
			else
			{
				$countCatChap++;
				if ($limitCatChap > 0 && $countCatChap > $limitCatChap)
					continue;
			}
			$itemID = $row["CatID"];
			$itemName = $row["Name"];
			$item = Item::CreateItemEx($itemID, $itemName, $itemType);
			$item->set_images($row["Images"]);
			
			if ($levelNest > 0 && $countProd > 0 && $itemType != Item::typeProduct && !$more)
			{
				$more = true;
				$this->DrawLinkMore(basename($_SERVER['PHP_SELF']), $countProd, $br);
			}
			
			$br1 = $br;
			$br1[] =  $itemID;
			if ($itemType == Item::typeProduct)
			{
				$item->DrawImage(basename($_SERVER['PHP_SELF']), $br1, $countProd > 0 ? 'none' : 'inline-block');
			}
			else
			{
				echo '<div style="background-color: #2f0202;">';
				$item->DrawLink(basename($_SERVER['PHP_SELF']), $br1, 0, false, Item::branchModeItems);
				echo '</div>';
			}
			
			if ($itemType != Item::typeProduct)
				$this->Draw($itemID, $br1, $limitProd, $limitCatChap, $limitNest, $levelNest + 1);
		}	
		if ($result && $result !== true)
			mysqli_free_result($result);
		
		if ($levelNest > 0 && $countProd > 0 && !$more)
			$this->DrawLinkMore(basename($_SERVER['PHP_SELF']), $countProd, $br);
		
		echo '</div>';
	}
	
	private function DrawLinkMore($fname, $count, $br)
	{
		$text = '>>> смотреть все ' . $count . ' шт.';
		$href = '/' . $fname . '?' . InputVar::branch . '=' . Catalog::branch_to_str($br);
		$a = '<a href="' . $href . '" class="linkMore">' . $text . '</a>';
		
		echo '<div style="padding-bottom: 4px;">' . $a . '</div>';
	}
	
	public static function Delete($itemID)
	{
		global $conn;
		
		if (Catalog::HasChildren($itemID))
			return false;
		
		$ret = true;
		try
		{
			mysqli_autocommit($conn, false);
			
			$sql = "DELETE FROM `CatParent` WHERE CatID = " . $itemID;
			if (!mysqli_query($conn, $sql))
				throw new Exception(1);
			
			$result = false;
			$sql = "DELETE FROM `Category` WHERE ID = " . $itemID;
			if (!mysqli_query($conn, $sql))
				throw new Exception(2);
			
			mysqli_commit($conn);
		}
		catch (Exception $e)
		{
			mysqli_rollback($conn);
			$ret = false;
		}
		
		mysqli_autocommit($conn, true);
		
		if ($ret && file_exists(SystemVars::getImagesPath($itemID)))
		{
			$arrImgFS = scandir(SystemVars::getImagesPath($itemID));
			foreach ($arrImgFS as $img)
			{
				$fpath = SystemVars::getImagesPath($itemID) . $img;
				if (is_dir($fpath))
					continue;
				
				//@touch($fpath);
				@unlink($fpath);
			}
			@rmdir(SystemVars::getImagesPath($itemID));
	}
		
		return $ret;
	}
	
	public static function Save($item, $newParents)
	{
		global $conn;
		
		$result = false;
		$oldParents = array();
		if ($item->get_id() > 0)
		{
			$sql = "SELECT ParentID FROM `CatParent` WHERE CatID = " . $item->get_id();
			$result = mysqli_query($conn, $sql);
			while ($result && $row = mysqli_fetch_assoc($result))
			{
				$oldParents[] = $row["ParentID"];
			}
		}
		if ($result && $result !== true)
			mysqli_free_result($result);
		
		$res = $item->Save($oldParents, $newParents);
		
		return $res;
	}
	
	public static function SaveSortOrder($sort, $itemID, $parentID)
	{
		global $conn;
		
		$sql = sprintf("UPDATE `CatParent` SET SortOrder = %d WHERE CatID = %d AND ParentID = %d", $sort, $itemID, $parentID);
		try
		{
			mysqli_query($conn, $sql);
		}
		catch (Exception $e)
		{
		}
	}
	
	public static function HasChildren($itemID)
	{
		global $conn;
		
		$sql = "SELECT CatID FROM `CatParent` WHERE ParentID = " . $itemID;
		$result = mysqli_query($conn, $sql);
		if (!$result)
			return true;
		$ret = false;
		if (mysqli_num_rows($result) > 0)
			$ret = true;
		if ($result !== true)
			mysqli_free_result($result);
		return $ret;
	}
	
	static function hex2rgb($hex)
	{
		$hex = str_replace("#", "", $hex);
		$r = 0;
		$g = 0;
		$b = 0;
		if (strlen($hex) == 3)
		{
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		}
		else if (strlen($hex) >= 6)
		{
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb; // returns an array with the rgb values
	}
	
	static function rgb2hex($rgb)
	{
		$hex = "#";
		$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

		return $hex; // returns the hex value including the number sign (#)
	}
	
	public static function GetColor($colorStart, $colorEnd, $step, $steps = 5)
	{
		$start = Catalog::hex2rgb($colorStart);
		$end = Catalog::hex2rgb($colorEnd);
		$step %= $steps;
		$ret = array($start[0] + $step * ($end[0] - $start[0]) / $steps, $start[1] + $step * ($end[1] - $start[1]) / $steps, $start[2] + $step * ($end[2] - $start[2]) / $steps);
		
		return Catalog::rgb2hex($ret);
	}
	
	public static function IsBranchValid($br)
	{
		if (count($br) == 0)
			return false;
		
		$rootID = $br[0];
		$val = db_get_value('Category', 'ID', 'WHERE `Type` = ' . Item::typeUndefined);
		if ($rootID != $val)
			return false;
		
		if (count($br) == 1)
			return true;
	
		for ($i = count($br) - 1; $i > 0; $i--)
		{
			$val = db_get_value('CatParent', 'CatID', 'WHERE CatID = ' . $br[$i] . ' AND ParentID = ' . $br[$i - 1]);
			if (is_null($val))
				return false;
		}
		
		return true;
	}
	
	public static function ValidateBranch($br)
	{
		if (Catalog::IsBranchValid($br))
			return $br;
		
		$rootID = db_get_value('Category', 'ID', 'WHERE `Type` = ' . Item::typeUndefined);
		if (is_null($rootID))
			return array();
		
		if (count($br) <= 1)
			return array($rootID);
		
		$id = $br[count($br) - 1];
		$brValid = array($id);
		while ($id != $rootID)
		{
			$id = db_get_value('CatParent', 'ParentID', 'WHERE CatID = ' . $id);
			if (is_null($id))
				return array($rootID);
			$brValid[] = $id;
		}
		
		return array_reverse($brValid);
	}
	
	public function DrawLinkBack($path, $type = Item::typeProduct)
	{
		$back = array_slice($this->get_branch(), 0, -1);
		$from = '&' . InputVar::from . '=' . $this->get_sel_id();
		$href = '/' . $path . '?' . InputVar::branch . '=' . Catalog::branch_to_str($back) . $from;
		$text = $type == Item::typeProduct ? '&#8592; в раздел' : '&#8593; вверх';
		echo '<div><div style="margin: 2px 2px 6px 2px; background-color: black; display: inline-block;"><a id="linkBackNot3D" style="font-size: 19px; font-weight: bold;" href="' . $href . '">' . $text . '</a></div></div>';
	}
}

class Thumbnailer
{
	static public function Create($fname, $id, $thumbW = SystemVars::thumbImagesWidth, $thumbH = SystemVars::thumbImagesHeight)
	{
		$path = SystemVars::getImagesPath($id);
		$thname = SystemVars::thumbPrefix . $fname;
		if (file_exists($path . $thname))
			return true;
		if (!file_exists($path . $fname))
			return false;
		try
		{
			$img = WideImage::load($path . $fname);
			$w = $img->getWidth();
			$h = $img->getHeight();
			if ($thumbH <= 0)
				$thumbH = $thumbW * $h / $w;
			if ($thumbW <= 0)
				$thumbW = $thumbH * $w / $h;
			if ($w != $thumbW || $h != $thumbH)
			{
				$img = $img->resize($thumbW, $thumbH, 'inside');
				$color = $img->allocateColor(0x17, 0x00, 0x00);
				$img = $img->resizeCanvas($thumbW, $thumbH, 'center', 'center', $color);
				$img->saveToFile($path . $thname);
			}
			$img->destroy();
			return true;
		}
		catch (Exception $e)
		{
			@unlink($path . $thname);
			return false;
		}
	}
}

?>
