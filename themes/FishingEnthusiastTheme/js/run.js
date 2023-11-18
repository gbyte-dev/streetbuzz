function rollEffects(){
if($(window).width() > 992 ){
$(".comment-ctrl").hover(
  function () {
    $(this).find(".activity-options,.comment-options").removeClass("hidden-opt");
  }, function() {
    $(this).find(".activity-options,.comment-options").addClass("hidden-opt");
  }
);
}


}
$(document).ready(function(){
/*$(".main-navigation a").addClass("btn btn-block btn-lg btn-primary");*/
//$(".navigation a").addClass("btn btn-xs btn-primary");
//$(".tabs-navigation a").addClass("btn btn-xs btn-default");
$(".delete").append("<span class='glyphicon glyphicon-remove'></span>");
$(".bookmark").append("<span class='glyphicon glyphicon-check'></span>");
$(".add-comment").prepend("<span class='glyphicon glyphicon-comment'></span>")

$(".toggle_left_menu").click(function(){
$("#left-area").toggleClass("hidden-xs hidden-sm" );
if($(".navbar-collapse").hasClass("in")){
$(".navbar-collapse").removeClass("in");
}

});

$(".navbar-toggle").click(function(){
if($("#left-area").hasClass("hidden-xs hidden-sm")){
}else{
$("#left-area").toggleClass("hidden-xs hidden-sm");
}


});
$(".searchselect .menu-btn, .dropdown").click(function(){

if($(window).width() < 768 ){
$(".top30").css("height","265px");
$(this).removeClass("expanded-header");	
}
});

rollEffects();
//$(".tabs-navigation").after("<div class='clear'></div>"); 
});
$(document ).ajaxComplete(function(){
rollEffects();
$(".comment-options").each(function(){
	if($(this).find("span.glyphicon").length > 0){
	}else{
	$(this).find(".delete").append("<span class='glyphicon glyphicon-remove'></span>");
	$(this).find(".bookmark").append("<span class='glyphicon glyphicon-check'></span>");
	}

});
$(".comment-options,.activity-options").each(function(){
	if($(this).find("span.glyphicon").length > 0){
	}else{
	$(this).find(".delete").append("<span class='glyphicon glyphicon-remove'></span>");
	$(this).find(".bookmark").append("<span class='glyphicon glyphicon-check'></span>");
	}

});





});