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
			add_submenu_page('polcode_link_head', 'Polcode Link Head Theme', 'Header theme', 'manage_options', 'polcode_link_head_theme', array($this, 'headAction') );
				//invisible link 
				add_submenu_page('polcode_link_head_htaccess', 'Link delete', 'Delete', 'manage_options', 'polcode_link_head_delete', array($this, 'deleteAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Link edit', 'Edit', 'manage_options', 'polcode_link_head_edit', array($this, 'editAction') );
	}


	/********************** view action ************************************/

	function indexAction() {
		$this->getRows();
		$this->themeHelper('index');
	}

	function htaAction() {
		if(isset($_POST['hta'])) {
			$this->openHtaccess('w');
				fwrite($this->file, $_POST['hta']);
			$this->closeHtaccess();
		}


		$this->openHtaccess('r');
			$tresc = fread($this->file, filesize($this->htpath));		
		$this->closeHtaccess();
		include "theme/hta.php";
	}

	function addAction(){
		//adding line
		if(isset($_POST['link'])){
			$this->addLine($_POST['code'], $_POST['link'], $_POST['to']);
		}

		include "theme/add.php";

	}

	function deleteAction(){
		$id = $_GET['id'];
		$this->getRows();
		$del =  $this->entries[$id];
		//create new tab to be save in .htaccess
		$this->openHtaccess('r');
		$tab;
		$x = 0;
		while (($buffer = fgets($this->file, filesize($this->htpath))) !== false) {  

			if($buffer == $del) {
				continue;
			}
			$tab[$x] = $buffer;
			$x++;

		}
		$this->closeHtaccess();

		//saving 
		$this->openHtaccess('w');
		for($i=0; $i<sizeof($tab); $i++){
			fwrite($this->file, $tab[$i]);
		}
		$this->closeHtaccess();
		echo 'Line was deleted. <a href="';
		echo get_admin_url();
		echo 'admin.php?page=polcode_link_head">Back to main page</a>';
	}

	function editAction(){
		$id = $_GET['id'];
		$this->getRows();
		$tresc =  $this->entries[$id];
		if(isset($_POST['editlink'])){
			//echo 'edit start<br>';
			$this->openHtaccess('r');
			$tab;
			$x = 0;
			while (($buffer = fgets($this->file, filesize($this->htpath))) !== false) {  
				if($buffer == $tresc) {					
					$tab[$x] = $_POST['editlink']."\n";
				}
				else
				{
					$tab[$x] = $buffer;
				}
				$x++;			
			}
			$this->closeHtaccess();
			//echo 'saving new file';
			
			$this->openHtaccess('w');
			for($i=0; $i<sizeof($tab); $i++){
				fwrite($this->file, $tab[$i]);
				//echo $tab[$i].'<br>';

			}
			$this->closeHtaccess();
			$tresc = $_POST['editlink'];
			echo 'link edited';
			//redirect after edit
			//$path = get_admin_url()."?page=polcode_link_head";
			//wp_redirect( $location );
		}
		include "theme/edit.php";
	}

	function headerAction(){

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
			//$this->entries= array();
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

	function addLine($code, $line, $to){
		$this->openHtaccess('r+');
		//prepare link 
		$link = "Redirect {$code} {$line} {$to} \n";
		//echo $link
		$x = 0;
		$tab;
		while (($buffer = fgets($this->file, filesize($this->htpath))) !== false) { 
			//checking if it is end of plugin part 
			$in = strpos($buffer, 'polcode_link_head end');
			if($in>0) {
				$tab[$x] = $link;
				$x++;				
			}
			$tab[$x] = $buffer;
			$x++;
		}
		$this->closeHtaccess();


		$this->openHtaccess('w');
		//save file 
		for($i=0; $i<sizeof($tab); $i++){
			//echo $tab[$i];
			//echo '<br>';
			fwrite($this->file, $tab[$i]);
		}

		echo 'line added';



		$this->closeHtaccess();
	}
}

$plh =  new polcode_link_head();


?>