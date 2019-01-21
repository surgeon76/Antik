var im_sel = -1;

function im_load()
{
	im_add();
}

function im_add(imagesToAdd)
{
	var images = "" + document.getElementById(hidImagesID).value;
	var arr = [];
	if (images.length > 0)
		arr = images.split('>');
	
	var arrToAdd = [];
	if (imagesToAdd !== undefined && imagesToAdd.length > 0)
		arrToAdd = imagesToAdd.split('>');
	
	arr = arr.concat(arrToAdd);
	document.getElementById(hidImagesID).value = arr.join('>');
	
	var thumbs = document.getElementById(divThumbsID);
	while (thumbs.hasChildNodes())
	{
		thumbs.removeChild(thumbs.lastChild);
	}
	
	var id = document.getElementById(hidID).value;
	var path = './' + imgPath + '/' + id + '/' + thumbPrefix;
	var rootImgWidth = document.getElementById(rootImagesWidthID);
	for (var i = 0; i < arr.length; i++)
	{
		var div = document.createElement('div');
		div.className = classDivThumb;
		div.id = divThumbID + i;
		var img = document.createElement('img');
		img.src = path + $('<textarea />').html(arr[i]).text();
		img.className = classImgThumb;
		div.appendChild(img);
		if (document.getElementById(hidAdminMode))
		{
			var linkDel = document.createElement('a');
			linkDel.href = 'javascript:im_delete(' + i + ');';
			linkDel.innerHTML = 'x';
			linkDel.className = divDelLink;
			div.appendChild(linkDel);
			var linkHead = document.createElement('a');
			linkHead.href = 'javascript:im_set_head(' + i + ');';
			linkHead.innerHTML = 'Сделать заглавным';
			linkHead.className = divOverLink;
			div.appendChild(linkHead);
		}
		if (rootImgWidth)
		{
			img.style.width = '';
			img.style.height = '';
			img.style.maxWidth = rootImagesWidth;
		}
		else
		{
			img.style.width = 158;
			img.style.height = 158;
		}
		thumbs.appendChild(div);
	}
	
	if (im_sel >= 0)
		im_select(im_sel, true);
	
	im_update_links();
}

function im_root(obj)
{
	obj.style.width = '';
	obj.style.height = '';
	obj.style.maxWidth = rootImagesWidth;
}

function im_delete(index)
{
	var thumbs = document.getElementById(divThumbsID);
	thumbs.removeChild(thumbs.children[index]);
	
	var images = document.getElementById(hidImagesID).value;
	var arr = images.split('>');
	arr.splice(index, 1);
	document.getElementById(hidImagesID).value = arr.join('>');
	
	if (index == im_sel)
		im_sel = -1;

	im_update_links();
	
	if (index < arr.length)
		im_hover(index, true);
	
	set_modified();
}

function im_select(index, indexOnly)
{
	var io = indexOnly || false;
	
	var thumbs = document.getElementById(divThumbsID);
	if (index >= thumbs.children.length)
		index = thumbs.children.length - 1;
	im_sel = index;
	for (var i = 0; i < thumbs.children.length; i++)
	{
		thumbs.children[i].className = i == index ? classDivThumbSel : classDivThumb;
	}
	
	var divImage = document.getElementById(divImgID);
	var image = document.getElementById(imageID);
	if (!io && im_sel >= 0)
	{
		var images = document.getElementById(hidImagesID).value;
		var arr = images.split('>');
		var id = document.getElementById(hidID).value;
		var path = './' + imgPath + '/' + id + '/';
		
		im_show(true, path + arr[im_sel]);
	}
}

function im_set_head(index)
{
	var thumbs = document.getElementById(divThumbsID);
	thumbs.insertBefore(thumbs.children[index], thumbs.children[0]);
	
	var images = document.getElementById(hidImagesID).value;
	var arr = images.split('>');
	arr.splice(0, 0, arr[index]);
	arr.splice(index + 1, 1);
	document.getElementById(hidImagesID).value = arr.join('>');
	
	if (im_sel > 0)
	{
		if (index == im_sel)
			im_select(0, true);
		else if (index > im_sel)
			im_select(im_sel + 1, true);
	}

	im_update_links();
	
	im_hover(index, true);
	
	set_modified();
}

function im_update_links()
{
	if (!document.getElementById(hidAdminMode))
		return;
	
	var thumbs = document.getElementById(divThumbsID);
	for (var i = 0; i < thumbs.children.length; i++)
	{
		var div = thumbs.children[i];
		div.id = divThumbID + i;
		for (var j = 0; j < div.children.length; j++)
		{
			if (div.children[j].className == divDelLink)
			{
				div.children[j].href = 'javascript:im_delete(' + i + ');';
				div.children[j].style.visibility = 'visible';
				im_link_normal_state(div.children[j], i);
			}
			else if (div.children[j].className == divOverLink)
			{
				div.children[j].href = 'javascript:im_set_head(' + i + ');';
				div.children[j].style.visibility = 'visible';
				if (i == 0)
				{
					div.children[j].style.visibility = 'hidden';
				}
				im_link_normal_state(div.children[j], i);
			}
		}
	}
}

function im_hover(index, hover)
{
	if (!document.getElementById(hidAdminMode))
		return;
	
	var thumbs = document.getElementById(divThumbsID);
	var div = thumbs.children[index];
	for (var j = 0; j < div.children.length; j++)
	{
		if (div.children[j].className == divDelLink || div.children[j].className == divOverLink)
		{
			if (hover)
				div.children[j].style.opacity = '1.0';
			else
				im_link_normal_state(div.children[j], index);
		}
	}
}

function im_link_normal_state(link, index)
{
	if (link.className == divDelLink)
	{
		link.style.opacity = '0.3';
		if (index == im_sel)
			link.style.opacity = '0.2';
	}
	else if (link.className == divOverLink)
	{
		link.style.opacity = '0.2';
		if (index == im_sel)
			link.style.opacity = '0.1';
	}
}

var divShadowID = 'divShadow';
var divCoverID = 'divCover';
var arrowClass = 'arrowImg';
var closeID = 'closeImg';
var newWinID = 'newWinImg';
var borderW = 4;
var loadingID = 'loading';
var loadingWH = 75;
function im_show(show, src)
{
	if (show)
	{
		var curImg = document.getElementById(imageID);
		if (curImg)
		{
			$('#' + loadingID).css('display', 'inline-block');
			curImg.src = src;
			return;
		}
		
		pause = true;
		$('body').css('overflow', 'hidden');
		
		var w = window.innerWidth;
		var h = window.innerHeight;
		
		var shadow = document.createElement('div');
		shadow.id = divShadowID;
		$(shadow).css('position', 'absolute').css('display', 'inline-block').css('background-color', 'black').
			css('left', document.body.scrollLeft).css('top', document.body.scrollTop).css('width', w).css('height', h).
			css('opacity', 0).css('z-index',  10);
			
		var cover = document.createElement('div');
		cover.id = divCoverID;
		$(cover).css('position', 'absolute').css('display', 'table').
			css('left', document.body.scrollLeft).css('top', document.body.scrollTop).css('width', w).css('height', h).
			css('z-index',  11);
			
		var cell = document.createElement('div');
		$(cell).css('position', 'relative').css('display', 'table-cell').css('width', '100%').css('height', '100%').css('vertical-align', 'middle').css('text-align', 'center');
		cover.appendChild(cell);
		
		var loading = document.createElement('div');
		loading.id = loadingID;
		$(loading).css('position', 'absolute').css('z-index', 12).css('cursor', 'wait').
			css('width', loadingWH).css('height', loadingWH).css('display', 'inline-block').
			css('left', document.body.scrollLeft + window.innerWidth / 2 - loadingWH / 2).css('top', document.body.scrollTop + window.innerHeight / 2 - loadingWH / 2).
			css('background-image', 'url(./' + imgPath + '/loading.gif)').css('background-size', '' + loadingWH + 'px ' + loadingWH + 'px');
		document.body.appendChild(loading);
		
		var image = document.createElement('img');
		image.id = imageID;
		image.src = src;
		image.style.visibility = 'hidden';
		$(image).css('position', 'relative').css('border', '4px solid #FFCD70').css('max-width', 0).css('max-height', 0);
		$(image).load(function(){
			$('#' + loadingID).css('display', 'none');
			if (this.style.visibility !== 'visible')
			{
				this.style.visibility = 'visible';
				$(image).css('max-width', 0).css('max-height', 0);
				$(image).animate({ maxWidth: w - borderW * 2, maxHeight: h - borderW * 2 }, 500);
			}
		});
		cell.appendChild(image);
		
		document.body.appendChild(shadow);
		$(shadow).animate({ opacity: 0.75 }, 500);
		
		document.body.appendChild(cover);
		
		var arrowL = document.createElement('div');
		arrowL.innerHTML = '&nbsp;&lt;&nbsp;&nbsp;';
		arrowL.className = arrowClass;
		$(arrowL).css('left', document.body.scrollLeft);
			
		var arrowR = document.createElement('div');
		arrowR.innerHTML = '&nbsp;&nbsp;&gt;&nbsp;';
		arrowR.className = arrowClass;
		$(arrowR).css('right', -document.body.scrollLeft);
		
		var thumbs = document.getElementById(divThumbsID);
		if (!thumbs || thumbs.children.length < 2)
		{
			arrowL.style.visibility = 'hidden';
			arrowR.style.visibility = 'hidden';
		}
			
		document.body.appendChild(arrowL);
		document.body.appendChild(arrowR);
		
		$('.' + arrowClass).css('color', 'white').css('background-color', 'black').css('opacity', 0.75).css('font-size', '35px').css('line-height', '27px').
			css('position', 'absolute').css('z-index', 12).
			css('display', 'inline-block').css('cursor', 'pointer').css('transform', 'scale(1, 3)').css('user-select', 'none');
		$('.' + arrowClass).css('top', document.body.scrollTop + window.innerHeight / 2 - $('.' + arrowClass)[0].offsetHeight / 2);
			
		var close = document.createElement('div');
		close.innerHTML = '&nbsp;x&nbsp;';
		close.id = closeID;
		$(close).css('right', -document.body.scrollLeft).css('top', document.body.scrollTop).
			css('color', 'white').css('background-color', 'black').css('opacity', 0.75).css('font-size', '42px').
			css('position', 'absolute').css('z-index', 12).css('display', 'inline-block').css('cursor', 'pointer').
			css('height', 48).css('width', 48).css('user-select', 'none');
		document.body.appendChild(close);
		
		var newWin = document.createElement('div');
		newWin.id = newWinID;
		$(newWin).css('left', document.body.scrollLeft).css('top', document.body.scrollTop).css('background-image', 'url(./images/newtab.png)').css('background-size', '48px 48px').
			css('color', 'white').css('background-color', 'black').css('opacity', 0.75).css('font-size', '17px').
			css('position', 'absolute').css('z-index', 12).css('display', 'inline-block').css('cursor', 'pointer').
			css('width', 48).css('height', 48);
		newWin.title = 'Открыть фотографию в новой вкладке';
		document.body.appendChild(newWin);
	}
	else
	{
		var image = document.getElementById(imageID);
		if (image)
			$(image).animate({ maxWidth: 10, maxHeight: 10 }, 500);
		
		var shadow = document.getElementById(divShadowID);
		if (shadow)
		{
			$(shadow).css('opacity', 0.75);
			$(shadow).animate({ opacity: 0 }, 500, function (){
				document.body.removeChild(shadow);
				var cover = document.getElementById(divCoverID);
				document.body.removeChild(cover);
				
				$('body').css('overflow', 'auto');
			});
		}
		
		$('#' + loadingID).remove();
		$('.' + arrowClass).remove();
		$('#' + closeID).remove();
		$('#' + newWinID).remove();
		
		pause = false;
	}
}

function im_resize()
{
	var w = window.innerWidth;
	var h = window.innerHeight;
		
	var shadow = document.getElementById(divShadowID);
	if (shadow)
		$(shadow).css('width', w).css('height', h).css('left', document.body.scrollLeft).css('top', document.body.scrollTop);
	
	var cover = document.getElementById(divCoverID);
	if (cover)
		$(cover).css('width', w).css('height', h).css('left', document.body.scrollLeft).css('top', document.body.scrollTop);
	
	if (!fullSizeMode)
	{
		var image = document.getElementById(imageID);
		if (image)
			$(image).css('max-width', w - borderW * 2).css('max-height', h - borderW * 2);
	}
	else
	{
		fullSizePos({x: mouseX / winW * window.innerWidth, y: mouseY / winH * window.innerHeight});
	}
	
	var arrows = $('.' + arrowClass);
	if (arrows[0] && arrows[1])
	{
		arrows[0].style.left = document.body.scrollLeft;
		arrows[1].style.right = -document.body.scrollLeft;
		arrows.css('top', document.body.scrollTop + window.innerHeight / 2 - $('.' + arrowClass)[0].offsetHeight / 2);
	}
	$('#' + loadingID).css('width', loadingWH).css('height', loadingWH).
		css('left', document.body.scrollLeft + window.innerWidth / 2 - loadingWH / 2).css('top', document.body.scrollTop + window.innerHeight / 2 - loadingWH / 2);
	$('#' + closeID).css('right', -document.body.scrollLeft).css('top', document.body.scrollTop);
	$('#' + newWinID).css('left', document.body.scrollLeft).css('top', document.body.scrollTop);
}

function im_navigate(next)
{
	var thumbs = document.getElementById(divThumbsID);
	if (!thumbs || thumbs.children.length < 2)
		return;
	var index = im_sel;
	if (!next)
	{
		index--;
		if (index < 0)
			index = thumbs.children.length - 1;
	}
	else
	{
		index++;
		if (index >= thumbs.children.length)
			index = 0;
	}
	im_select(index, !document.getElementById(imageID));
}

var fullSizeMode = false;
var mouseX = 0, mouseY = 0;
var winW = 0, winH = 0;
var pause = false;
function fullSizePos(mouse)
{
	var img = document.getElementById(imageID);
	if (!img)
		return;
	if (!fullSizeMode)
		return;
	
	img.style.left = -mouse.x * img.width / window.innerWidth + window.innerWidth / 2;
	img.style.top = -mouse.y * img.height / window.innerHeight + window.innerHeight / 2;
}

function im_animate_root(start, horz, vert)
{
	if (document.getElementById(hidAdminMode) || !document.getElementById(rootImagesWidthID))
		return;
	var thumbs = document.getElementById(divThumbsID);
	if (thumbs.children.length < start + 2)
		return;
	setInterval(move_root, 5000, start, horz, vert);
}
function move_root(start, horz, vert)
{
	if (pause)
		return;
	
	var thumbs = document.getElementById(divThumbsID);
	var afterStart = thumbs.children[start + 1];
	$(thumbs.children[start]).animate({ opacity: 0 }, 1000, function(){
		for (var i = start + 1; i < thumbs.children.length; i++)
		{
			var l = horz ? -(thumbs.children[i - 1].offsetWidth + 4) : 0;
			var t = vert ? -(thumbs.children[i - 1].offsetHeight + 4) : 0;
			$(thumbs.children[i]).animate({ left: l, top: t }, 1000, function(){
				if (this == afterStart)
				{
					$(thumbs.children[start]).animate({ opacity: 1 }, 1000);
					thumbs.appendChild(thumbs.children[start]);
					if (im_sel >= start)
					{
						if (im_sel == start)
							im_sel = thumbs.children.length - 1;
						else
							im_sel--;
					}
					var images = document.getElementById(hidImagesID).value;
					var arr = images.split('>');
					arr.push(arr[start]);
					arr.splice(start, 1);
					document.getElementById(hidImagesID).value = arr.join('>');
				}
				this.style.left = 0;
				this.style.top = 0;
			});
		}
	});
}

var maxJerkTimes = 7;
var jerkTimes = maxJerkTimes;
var jerkIndex = 0;
function im_jerk()
{
	if (document.getElementById(hidAdminMode))
		return;
	
	var side = jerkTimes % 2 == 0 ? -1 : 1;
	var thumbs = document.getElementById(divThumbsID);
	if (!thumbs || thumbs.children.length == 0)
		return;
		
	if (jerkTimes == maxJerkTimes)
	{
		jerkIndex = 0;
		if (!document.getElementById(rootImagesWidthID))
			jerkIndex = Math.floor(Math.random() * thumbs.children.length);
	}
		
	thumbs.children[jerkIndex].style.transform = 'rotateZ(' + side * 1 + 'deg)';
	
	jerkTimes--;
	if (jerkTimes <= 0)
	{
		thumbs.children[jerkIndex].style.transform = '';
		jerkTimes = maxJerkTimes;
		setTimeout(im_jerk, 10000);
	}
	else
	{
		setTimeout(im_jerk, 50);
	}
}

$(document).ready(function(){
	
$(document).keydown(function(e){
	if (fullSizeMode)
		return;
	
    if (e.keyCode === 27) // ESC
	{
		if (!document.getElementById(imageID))
			return;
        im_show(false);
		e.stopPropagation();
		e.preventDefault();
	}
	else if (e.keyCode === 37) // left
	{
		if (!document.getElementById(imageID))
			return;
		im_navigate(false);
		e.stopPropagation();
		e.preventDefault();
	}
	else if (e.keyCode === 39) // right
	{
		if (!document.getElementById(imageID))
			return;
		im_navigate(true);
		e.stopPropagation();
		e.preventDefault();
	}
	else if (e.keyCode === 13 || e.keyCode === 32) // enter, space
	{
		if (!document.getElementById(imageID))
			return;
		enterPress(e);
		e.stopPropagation();
		e.preventDefault();
	}
});
function enterPress(e)
{
	if (document.getElementById(imageID))
		im_navigate(!e.shiftKey);
	else
		im_select(im_sel);
}

$('body').on('click', '.' + arrowClass, function(e){
	if (fullSizeMode)
		return;
	
	im_navigate(e.target.innerHTML.indexOf('lt') < 0);
	e.stopPropagation();
});

$('body').on('click', '#' + closeID, function(e){
	if (fullSizeMode)
		return;
	
	im_show(false);
	e.stopPropagation();
});

$('body').on('click', '#' + newWinID, function(e){
	if (fullSizeMode)
		return;
	
	window.open($('#' + imageID)[0].src, '_blank');
	e.stopPropagation();
});

$('body').on('mouseenter', '.' + arrowClass + ', #' + closeID + ', #' + newWinID, hoverImgIn).
	on('mouseleave', '.' + arrowClass + ', #' + closeID + ', #' + newWinID, hoverImgOut);
function hoverImgIn(e)
{
	hoverImg(e, true);
}
function hoverImgOut(e)
{
	hoverImg(e, false);
}
function hoverImg(e, hover)
{
	if (hover)
	{
		e.target.style.opacity = 1;
	}
	else
	{
		e.target.style.opacity = 0.75;
	}
}

$('body').on('wheel', '#' + divThumbsID + ', #' + divCoverID, function(e){
	if (!document.getElementById(imageID))
		return;
	if (fullSizeMode)
		return;
	
	if (!e.ctrlKey && !e.altKey && !e.shiftKey)
	{
		im_navigate(e.originalEvent.deltaY > 0);
		e.stopPropagation();
		e.preventDefault();
	}
});

$('#' + divThumbsID).on('mouseenter', '.' + classDivThumb, thumb_enter).on('mouseleave', '.' + classDivThumb, thumb_leave);
$('#' + divThumbsID).on('mouseenter', '.' + classDivThumbSel, thumb_enter).on('mouseleave', '.' + classDivThumbSel, thumb_leave);
function thumb_enter(event)
{
	thumb_hover(event, true);
}
function thumb_leave(event)
{
	thumb_hover(event, false);
}
function thumb_hover(event, hover)
{
	var p = event.target.parentNode;
	var div = event.target;
	if (!div.id)
	{
		p = p.parentNode;
		div = div.parentNode;
	}
	for (var i = 0; i < p.children.length; i++)
	{
		if (div.id && p.children[i].id == div.id)
		{
			im_hover(i, hover);
			break;
		}
	}
}

$('#' + divThumbsID).on('click', '.' + classImgThumb, thumb_click);
function thumb_click(event)
{
	var p = event.target.parentNode.parentNode;
	for (var i = 0; i < p.children.length; i++)
	{
		if (p.children[i] == event.target.parentNode)
		{
			im_select(i);
			im_update_links();
			im_hover(i, true);
			break;
		}
	}
}

function goFullSize(on)
{
	var img = document.getElementById(imageID);
	if (!img)
		return;
	
	if (on)
	{
		img.style.position = 'absolute';
		img.style.maxWidth = '';
		img.style.maxHeight = '';
		fullSizeMode = true;
		//img.setCapture(true);
		$('#' + divCoverID).css('cursor', 'move');
		$('#' + imageID).css('cursor', 'move');
		$('#' + closeID).css('display', 'none');
		$('#' + newWinID).css('display', 'none');
		$('.' + arrowClass).css('display', 'none');
	}
	else
	{
		fullSizeMode = false;
		img.style.left = '';
		img.style.top = '';
		img.style.maxWidth = window.innerWidth - borderW * 2;
		img.style.maxHeight = window.innerHeight - borderW * 2;
		img.style.position = 'relative';
		//img.setCapture(false);
		$('#' + divCoverID).css('cursor', 'default');
		$('#' + imageID).css('cursor', 'zoom-in');
		$('#' + closeID).css('display', 'inline-block');
		$('#' + newWinID).css('display', 'inline-block');
		$('.' + arrowClass).css('display', 'inline-block');
	}
}

$('body').on('mousedown', '#' + imageID, function(e){
	if (fullSizeMode)
		return;
	if (e.which !== 1)
		return;
	
	e.preventDefault();
	
	goFullSize(true);
	fullSizePos({x: e.clientX, y: e.clientY});
});

$('body').on('mousemove', '#' + divCoverID, function(e){
	mouseX = e.clientX;
	mouseY = e.clientY;
	winW = window.innerWidth;
	winH = window.innerHeight;
	
	if (!fullSizeMode)
		return;
	
	e.preventDefault();
	
	fullSizePos({x: e.clientX, y: e.clientY});
});

$('body').on('mouseup', '#' + divCoverID, function(e){
	if (!fullSizeMode)
		return;
	
	e.preventDefault();
});

$('body').on('click', '#' + divCoverID, function(e){
	if (fullSizeMode)
		goFullSize(false);
	else
		im_show(false);
});

$(window).resize(function() {
	im_resize();
});

$('#image, .imgThumb, .imgProduct').error(function(){
	setTimeout(reloadImg, 1000, this);
	console.log('Reloading image ' + this.src);
});
function reloadImg(img)
{
	var d = new Date();
	var src = img.src;
	var pos = src.indexOf('?');
	if (pos > 0)
		src = src.substr(0, pos);
	img.src = src + '?' + d.getTime();
}

// $('#' + imageID).mousecapture({
	// "down": function(e, s){
		// if (fullSizeMode)
			// return;
		// goFullSize(true);
		// fullSizePos({x: e.clientX, y: e.clientY});
	// },
	// "move": function(e, s){
		// if (!fullSizeMode)
			// return;
		// e.preventDefault();
		// fullSizePos({x: e.clientX, y: e.clientY});
	// },
	// "up": function(e, s){
		// if (fullSizeMode)
		// {
			// e.stopPropagation();
			// goFullSize(false);
		// }
	// },
// });

});
