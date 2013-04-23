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


	function initAction() {

		wp_register_style( 'linkhead', plugins_url('css/polcode_link_head.css', __FILE__) );
    	wp_enqueue_style( 'linkhead' );


		add_menu_page('Link Head', 'Link Head', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));
			//submenu
			add_submenu_page('polcode_link_head', 'Polcode Link Header', 'Htaccess', 'manage_options', 'polcode_link_head_htaccess', array($this, 'htaAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Header Add', 'Link Add', 'manage_options', 'polcode_link_head_add', array($this, 'addAction') );
	}


	/********************** view action ************************************/

	function indexAction() {
		$this->getRows();
		$this->themeHelper('index');
		
	}

	function htaAction() {
		$this->openHtaccess('r');
			$tresc = fread($this->file, filesize($this->htpath));		
		$this->closeHtaccess();
		include "theme/hta.php";
	}

	function addAction(){
		//adding line
		if(isset($_POST['line'])){
			$this->addLine($_POST['code'], $_POST['line'], $_POST['to']);
		}

		include "theme/add.php";

	}


	/******************** helpers ***************************************/

	function themeHelper($name){
		include "theme/{$name}.php";
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

	function addLine($code, #line, $to){
		$this->openHtaccess('r+');

		$this->closeHtaccess();
	}
}

$plh =  new polcode_link_head();


?>