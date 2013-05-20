jQuery(document).ready(function(){
	
	jQuery('.he').hide();
	jQuery('#headtyp').change(function(){
		 jQuery("select#headtyp option:selected").each(function () {
			if(jQuery(this).val()==1){
				jQuery('.he').show();
			}
			else{
				jQuery('.he').hide();
			}
		});		
	});// change

	jQuery('#selall').click(function(event){
		event.preventDefault();
		jQuery('.polcode_link_head input[type="checkbox"]').prop('checked', true);

	});

	jQuery('#deall').click(function(event){
		event.preventDefault();
		jQuery('.polcode_link_head input[type="checkbox"]').prop('checked', false);

	});


});

