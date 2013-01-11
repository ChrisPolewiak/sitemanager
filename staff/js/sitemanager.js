var filename = window.location.href.match(/.*\/(.*php)/);

$('.fieldset-title').die();
$('.fieldset-title').live('click',function(){
	var boxid = $(this).attr('id');
	var icon = $(this).find('i');

	var filename = window.location.href.match(/.*\/(.*php)/);

	if(icon.hasClass('icon-minus')) {
		el = $(this).next();
		el.hide();
		$(this).css({marginBottom: '10px'});
		icon.removeClass('icon-minus');
		icon.addClass('icon-plus');
		if(boxid)
			$.cookie('smbox-'+boxid+'-'+filename, 'hide');
	}
	else if(icon.hasClass('icon-plus')) {
		el = $(this).next();
		el.show();
		$(this).css({marginBottom: '0'});
		icon.removeClass('icon-plus');
		icon.addClass('icon-minus');
		if(boxid)
			$.cookie('smbox-'+boxid+'-'+filename, 'show');
	}
});

$('#tabs a').die();
$('#tabs a').live('click',function(e){
	e.preventDefault();
	$(this).tab('show');
	tabid = $(this).attr('href').substr(1);
	var filename = window.location.href.match(/.*\/(.*php)/);
	$.cookie('tabs-'+filename[1], tabid);
});

$('body').ready(function(){

	$('.fieldset-title').find('i').parent().addClass('cursor-pointer');

	$.each(document.cookie.split(/; */),function() {
		var splitCookie = this.split('=');
		if( smboxstr = splitCookie[0].match(/^smbox\-(.+)\-(.+)/) ) {
			if( filename == smboxstr[2]) {
				if (splitCookie[1] == 'hide' ) {
					smbox_id = smboxstr[1];
					smbox_value = splitCookie[1];
					el = $('#'+smbox_id).next();
					el.hide();
					$('#'+smbox_id).css({marginBottom: '10px'});
					icon = $('#'+smbox_id).find('i');
					icon.removeClass('icon-minus');
					icon.addClass('icon-plus');
				}
			}
		}

		if( cookiestr = splitCookie[0].match(/^tabs\-(.+)/) ) {
			if( filename[1] == cookiestr[1]) {
				var tabid = splitCookie[1];
				$('#tabs a[href="#'+tabid+'"]').tab('show');

			}
		}
	});

});
