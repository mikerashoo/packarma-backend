$('.has-sub').on('click',function(){
	$(this).toggleClass('open');
});
$('.menu-toggle').on('click',function(){
	$('body').toggleClass('menu-open');
	$('body').removeAttr('style');
	$('body').css('overflow','hidden');
});
$('#sidebarClose').on('click',function(){
	$('body').toggleClass('menu-open');
	$('body').removeAttr('style');
	$('body').css('overflow','auto');
});