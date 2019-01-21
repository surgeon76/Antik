var mX = -1;
var mY = -1;
var downX = 0;
var downY = 0;

var modeRotate = false;
var clickEnabled = true;

var addX = 0;
var addZ = 0;
var addY = 0;
var transX = 0;
var transZ = 0;
var transY = 0;
var rotY = 0;
var rotX = 0;

var cosR = 0;
var sinR = 0;
var sinA = 0;

var maxA = 15;
var minReal = -113;

var modeWalkWidth = 0;
var modeWalkLength = 0;
var modeWalkHeight = 0;

function GetInitPos(wall, toFromID)
{
	for (var i = 0; i < wall.childNodes.length; i++)
	{
		var item = wall.childNodes[i];
		var id = item.id;
		var dir = 1;
		if (id.indexOf('p') == 0)
			dir = -1;
		if (parseInt(id.substr(1)) == toFromID)
			return {pos: item.offsetLeft + item.offsetWidth / 2, posY: item.offsetTop + item.offsetHeight / 2, dir: dir};
	}
	return {pos: 0, posY: 0, dir: 0};
}

function Init()
{
	modeWalkLength = parseFloat($('#' + lWallID).css('width'));
	modeWalkWidth = parseFloat($('#' + nWallID).css('width'));
	modeWalkHeight = parseFloat($('#' + nWallID).css('height'));
	
	var l = modeWalkLength;
	var w = modeWalkWidth;
	
	transZ = -l / 2 + minDist;
	
	var hidMode = document.getElementById(modeWalkHid);
	if (hidMode && parseInt(hidMode.value) > 0)
	{
		var fromID = parseInt(hidMode.value);
		var walls = [document.getElementById(lWallID), document.getElementById(nWallID), document.getElementById(rWallID), document.getElementById(pWallID)];
		for (var i = 0; i < walls.length; i++)
		{
			var pos = GetInitPos(walls[i], fromID);
			if (pos.dir != 0)
			{
				if (i == 0)
				{
					transX = w / 2 - minDist * 2;
					transZ = pos.pos - l / 2;
					rotX = 90 * pos.dir;
				}
				else if (i == 1)
				{
					transX = (w - pos.pos) - w / 2;
					transZ = l / 2 - minDist * 2;
					rotX = (pos.dir + 1) * 90;
				}
				else if (i == 2)
				{
					transX = -w / 2 + minDist * 2;
					transZ = (l - pos.pos) - l / 2;
					rotX = -90 * pos.dir;
				}
				else
				{
					transX = pos.pos - w / 2;
					transZ = -l / 2 + minDist * 2;
					rotX = (pos.dir - 1) * 90;
				}
				correctRot();
				correctTrans();
				break;
			}
		}
	}
}

var animMoveTimerID = 0;
var animMoveHref = '';
var animMoveSteps = 25;
var animMoveStep = 0;
var animMoveStartX = 0;
var animMoveStartZ = 0;
var animMoveStartY = 0;
var animMoveStartRotX = 0;
var animMoveStartRotY = 0;
var animMoveEndX = 0;
var animMoveEndZ = 0;
var animMoveEndY = 0;
var animMoveEndRotX = 0;
var animMoveEndRotY = 0;
function AnimateMove(toID, href)
{
	if (document.getElementById('linkBack'))
	{
		animMoveHref = href;
		AnimateMoveCover(true, 'black', '0');
		AnimateMoveCoverFade(true);
		return;
	}
	if (!isWalkMode())
		return;
	
	var l = modeWalkLength;
	var w = modeWalkWidth;
	
	animMoveHref = href;
	
	animMoveStartX = transX;
	animMoveStartZ = transZ;
	animMoveStartRotX = rotX;
	animMoveStartRotY = rotY;
	
	var walls = [document.getElementById(lWallID), document.getElementById(nWallID), document.getElementById(rWallID), document.getElementById(pWallID)];
	for (var i = 0; i < walls.length; i++)
	{
		var pos = GetInitPos(walls[i], toID);
		if (pos.dir != 0)
		{
			if (i == 0)
			{
				animMoveEndX = w / 2;
				animMoveEndZ = pos.pos - l / 2;
				animMoveEndRotX = 270;
			}
			else if (i == 1)
			{
				animMoveEndX = (w - pos.pos) - w / 2;
				animMoveEndZ = l / 2;
				animMoveEndRotX = 0;
			}
			else if (i == 2)
			{
				animMoveEndX = -w / 2;
				animMoveEndZ = (l - pos.pos) - l / 2;
				animMoveEndRotX = 90;
			}
			else
			{
				animMoveEndX = pos.pos - w / 2;
				animMoveEndZ = -l / 2;
				animMoveEndRotX = 180;
			}
			if (pos.dir < 0)
			{
				var h = modeWalkHeight;
				animMoveStartY = 0;
				animMoveEndY = h / 2 - pos.posY;
				
				animMoveEndRotY = 0;
			}
			AnimateMoveCover(true, pos.dir < 0 ? 'black' : 'white', '0');
			animMoveTimerID = setInterval(AnimateMoveProc, 40);
			$('#' + divVport).css('z-index', '2');
			return;
		}
	}
	animMoveHref = href;
	AnimateMoveCover(true, 'white', '0');
	AnimateMoveCoverFade(true);
}

function AnimateMoveProc()
{
	animMoveStep++;
	
	var distX = animMoveEndX - animMoveStartX;
	var distZ = animMoveEndZ - animMoveStartZ;
	var distY = animMoveEndY - animMoveStartY;
	var curTransX = distX / animMoveSteps * animMoveStep;
	var curTransZ = distZ / animMoveSteps * animMoveStep;
	var curTransY = distY / animMoveSteps * animMoveStep;
	var delta = 0.001;
	transY = Math.abs(distY) > delta ? curTransY * curTransY / distY : 0;
	var dX = 0;
	var dZ = 0;
	if (animMoveEndRotX == 0 || animMoveEndRotX == 180)
	{
		var x = distX - curTransX;
		dX = Math.abs(x) > delta ? animMoveStartX + distX - x * x / distX - transX : 0;
		dZ = Math.abs(distZ) > delta ? animMoveStartZ + curTransZ * curTransZ / distZ - transZ : 0;
	}
	else
	{
		dX = Math.abs(distX) > delta ? animMoveStartX + curTransX * curTransX / distX - transX : 0;
		var z = distZ - curTransZ;
		dZ = Math.abs(z) > delta ? animMoveStartZ + distZ - z * z / distZ - transZ : 0;
	}
	
	ModeTrans(dX, dZ, 0, true);
	
	var distRotX = animMoveEndRotX - animMoveStartRotX;
	if (distRotX < -180)
		distRotX += 360;
	else if (distRotX > 180)
		distRotX -= 360;
	var distRotY = animMoveEndRotY - animMoveStartRotY;
	var curRotX = distRotX / animMoveSteps * animMoveStep;
	var curRotY = distRotY / animMoveSteps * animMoveStep;
	
	ModeRotate(Math.abs(distRotX) > delta ? animMoveStartRotX + curRotX * curRotX / distRotX - rotX : 0, Math.abs(distRotY) > delta ? -(animMoveStartRotY + curRotY * curRotY / distRotY) + rotY : 0);
	
	if (animMoveStep == Math.round(animMoveSteps / 3 * 2))
		AnimateMoveCoverFade(true);
	if (animMoveStep == animMoveSteps)
		clearInterval(animMoveTimerID);
}

var animMoveCover = null;
var animMoveCoverOn = false;
function AnimateMoveCover(on, color, opacity)
{
	animMoveCoverOn = false;
	if (animMoveCover)
	{
		$(animMoveCover).remove();
		animMoveCover = null;
	}
	if (on)
	{
		var vport = $('#tdItem')[0];
		animMoveCover = document.createElement('div');
		$(animMoveCover).css('left', '0').css('top', '21').css('width', vport.offsetWidth).css('height', vport.offsetHeight - 21).
			css('z-index', '10').css('opacity', opacity).css('background-color', color).css('position', 'absolute').
			css('user-select', 'none');
		vport.appendChild(animMoveCover);
		animMoveCoverOn = true;
	}
}

function AnimateMoveCoverFade(fadeIn)
{
	if (!animMoveCover)
		return;
	
	if (fadeIn)
	{
		$(animMoveCover).css('opacity', '0').css('pointer-events', 'auto').
			animate({ opacity: 1 }, 1000, function()
			{
				window.location.href = animMoveHref;
			});
	}
	else
	{
		$(animMoveCover).css('opacity', '1').css('pointer-events', 'none').
			animate({ opacity: 0 }, 1000, function()
			{
				if (!animMoveCoverOn)
					AnimateMoveCover(false);
			});
	}
}

function LightWindows(door)
{
	$(door).find('.lwindow').animate({ backgroundColor: '#FFA500' }, 300);
	$(door).find('.rwindow').animate({ backgroundColor: '#FFA500' }, 300, function()
	{
		AnimateDoorsOpen(door);
	});
}

var doorOpenTimerID = 0;
var doorOpenSteps = 12;
var doorOpenStep = 0;
var doorOpenWidth = 0;
function AnimateDoorsOpen(door)
{
	if (doorOpenStep == 0)
	{
		doorOpenTimerID = setInterval(AnimateDoorsOpen, 40, door);
		doorOpenWidth = parseFloat($(door).find('.ldoor').css('width'));
	}
	
	doorOpenStep++;
	
	$(door).find('.ldoor').css('left', -doorOpenWidth * doorOpenStep / doorOpenSteps);
	$(door).find('.rdoor').css('width', doorOpenWidth * (1 - doorOpenStep / doorOpenSteps));
	
	if (doorOpenStep == doorOpenSteps)
		clearInterval(doorOpenTimerID);
}

function correctRot()
{
	rotX %= 360;
	if (rotX < 0)
		rotX += 360;

	if (rotY > maxA)
		rotY = maxA;
	else if (rotY < -maxA)
		rotY = -maxA;
}

function vportGetH()
{
	var minH = 200;
	var vport = $('#' + divVport)[0];
	var tableFrame = $('#tableFrame')[0];
	var h = window.innerHeight - (tableFrame.rows[0].offsetHeight + tableFrame.rows[1].offsetHeight + 25 - document.body.scrollTop);
	if (h < minH)
		h = minH;
	else if (h > window.innerHeight)
		h = window.innerHeight;
	return h;
}

function ResizeAnimCover()
{
	if (animMoveCover)
	{
		var tdItem = $('#tdItem')[0];
		$(animMoveCover).css('width', tdItem.offsetWidth).css('height', tdItem.offsetHeight - 21);
	}
}

function ResizeScene()
{
	var tdItem = $('#tdItem')[0];
	var vport = $('#' + divVport)[0];
	var h = vportGetH();
	$(vport).css('width', tdItem.offsetWidth - 4);
	$(vport).css('height', h);
	$(vport).css('maxWidth', window.innerWidth);
	$(vport).css('maxHeight', h);
	var w = parseFloat($('#' + nWallID).css('width'));
	var h = parseFloat($('#' + nWallID).css('height'));
	$('#' + divScene).css('left', vport.offsetWidth / 2 - w / 2);
	$('#' + divScene).css('top', vport.offsetHeight / 2 - h / 2);
	
	$('#arrowHolder').css('left', vport.offsetWidth / 2 - 75);
	
	ResizeAnimCover();
}

function isWalkMode()
{
	return document.getElementById(modeWalkHid) ? true : false;
}

function MoveScene()
{
	$('#' + divScene).css('transform', 'rotateY(' + rotX + 'deg) rotate3d(' + cosR + ',0, ' + sinR + ',' + rotY + 'deg)' + ' translate3d(' + (transX + addX) + 'px,' + (transY + addY) + 'px,' + (transZ + addZ) + 'px)');
}

function UpdateAdd()
{
	addX = (1 - (minDist - minReal)) * sinR * (1 - Math.abs(sinA) / 8);
	addZ = (minDist - minReal) * cosR * (1 - Math.abs(sinA) / 8);
	addY = modeWalkHeight * sinA;
}

function UpdateWalls()
{
	var num = 0.8;
	if (cosR > num)
		$('#' + pWallID).css('display', 'none');
	else
		$('#' + pWallID).css('display', 'inline-block');
	
	if (sinR > num)
		$('#' + lWallID).css('display', 'none');
	else
		$('#' + lWallID).css('display', 'inline-block');
	
	if (sinR < -num)
		$('#' + rWallID).css('display', 'none');
	else
		$('#' + rWallID).css('display', 'inline-block');
	
	if (cosR < -num)
		$('#' + nWallID).css('display', 'none');
	else
		$('#' + nWallID).css('display', 'inline-block');
}

function CalcRotation()
{
	cosR = Math.cos(rotX / 180 * Math.PI);
	sinR = Math.sin(rotX / 180 * Math.PI);
	sinA = Math.sin(rotY / 180 * Math.PI);
}

function getMouseButton(event) {
    var buttonPressed = "none";
    if (event) {
        if ((event.which === null) || (event.which === 'undefined') || (!event.hasOwnProperty("which"))) {
            buttonPressed = (event.button < 2) ? 'left' :
                ((event.button === 4) ? 'middle' : 'right');
        } else {
            buttonPressed = (event.which < 2) ? 'left' :
                ((event.which === 2) ? 'middle' : 'right');
        }
    }
    return buttonPressed;
}

function correctTrans()
{
	var l = modeWalkLength;
	var w = modeWalkWidth;
	
	if (transX < -w / 2 + minDist)
		transX = -w / 2 + minDist;
	else if (transX > w / 2 - minDist)
		transX = w / 2 - minDist;
	
	if (transZ < -l / 2 + minDist)
		transZ = -l / 2 + minDist;
	else if (transZ > l / 2 - minDist)
		transZ = l / 2 - minDist;
}

function ModeTrans(dX, dZ, level, notCorrect)
{
	level = level || 0;
	
	if (dX == 0 && dZ == 0)
		return;

	transX += dX;
	transZ += dZ;
	
	if (!notCorrect)
		correctTrans();
	
	UpdateAdd();
	MoveScene();
	
	if (level > 0)
		setTimeout(ModeTrans, 40, dX, dZ, level - 1);
}

function ModeRotate(dX, dY, level)
{
	level = level || 0;
	
	rotX += dX;
	rotY -= dY;
	correctRot();
	
	CalcRotation();
	UpdateAdd();
	MoveScene();
	UpdateWalls();
	
	if (level > 0)
		setTimeout(ModeRotate, 40, dX, dZ, level - 1);
}

var modeKeys = [];
var modeKeyTimerID = 0;
var modeKeyTrans = 80;
var modeKeyRot = 6;
function isProceedModeKey(key)
{
	if (key == 37 || key == 38 || key == 39 || key == 40 ||
		key == 65 || key == 87 || key == 68 || key == 83 ||
		key == 16)
		return true;
	return false;
}

function ModeKeyDown(key)
{
	if (modeKeys.indexOf(key) < 0)
	{
		modeKeys.push(key);
		if (modeKeys.length == 1)
			modeKeyTimerID = setInterval(ModeKeyTimer, 40);
	}
}

function ModeKeyUp(key)
{
	var i = modeKeys.indexOf(key);
	if (i >= 0)
	{
		modeKeys.splice(i, 1);
		if (modeKeys.length == 0)
			clearInterval(modeKeyTimerID);
	}
}

function ModeKeyTimer()
{
	if (modeKeys.indexOf(37) >= 0)
	{
		if (modeKeys.indexOf(16) >= 0)
			ModeTrans(modeKeyTrans * cosR, modeKeyTrans * sinR, 0);
		else
			ModeRotate(-modeKeyRot, 0);
	}
	if (modeKeys.indexOf(65) >= 0)
	{
		if (modeKeys.indexOf(16) >= 0)
			ModeRotate(-modeKeyRot, 0);
		else
			ModeTrans(modeKeyTrans * cosR, modeKeyTrans * sinR, 0);
	}
	if (modeKeys.indexOf(39) >= 0)
	{
		if (modeKeys.indexOf(16) >= 0)
			ModeTrans(-modeKeyTrans * cosR, -modeKeyTrans * sinR, 0);
		else
			ModeRotate(modeKeyRot, 0);
	}
	if (modeKeys.indexOf(68) >= 0)
	{
		if (modeKeys.indexOf(16) >= 0)
			ModeRotate(modeKeyRot, 0);
		else
			ModeTrans(-modeKeyTrans * cosR, -modeKeyTrans * sinR, 0);
	}
	if (modeKeys.indexOf(38) >= 0 || modeKeys.indexOf(87) >= 0)
	{
		if (modeKeys.indexOf(16) < 0)
			ModeTrans(-modeKeyTrans * sinR, modeKeyTrans * cosR, 0);
		else
			ModeRotate(0, -modeKeyRot / 2);
	}
	if (modeKeys.indexOf(40) >= 0 || modeKeys.indexOf(83) >= 0)
	{
		if (modeKeys.indexOf(16) < 0)
			ModeTrans(modeKeyTrans * sinR, -modeKeyTrans * cosR, 0);
		else
			ModeRotate(0, modeKeyRot / 2);
	}
}

$(window).load(function(){
	if (!isWalkMode())
		return;
	
	ResizeScene();
});

$(document).ready(function(){

$('body').on('click', '#linkBack', function(e){
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	animMoveHref = this.href;
	AnimateMoveCover(true, 'black', '0');
	AnimateMoveCoverFade(true);
});

$('body').on('click', 'a.treeLink', function(e){
	if (!isWalkMode() && !document.getElementById('linkBack'))
		return;
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	if (this.href.indexOf(':void(0)') > 0)
		return false;
	
	animMoveHref = this.href;
	AnimateMoveCover(true, document.getElementById('linkBack') ? 'black' : 'white', '0');
	AnimateMoveCoverFade(true);
});

if (!isWalkMode())
	return;

Init();
ResizeScene();
CalcRotation();
UpdateAdd();
MoveScene();
UpdateWalls();

$('body').on('mousedown', '#' + divVport, function(e){
	if (e.which !== 1)
		return;
	
	$('#' + divVport).css('z-index', '2');
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	modeRotate = true;
	mX = e.clientX;
	mY = e.clientY;
	downX = e.clientX;
	downY = e.clientY;
	
	moveCursorSave = document.body.style.cursor;
	document.body.style.cursor = 'move';
});

$('body').on('mousemove', '#' + divVport, function(e){
	if (!modeRotate)
		return;
	if (mX < 0)
	{
		mX = e.clientX;
		mY = e.clientY;
		return;
	}
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	var dX = e.clientX - mX;
	var dY = (e.clientY - mY) / 3;
	
	ModeRotate(e.clientX - mX, (e.clientY - mY) / 3);
	
	mX = e.clientX;
	mY = e.clientY;
	
	if (Math.abs(e.clientX - downX) > 1 || Math.abs(e.clientY - downY) > 1)
		clickEnabled = false;
});

$('body').on('mouseup', '#' + divVport, function(e){
	if (e.which !== 1)
		return;
	if (!modeRotate)
		return;
	
	$('#' + divVport).css('z-index', '0');
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	modeRotate = false;
	document.body.style.cursor = 'default';
	
	setTimeout(function(){
		clickEnabled = true;
	}, 200);
});

$('body').on('wheel', '#' + divVport, function(e){
	var t = e.originalEvent.deltaY > 0 ? 100 : -100;
	ModeTrans(t * 3 * sinR, -t * 3 * cosR, 0);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$(window).resize(function() {
	ResizeScene();
});

$(window).scroll(function() {
	ResizeScene();
});

$(document).keydown(function(e){
	if (document.getElementById(imageID))
		return;
	if (isProceedModeKey(e.keyCode))
	{
		ModeKeyDown(e.keyCode);
		
		e.preventDefault();
		e.stopPropagation();
		e.cancelBubble = true;
	}
});

$(document).keyup(function(e){
	if (isProceedModeKey(e.keyCode))
	{
		ModeKeyUp(e.keyCode);
		
		e.preventDefault();
		e.stopPropagation();
		e.cancelBubble = true;
	}
});

$('body').on('mousedown', '#leftArrow', function(e){
	ModeKeyDown(37);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mousedown', '#rightArrow', function(e){
	ModeKeyDown(39);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mousedown', '#forwardArrow', function(e){
	ModeKeyDown(38);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mousedown', '#backwardArrow', function(e){
	ModeKeyDown(40);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mouseup mouseleave', '#leftArrow', function(e){
	ModeKeyUp(37);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mouseup mouseleave', '#rightArrow', function(e){
	ModeKeyUp(39);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mouseup mouseleave', '#forwardArrow', function(e){
	ModeKeyUp(38);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('mouseup mouseleave', '#backwardArrow', function(e){
	ModeKeyUp(40);
	
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
});

$('body').on('click', 'a.product', function(e){
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	if (clickEnabled)
		AnimateMove(parseInt(this.parentNode.parentNode.id.substr(1)), this.href);
});

// $('body').on('click', 'a.chapter, a.category, a.root', function(e){
	// e.preventDefault();
	// e.stopPropagation();
	// e.cancelBubble = true;
	
	// if (clickEnabled)
	// {
		// AnimateMove(parseInt(this.parentNode.parentNode.id.substr(1)), this.href);
		// LightWindows(this.parentNode.parentNode);
	// }
// });

$('body').on('click', '.door', function(e){
	e.preventDefault();
	e.stopPropagation();
	e.cancelBubble = true;
	
	if (clickEnabled)
	{
		var id = this.id.substr(1);
		AnimateMove(parseInt(id), $(this).find('a').attr('href'));
		LightWindows(this);
	}
});

});
