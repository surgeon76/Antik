<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
require_once 'auth.php';
require_once 'localstorage.php';
require_once 'imgmgr.php';
require_once 'analytics.php';
require_once 'mode3d.php';

$edit = isset($_SESSION[InputVar::admin]);

$conn = db_connect();

$branch = Catalog::branch_to_arr("" . $_GET[InputVar::branch]);
$catalog = new Catalog($branch, $edit);
$branch = Catalog::branch_to_str($catalog->get_branch());

$itemName = "";

$id = $catalog->get_sel_id();
$item = null;
if ($id > 0)
{
	$item = Item::CreateItem($id);
	$itemName = htmlentities(str_replace("\r\n", " ", strip_tags($item->get_name())));
}

$title = $itemName . " - Антикварный салон - Крым, г. Симферополь, ул. Толстого 9 - антиквариат Крым, антикварный магазин Симферополь";
$description = "Официальный веб сайт Антикварного Салона, Крым, г. Симферополь, ул. Толстого 9, " . $itemName;
$keywords = "антиквариат крым, антикварный магазин симферополь, антикварный, салон, магазин, лавка, симферополь, крым, толстого, антиквар, антикварная, " . $itemName;

?>

<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>"> 
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="robots" content="all"> 
<meta name="revisit-after" content="1 week"> 
<meta name="author" content="Vyacheslav Subbotin"> 

<link rel="stylesheet" href="antiqua.css">
<link rel="icon" type="image/png" href="./images/favicon3.png" />
<script type="text/javascript" src="jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="jquery.inview.min.js"></script>
<script type="text/javascript" src="localstorage.js"></script>
<script type="text/javascript" src="imgmgr.js"></script>
<script type="text/javascript" src="mode3d.js"></script>
<script type="text/javascript" src="jquery.color.js"></script>
</head>
<body style="position: relative;">
<form action="save.php" method="POST">

<script type="text/javascript">
function getObjWidth(obj, w, p, b, m)
{
	w = w || true;
	p = p || true;
	b = b || true;
	m = m || true;
	
	var style = obj.currentStyle || window.getComputedStyle(obj),
	width = obj.offsetWidth,
	margin = parseFloat(style.marginLeft) + parseFloat(style.marginRight),
	padding = parseFloat(style.paddingLeft) + parseFloat(style.paddingRight),
	border = parseFloat(style.borderLeftWidth) + parseFloat(style.borderRightWidth);
	
	var res = 0;
	if (w)
		res += width;
	if (p)
		res += padding;
	if (b)
		res += border;
	if (m)
		res += margin;
}

function getObjHeight(obj, h, p, b, m)
{
	h = h || true;
	p = p || true;
	b = b || true;
	m = m || true;
	
	var style = obj.currentStyle || window.getComputedStyle(obj),
	height = obj.offsetHeight,
	margin = parseFloat(style.marginTop) + parseFloat(style.marginBottom),
	padding = parseFloat(style.paddingTop) + parseFloat(style.paddingBottom),
	border = parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);
	
	var res = 0;
	if (h)
		res += height;
	if (p)
		res += padding;
	if (b)
		res += border;
	if (m)
		res += margin;
}

function OnNew(e)
{
	if (!formHasChanged || confirm('Данные текущей записи изменены и не сохранены. Продолжение выполнения выбранного действия без предшествующей операции сохранения приведет к потере внесенных изменений. Все равно продолжить?'))
	{
		formHasChanged = false;
		e.submit();
		return true;
	}
	else
	{
		document.getElementById('<?php echo Html::selectNew ?>').selectedIndex = 0;
		return false;
	}
}

function ModeChanged(e)
{
	var selMode = document.getElementById('<?php echo Html::selectMode ?>').value;
	if (parseInt(selMode) > 0)
	{
		var div = document.createElement('div');
		if (!('transformStyle' in div.style))
		{
			alert('Ваш браузер не поддерживает данный режим.');
			document.getElementById('<?php echo Html::selectMode ?>').value = 0;
			return false;
		}
	}
	$.ajax({
		url: 'mode.php?' + '<?php echo InputVar::selectMode ?>' + '=' + selMode,
		type: 'GET',
		success: function(data, textStatus, jqXHR)
		{
			if (typeof data.error === 'undefined')
			{
				window.location.reload();
			}
		}
	});
}

function is3DMode()
{
	return document.getElementById('<?php echo InputVar::selectMode ?>') ? true : false;
}

var loadCover = null;
function LoadCover(on)
{
	if (on)
	{
		loadCover = document.createElement('div');
		$(loadCover).css('left', '0').css('top', '0').css('width', window.innerWidth).css('height', window.innerHeight).
			css('z-index', '10').css('opacity', '1').css('background-color', '#170201').css('position', 'absolute').
			css('user-select', 'none');
		document.body.appendChild(loadCover);
	}
	else
	{
		if (loadCover)
		{
			$(loadCover).remove();
			loadCover = null;
		}
	}
}

</script>

<?php
$selectMode = Mode3D::modeClassic;
$selMode = '';
if (!$edit)
{
	if (isset($_SESSION[InputVar::selectMode]))
		$selectMode = $_SESSION[InputVar::selectMode];

	$selMode = '<div style="position: absolute; top: 2px; left: 4px; bottom: 2px;">
	<select id="' . Html::selectMode . '" name="' . Html::selectMode . '" onchange="ModeChanged();">
	<option value="' . Mode3D::modeClassic . '" ' . ($selectMode == Mode3D::modeClassic ? "selected" : "") . '>Классический вид</option>
	<option value="' . Mode3D::mode3d . '" ' . ($selectMode == Mode3D::mode3d ? "selected" : "") . '>Прогулка по галерее</option>
	</select>
	</div>';
}

echo "<script>LoadCover(true);</script>";

DrawHeader($edit, $selMode);

echo '<table class="' . Css::tableCat . '"><tr><td class="' . Css::tdTree . '" id="' . Css::tdTree . '">'; //tree cell

echo '<div>';
$catalog->DrawTree();
echo '</div>';

echo '<div style="height: 55px">';

echo '<div style="display: inline-block; position: absolute; left: 2px; bottom: 36px; z-index: 1">';
echo $analyticsCodeLiveInternet;
echo '</div>';

echo '<div style="display: inline-block; position: absolute; left: 2px; bottom: 2px;">';
echo $analyticsCodeYandex;
echo '</div>';

echo '<div style="display: inline-block; position: absolute; left: 86px; bottom: 2px;">';
echo $analyticsCodeGoogle;
echo '</div>';

echo '</td><td class="' . Css::tdItem . '" id="' . Css::tdItem . '">'; //item cell

if ($id > 0 && !is_null($item))
{
	$parentID = count($catalog->get_branch()) > 1 ? $catalog->get_branch()[count($catalog->get_branch()) - 2] : 0;
	if ($parentID > 0)
		$item->set_sort_order($parentID);
	
	echo '<input type="hidden" id="' . Html::hidID . '" name="' . Html::hidID . '" value="'. $id . '" />';
	if ($edit)
		echo '<input type="hidden" id="' . Html::hidAdminMode . '" name="' . Html::hidAdminMode . '" value="'. $edit . '" />';
	
	if ($item->get_type() == Item::typeUndefined || $item->get_type() == Item::typeChapter)
		echo '<input type="hidden" id="' . Html::rootImagesWidthID . '" name="' . Html::rootImagesWidthID . '" value="'. SystemVars::rootImagesWidth . '" />';
	
	echo '<div style="position: relative; background-color: #5F1904; height: 23px; width: 100%">';
	echo '<div style="position: absolute; display: inline-block; left: 2px; overflow: hidden; white-space: nowrap;">';
	$catalog->DrawBranch();
	echo '</div>';
	echo '</div>';
	
	echo '<div style="margin: 2px; position: relative;">';
	
	$modeWalk = new Mode($catalog);
	if (!$edit && $selectMode == Mode3D::mode3d)
	{
		$from = null;
		if (isset($_GET[InputVar::from]))
			$from = Item::CreateItem($_GET[InputVar::from]);
		printf("<script>AnimateMoveCover(true, '%s', '1'); AnimateMoveCoverFade(false);</script>", (($from && $from->get_type() == Item::typeProduct) || $item->get_type() == Item::typeProduct) ? 'black' : 'white');
		echo "<script>LoadCover(false);</script>";
	}
	
	if (!$edit && $selectMode == Mode3D::mode3d && $item->get_type() != Item::typeProduct)
	{
		$from = 0;
		if (isset($_GET[InputVar::from]))
			$from = $_GET[InputVar::from];
		echo '<input type="hidden" id="' . Html::selectModeHid . '" name="' . Html::selectModeHid . '" value="' . $from . '" />';
		$modeWalk->Draw();
	}
	else
	{
		if ($edit || $item->get_type() == Item::typeProduct)
			echo '<div class="' . Css::divProps . '">';
		
		if ($selectMode == Mode3D::mode3d)
		{
			if (!$edit)
				$modeWalk->DrawLinkBack(basename($_SERVER['PHP_SELF']));
		}
		else if (!$edit && ($item->get_type() == Item::typeProduct || $item->get_type() == Item::typeCategory))
		{
			$catalog->DrawLinkBack(basename($_SERVER['PHP_SELF']), $item->get_type());
		}
		
		$item->Draw($edit);
		
		if ($edit)
		{
			echo '<div class="' . Css::divDesc . '">';
			echo '<input type="hidden" id="' . Html::hidBranch . '" name="' . Html::hidBranch . '" value="'. $branch . '" />';
			echo '<input type="hidden" id="' . Html::hidType . '" name="' . Html::hidType . '" value="'. $item->get_type() . '" />';
			echo '<input type="submit" id="' . Html::buttonSave . '" value="Сохранить" />&nbsp;';
			
			$clickCancel = "location.reload();";
			$branchArr = $catalog->get_branch();
			if (count($branchArr) > 1)
			{
				array_splice($branchArr, -1, 1);
				$clickCancel = "window.location.href='" . basename($_SERVER['PHP_SELF']) . "?" . InputVar::branch . "=" . Catalog::branch_to_str($branchArr) . "';";
			}
			echo '<input type="button" onclick="' . $clickCancel . '" value="Отмена" />&nbsp;';
		
			if ($item->get_type() != Item::typeUndefined && !Catalog::HasChildren($item->get_id()))
				echo '<a href="delete.php?' . InputVar::branch . '=' . $branch . '" onclick="if (confirm(\'Запись будет удалена.\')){formHasChanged = false; return true;} return false;">Удалить</a>&nbsp;';
			if ($item->get_type() != Item::typeProduct)
			{
				echo '<select id="' . Html::selectNew . '" name="' . Html::selectNew . '" onchange="return OnNew(this.form);">
	<option value="0" selected>&lt;Новый&gt;</option>
	<option value="' . Item::typeCategory . '">Раздел</option>
	<option value="' . Item::typeProduct . '">Товар</option>
	</select>';
			}
			echo '</div>';
		}
		
		if ($edit || $item->get_type() == Item::typeProduct)
			echo '</div>';
			
		if ($item->get_type() == Item::typeProduct || $item->get_type() == Item::typeUndefined || $item->get_type() == Item::typeChapter)
		{
			echo '<input type="hidden" id="' . Html::hidImagesID . '" name="' . Html::hidImagesID . '" value="'. $item->get_images() . '" />';
			$style = '';
			if (($item->get_type() == Item::typeUndefined || $item->get_type() == Item::typeChapter) && !$edit)
				$style = 'overflow: hidden; white-space: nowrap;';
			echo '<div><div id="' . Html::divThumbsID . '" style="' . $style . '"></div></div>';
			//echo '<div style="background-color: #531401; height: 20px; margin-top: 1px;"></div>';
		}
		
		echo '<div id="' . Html::divItemsContainer . '">';
		$catalog->Draw();
		echo '</div>';
		
		echo '<div style="padding: 2px">';
		echo 'См. также:<br />';
		$catalog->DrawAltBranches();
		echo '</div>';
	}
	
	echo GetKeyPhrases();
	
	echo '</div>';
}
else
{
	echo 'Раздел не найден';
}

echo '</td></tr></table>';

DrawFooter();

echo "<script>LoadCover(false);</script>";

db_close();

if ($edit && isset($_SESSION[InputVar::error]))
{
	echo '<script>alert("Во время выполнения последней операции произошла непредвиденная ошибка.");</script>';
	unset($_SESSION[InputVar::error]);
}

?>

<script>
var formHasChanged = false;
var submitted = false;

var divWait = 'divWait';
var uploadingID = 'uploading';
var uploadingWH = 75;
function Wait(wait)
{
	if (wait)
	{
		$('body').css('overflow', 'hidden');
		
		var shadow = document.createElement('div');
		shadow.id = divWait;
		$(shadow).css('left', document.body.scrollLeft).css('top', document.body.scrollTop).css('width', window.innerWidth).css('height', window.innerHeight);
		document.body.appendChild(shadow);
		
		var uploading = document.createElement('div');
		uploading.id = uploadingID;
		$(uploading).css('position', 'absolute').css('z-index', 11).css('cursor', 'wait').
			css('width', uploadingWH).css('height', uploadingWH).css('display', 'inline-block').
			css('left', document.body.scrollLeft + window.innerWidth / 2 - uploadingWH / 2).css('top', document.body.scrollTop + window.innerHeight / 2 - uploadingWH / 2).
			css('background-image', 'url(./' + imgPath + '/loading.gif)').css('background-size', '' + uploadingWH + 'px ' + uploadingWH + 'px');
		document.body.appendChild(uploading);
	}
	else
	{
		$('#' + uploadingID).remove();
		$('#' + divWait).remove();
		
		$('body').css('overflow', 'auto');
	}
}

function waitResize()
{
	$('#' + uploadingID).css('width', uploadingWH).css('height', uploadingWH).
		css('left', document.body.scrollLeft + window.innerWidth / 2 - uploadingWH / 2).css('top', document.body.scrollTop + window.innerHeight / 2 - uploadingWH / 2);
	$('#' + divWait).css('width', window.innerWidth).css('height', window.innerHeight).css('left', document.body.scrollLeft).css('top', document.body.scrollTop);
}

function set_modified()
{
	if (!formHasChanged && document.getElementById('<?php echo Html::buttonSave ?>'))
	{
		formHasChanged = true;
		document.getElementById('<?php echo Html::buttonSave ?>').value = "*" + document.getElementById('<?php echo Html::buttonSave ?>').value;
		document.getElementById('<?php echo Html::buttonSave ?>').style.fontWeight = "bold";
	}
}

function ls_autosave(id, inputs, interval)
{
	setInterval(function(){if (formHasChanged){ls_save(id, inputs); ls_set_modified(id, true);}}, interval);
}

function IsInViewport(item)
{
	var rect = item.getBoundingClientRect();
	var w = window.innerWidth;
	var h = window.innerHeight;
	//alert (rect.left + ' ' + rect.top + ' ' + rect.right + ' ' + rect.bottom + ' ' + w + ' ' + h);
	if (((rect.left >= 0 && rect.left < w) || (rect.right > 0 && rect.right <= w) || (0 >= rect.left && 0 < rect.right) || (w > rect.left && w <= rect.right)) &&
		((rect.top >= 0 && rect.top < h) || (rect.bottom > 0 && rect.bottom <= h) || (0 >= rect.top && 0 < rect.bottom) || (h > rect.top && h <= rect.bottom)))
		return true;
	return false;
}

function UpdateItemsTree(parent, nest)
{
	if (!parent)
		parent = document.getElementById('<?php echo Html::divItemsContainer ?>');
	if (!parent)
		return;
	if (!nest && nest !== 0)
		nest = -1;

	var parentStyle = parent.currentStyle || window.getComputedStyle(parent);
	var nodeWidth = parent.offsetWidth - (parseFloat(parentStyle.paddingLeft) + parseFloat(parentStyle.paddingRight));
	var divWidth = thumbImagesWidth + 26;
	var curWidth = 0;
	var level = 0;
	var maxLevel = 2;
	var display = 'inline-block';
	for (var j = 0; j < parent.children.length; j++)
	{
		var div = parent.children[j];
		if (div.className == '<?php echo Css::divTreeItem2 ?>')
		{
			UpdateItemsTree(div, nest + 1);
			continue;
		}
		if (div.className != '<?php echo Css::divProductLink ?>')
			continue;
		
		if (j == 0)
		{
			div.style.display = 'inline-block';
			var divStyle = div.currentStyle || window.getComputedStyle(div);
			divWidth = div.offsetWidth + (parseFloat(divStyle.marginLeft) + parseFloat(divStyle.marginRight));
		}
		curWidth += divWidth;
		if (curWidth > nodeWidth)
		{
			level++;
			if (nest > 0 && level > maxLevel)
				display = 'none';
			curWidth = divWidth;
		}
		div.style.display = display;
		
		if (display != 'none' && IsInViewport(div))
		{
			var img = $(div).find('.' + '<?php echo Css::imgProduct ?>')[0];
			var src = $(div).find('input[type=hidden]')[0].value;
			ShowThumb(img, src);
		}
	}
}

function ShowThumb(img, src)
{
	if (img.style.visibility != 'visible')
	{
		$(img)
			.css('opacity', 0)
			.load(function() { $(img).animate({ opacity: 1 }, 500); });
		img.src = src;
		img.style.visibility = 'visible';
	}
}

function root_resize()
{
	if (!document.getElementById(hidAdminMode) && document.getElementById(rootImagesWidthID) && !isWalkMode())
	{
		var thmbs = $('#' + divThumbsID);
		thmbs.css('max-width', 0);
		thmbs.css('max-width', $('.' + '<?php echo Css::tdItem ?>')[0].offsetWidth - 4);
	}
}

function DrawTreeLine(i, v)
{
	var parent = v.parentNode.parentNode;
	var first = parent.children[0];
	var next = v.nextSibling;
	
	v.style.left = 5;
	v.style.right = next.offsetWidth + 1;
	
	v.style.top = (first.offsetTop + first.offsetHeight) - v.parentNode.offsetTop + (first.tagName == 'TABLE' ? 0 : 8);
	v.style.bottom = v.parentNode.offsetHeight - 14;
}

function DrawTreeLines()
{
	$('.<?php echo Css::treeLine ?>, .<?php echo Css::treeLineSel ?>, .<?php echo Css::treeLineBold ?>').each(function(i, v){
		DrawTreeLine(i, v);
	});
}

$(window).load(function(){ //load complete
	
$(document).on('input change paste', 'form input, form textarea', 
function(e)
{
   	set_modified();
});

DrawTreeLines();
ResizeAnimCover();

});

$(document).ready(function(){ //doc ready

root_resize();
UpdateItemsTree();
DrawTreeLines();
ResizeAnimCover();

$(window).resize(function() {
	UpdateItemsTree();
	waitResize();
	root_resize();
	DrawTreeLines();
	ResizeAnimCover();
});

// $('body').on('inview', '.' + '<?php echo Css::imgProduct ?>', function(event, isVisible) {
	// if (!isVisible)
		// return;
	
	// var topParent = event.target;
	// while (topParent && topParent.className != '<?php echo Css::divProductLink ?>')
	// {
		// topParent = topParent.parentNode;
	// }
	// if (!topParent || topParent.style.display == 'none')
		// return;
	
	// var parent = event.target.parentNode;
	// var src = parent.children[0].value;
	// var img = parent.children[1];
	// ShowThumb(img, src);
// });

$('input[type=file]').on('change', upload_files);
function upload_files(event)
{
	Wait(true);
	
	var files;
	files = event.target.files;
	var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

	var root = document.getElementById(rootImagesWidthID) ? '&root=' + rootImagesWidth : '';
    $.ajax({
        url: 'upload.php?id=' + document.getElementById('<?php echo Html::hidID ?>').value + root,
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            if (typeof data.error === 'undefined')
            {
				Wait(false);
				
				if (data.files)
				{
					//files uploaded
					var strImgs = '';
					$.each(data.files, function(key, value)
					{
						strImgs += (strImgs.length > 0 ? '>' : '') + $('<textarea />').text(value).html();
					});
					im_add(strImgs);
				}
				else
				{
					alert('Ошибка выгрузки');
				}
            }
            else
            {
				Wait(false);
				
                // Handle errors here
				alert('Ошибка выгрузки');
                console.log('ERRORS: ' + data.error);
            }
			event.target.value = "";
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
			Wait(false);
			
            // Handle errors here
			alert('Ошибка выгрузки');
            console.log('ERRORS: ' + textStatus);
			event.target.value = "";
        }
    });
}

$("form").submit(function(e)
{
	if (document.getElementById('<?php echo Html::editName ?>'))
	{
		var s = "" + document.getElementById('<?php echo Html::editName ?>').value;
		if (s.trim().length == 0)
		{
			alert('Наименование не может быть пустым.');
			document.getElementById('<?php echo Html::editName ?>').focus();
			e.preventDefault();
			return false;
		}
	}
	
     submitted = true;
     if (formHasChanged)
     {
     	if (ls_supported() && document.getElementById('<?php echo Html::hidID ?>') && document.getElementById('<?php echo Html::hidAdminMode ?>'))
     	{
			ls_save(document.getElementById('<?php echo Html::hidID ?>').value, lsInputArr);
			ls_set_modified(document.getElementById('<?php echo Html::hidID ?>').value, true);
    	}
     }
     
     $("<?php echo Html::addImgID ?>").replaceWith($("<?php echo Html::addImgID ?>").clone(true));
});

window.onbeforeunload = function(e)
{
	if (!formHasChanged || submitted)
		return;
	
    e = e || window.event;

	var msg = 'Данные текущей записи изменены и не сохранены. Уход с текущей страницы, ее закрытие или обновление без предшествующей операции сохранения приведет к потере внесенных изменений. Все равно продолжить?';
    // For IE and Firefox prior to version 4
    if (e)
        e.returnValue = msg;

    // For Safari
    return msg;
};

window.onunload = function()
{
	if (!submitted)
	{
		if (ls_supported() && document.getElementById('<?php echo Html::hidID ?>') && document.getElementById('<?php echo Html::hidAdminMode ?>'))
		{
			ls_clear(document.getElementById('<?php echo Html::hidID ?>').value, lsInputArr);
	   		ls_set_modified(document.getElementById('<?php echo Html::hidID ?>').value, false);
   		}
   	}
};

if (document.getElementById('<?php echo Html::hidImagesID ?>'))
{
	im_load();
	im_animate_root(isWalkMode() ? 0 : 1, true, false);
}

if (ls_supported() && document.getElementById('<?php echo Html::hidID ?>') && document.getElementById('<?php echo Html::hidAdminMode ?>'))
{
	if (ls_is_modified(document.getElementById('<?php echo Html::hidID ?>').value) &&
		confirm('Похоже, в прошлый раз данные не были сохранены в базе данных по причине непредвиденной ошибки. Попробовать восстановить из автосохранения?'))
	{
		ls_load(document.getElementById('<?php echo Html::hidID ?>').value, lsInputArr);
		im_load();
		set_modified();
	}
	else
	{
		ls_set_modified(document.getElementById('<?php echo Html::hidID ?>').value, false);
		ls_clear(document.getElementById('<?php echo Html::hidID ?>').value, lsInputArr);
	}
		
	ls_autosave(document.getElementById('<?php echo Html::hidID ?>').value, lsInputArr, 30000);
}

$('.<?php echo Css::divProductLink ?>').on('mouseenter', '.<?php echo Css::product ?>', product_enter).on('mouseleave', '.<?php echo Css::product ?>', product_leave);
function product_enter(event)
{
	product_hover(event, true);
}
function product_leave(event)
{
	product_hover(event, false);
}
function product_hover(event, hover)
{
	var a = event.target;
	while (a.className != '<?php echo Css::product ?>')
		a = a.parentNode;
	if (hover)
	{
		a.children[1].style.visibility = 'hidden';
		a.children[2].style.visibility = 'visible';
		a.children[2].style.top = a.children[1].offsetTop;
		a.children[2].style.backgroundColor = a.parentNode.parentNode.style.backgroundColor;
	}
	else
	{
		a.children[1].style.visibility = 'visible';
		a.children[2].style.visibility = 'hidden';
	}
}

});
</script>
</form>
</body>
</html>