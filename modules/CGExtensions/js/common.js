/* make buttons with links inside behave properly */
$(function(){
    $('button[data-href]').click(function(ev){
	ev.preventDefault();
	let href = $(this).data('href');
	if( href.length > 0 ) window.location.href = href;
    })
})
