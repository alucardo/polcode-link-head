<?php 

/* 
	Plugin Name: Polcode Link Head
	Plugin Uri:
	Description: Plugin create pretty links
	Version: 1.0
	Author: Przemysław Olesiński
*/

//include required class

	require 'class/htaccess.polcode.php';



class polcode_link_head {


	//iner class var
	$hta;

	
	function __construct(){

		$hta = new htaccess_polcode();
	
		// init for admin site
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAction'));
			
		}
	}

	private function initAction() {
		
	}

	/************************* View Action *****************************/

	//main plugin admin view
	private function indexAction() {

	}


	//htaccess view 
	private function htaAction() {

	}

	//header view
	private function headAction() {

	}


	/************************* get | set | add | back *************************/



	//gets all links
	private function getAllLinks() {

	}

	//gets single link 
	private function getLink($id) {

	}

	//set link
	private function setLink($id) {

	}

	//add link
	private function addLink() {

	}


	//get value from wp-option
	private function getOption($name) {

	}

	//get header
	private function getHeader($id) {

	}

	//set header
	private function setHeader($id) {

	}

	//add header
	private function addHeader() {

	}


	/******************** helpers ***********************************/




}

$plh =  new polcode_link_head();


?>