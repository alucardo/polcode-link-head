jQuery(document).ready(function($){
//alert('test');
	jQuery("iframe.iframe").width(jQuery(window).width());
	jQuery("iframe.iframe").height((jQuery(window).height() - jQuery("#headerCntr").outerHeight()));

	jQuery('#polcode_close').click(function(e){
		jQuery(".polcode_box").hide();
		e.preventDefault();
	});

});