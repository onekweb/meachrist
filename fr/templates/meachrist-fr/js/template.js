/* template.js*/
$(document).ready( function(){	
						$("#mainmenu li.parent").hover( function(){
						$(this).find('ul').fadeIn(200);
						}, function(){
						$(this).find('ul').fadeOut(200);
						});

});
