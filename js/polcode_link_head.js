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
	});
});

