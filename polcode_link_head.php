<?php 

/* 
	Plugin Name: Polcode Link Head
	Plugin Uri:
	Description: Plugin create pretty links
	Version: 1.0
	Author: Przemysław Olesiński
*/

class polcode_link_head {

	private $htpath;
	private $file;
	private $entries;

	function __construct() {		
		$this->htpath = $_SERVER["DOCUMENT_ROOT"].'/.htaccess';
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAction'));
		}
	}

	function openHtaccess($typ) {
		try {
			$this -> file = fopen($this->htpath, $typ);
		}
		catch( Exception $e) {
			echo 'File open error';
		}
	}

	function closeHtaccess(){
		fclose($this -> file);
	}

	function initAction() {
		add_menu_page('Link Head', 'Link Head', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));

	}

	function getRows(){
		$this->openHtaccess('r');
			$this->entries= array();
			$start = 0;
			$x = 0;
			while (($buffer = fgets($this->file, filesize($this->htpath))) !== false) {  
        		if($start == 0){
        			$in = strpos($buffer, 'polcode_link_head start');
        			if($in>0) {
        				$start = 1;
        			}
        		}
        		else {
        			$in = strpos($buffer, 'polcode_link_head end');
        			if($in>0) {
        				break;
        			}
        			if(strlen($buffer)>1) {
        				$this->entries[$x]=$buffer;
        				$x++;
           			}
        		}
    		}


		$this->closeHtaccess();
	}

	/********************** view action ************************************/

	function indexAction() {
		$this->getRows();

		$this->themeHelper('index');
		
	}


	/******************** helpers ***************************************/

	function themeHelper($name){
		include "theme/{$name}.php";
	}

}

$plh =  new polcode_link_head();


?>