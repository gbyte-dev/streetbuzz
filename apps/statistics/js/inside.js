var _d	= document;
var _w	= window;
var d		= document;
var w		= window;
var siteurl	= "/";

var disable_animations	= false;

var window_loaded	= false;

var dropdivs	= {};
var dropdiv_dropstep_px	= 10;
var dropdiv_dropstep_tm	= 1;

function dropdiv_open(div_id, height_offset)
{
	if( dropdivs[div_id] == 1 ) {
		return dropdiv_close(div_id);
	}
	if( dropdivs[div_id] == 2 ) {
		return false;
	}
	var div	= _d.getElementById(div_id);
	if( !div ) {
		return false;
	}
	if( disable_animations ) {
		dropdivs[div_id]		= 1;
		div.style.display		= "block";
		if( _d.addEventListener ) {
			_d.addEventListener("mouseup", function(){dropdiv_close(div_id);}, false);
		}
		else if( _d.attachEvent ) {
			_d.attachEvent("onmouseup", function(){dropdiv_close(div_id);} );
		}
		return true;
	}
	var height	= parseInt(div.style.height, 10);
	if( !height ) {
		div.style.visiblity	= "hidden";
		div.style.display		= "block";
		height	= parseInt(div.clientHeight, 10);
		div.style.display		= "none";
		div.style.visiblity	= "visible";
		if( height_offset ) {
			height	+= height_offset;
		}
	}
	if( !height ) {
		return false;
	}
	var h	= 0;
	var func	= function() {
		div.style.height	= h+"px";
		if( h >= height ) {
			dropdivs[div_id]	= 1;
			div.style.height	= height+"px";
			div.style.overflow	= div.getAttribute("orig_overflow");
			if( _d.addEventListener ) {
				_d.addEventListener("mouseup", function(){dropdiv_close(div_id);}, false);
			}
			else if( _d.attachEvent ) {
				_d.attachEvent("onmouseup", function(){dropdiv_close(div_id);} );
			}
			return true;
		}
		h	+= dropdiv_dropstep_px;
		setTimeout( func, dropdiv_dropstep_tm );
	};
	var tmp = div.getAttribute("orig_overflow");
	if( ! tmp ) {
		tmp	= div.style.overflow ? div.style.overflow : "visible";
	}
	div.setAttribute("orig_overflow", tmp);
	div.style.overflow	= "hidden";
	div.style.display		= "block";
	dropdivs[div_id]	= 2;
	func();
}

function dropdiv_close(div_id, do_it_fast)
{
	if( dropdivs[div_id] == 0 ) {
		return true;
	}
	if( dropdivs[div_id] == 2 ) {
		return false;
	}
	var div	= _d.getElementById(div_id);
	if( !div ) {
		return false;
	}
	if( disable_animations || do_it_fast ) {
		div.style.display	= "none";
		dropdivs[div_id]	= 0;
		return;
	}
	var h	= parseInt(div.style.height, 10);
	var orig_h	= h;
	var func	= function() {
		div.style.height	= Math.max(0,h)+"px";
		if( h <= 0 ) {
			div.style.display	= "none";
			div.style.height	= orig_h+"px";
			dropdivs[div_id]	= 0;
			return true;
		}
		h	-= dropdiv_dropstep_px;
		setTimeout( func, dropdiv_dropstep_tm );
	};
	div.style.overflow	= "hidden";
	dropdivs[div_id]	= 2;
	func();
}
