// JavaScript Document

$(function(){
$(document).bind("contextmenu",function(ev){     
	var ev=ev || window.event;
	var evTarget=ev.srcElement.tagName.toLowerCase();
	var x,y;
	if (document.all) {
		if(evTarget=="input" || evTarget=="textarea")
		return;
		x=ev.clientX + document.body.scrollLeft;
		y=ev.clientY + document.body.scrollTop;
	}else{
		if(evTarget=="input" || evTarget=="textarea")
		return;
		x=ev.pageX;
		y=ev.pageY;
	}
	x-=15;
	y-=15;	
	$("ul.v_menu").css({
		position:"absolute",
		left:x,
		top:y
	});
	$("ul.v_menu").show();
	return false;
});
 
	$("ul.v_menu").hover(function(){
 
	},function(){
		$("ul.v_menu").hide();
	});
	$("ul.v_menu").click(function(){
		$("ul.v_menu").hide();	
	});	
	
});