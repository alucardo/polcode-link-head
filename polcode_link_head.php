<?php 

/* 
	Plugin Name: Polcode Link Head
	Plugin Uri:
	Description: Plugin create pretty links
	Version: 1.0
	Author: Przemysław Olesiński
*/

class polcode_link_head {
	
	function __construct(){

	
		// init for admin site
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAction'));
			
		}
	}

	function initAction() {
		
	}

	/************************* View Action *****************************/


}

$plh =  new polcode_link_head();


?>