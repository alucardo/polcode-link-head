<?php 

/* 
	Plugin Name: Polcode Link Head
	Plugin Uri:
	Description: Plugin create pretty links
	Version: 1.0
	Author: Przemysław Olesiński
*/

//include required class


class polcode_link_head {


	
	function __construct(){

		//css init
		wp_register_style( 'linkhead', plugins_url('css/polcode_link_head.css', __FILE__) );
    	wp_enqueue_style( 'linkhead' );



		// init for admin site
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAction'));
			
		}
	}

	function initAction() {
		//main link
		add_menu_page('Polcode Link', 'Polcode Link', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));
			//sub page
			add_submenu_page('polcode_link_head', 'Htaccess View', 'Htaccess', 'manage_options', 'polcode_link_head_htaccess', array($this, 'htaAction') );
	}


	/*************************** View Action ***********************************/

	function indexAction(){

		$this->theme('index');
	}

	function htaAction(){

		$this->theme('hta');	
	}


	/************************* helpers ******************************************/


	function theme($name){
		require "theme/{$name}.php";
	}




}

$plh =  new polcode_link_head();


?>