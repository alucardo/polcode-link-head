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
	private $tabtheme;
	private $pluginname;
	const VER = 0.1;

	function __construct() {		
		$this->pluginname = "polcode_link_head";
		$this->tabtheme = $this->pluginname."_theme";
		$this->htpath = $_SERVER["DOCUMENT_ROOT"].'/.htaccess';
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAction'));
		}
	}


	function initAction() {


		//check if plugin is install
		if(!$this->getOption('installed')) {
			$this->setOption('installed', 'N');
			echo 'Plugin is not installed. Please refresh page.';
		}

		if($this->getOption('installed')=='N'){
			$this->install();
		}




		wp_register_style( 'linkhead', plugins_url('css/polcode_link_head.css', __FILE__) );
    	wp_enqueue_style( 'linkhead' );


		add_menu_page('Link Head', 'Link Head', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));
			//submenu
			add_submenu_page('polcode_link_head', 'Polcode Link Header', 'Htaccess', 'manage_options', 'polcode_link_head_htaccess', array($this, 'htaAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Header Add', 'Link Add', 'manage_options', 'polcode_link_head_add', array($this, 'addAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Head Theme', 'Header theme', 'manage_options', 'polcode_link_head_theme', array($this, 'headerAction') );
				//invisible link 
				add_submenu_page('polcode_link_head_htaccess', 'Link delete', 'Delete', 'manage_options', 'polcode_link_head_delete', array($this, 'deleteAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Link edit', 'Edit', 'manage_options', 'polcode_link_head_edit', array($this, 'editAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Theme add', 'Theme Add', 'manage_options', 'polcode_link_head_theme_add', array($this, 'addThemeAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Theme edit', 'Theme Add', 'manage_options', 'polcode_link_head_theme_edit', array($this, 'editThemeAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Theme delete', 'Theme Add', 'manage_options', 'polcode_link_head_theme_delete', array($this, 'deleteThemeAction') );
	}


	/********************** view action ************************************/

	function indexAction() {
		//echo 'Install:<br>';
		//$this->install();
		//echo '<br>';
		$this->getRows();
		$this->themeHelper('index');
	}

	function htaAction() {
		if(isset($_POST['hta'])) {
			//$forsave =  stripcslashes($_POST['hta']); 
			$this->openHtaccess('w');
				fwrite($this->file, stripcslashes($_POST['hta']));
			$this->closeHtaccess();
			//echo $forsave;
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
		global $wpdb;
		$rows = $wpdb -> get_results("SELECT * FROM ".$wpdb->prefix.$this->tabtheme);

		include "theme/theme.php";
	}


	function addThemeAction(){
		if(isset($_POST['nametheme'])){
			$this->addTheme($_POST['nametheme'], $_POST['desc']);
			echo 'Theme added';
		}
		include "theme/addtheme.php";
	}

	/******************** helpers ***************************************/

	// not working with external var
	private function themeHelper($name){
		include "theme/{$name}.php";
	}

	//opening htaccess file with $typ
	private function openHtaccess($typ) {
		try {
			$this -> file = fopen($this->htpath, $typ);
		}
		catch( Exception $e) {
			echo 'File open error';
		}
	}

	//closing file saved in file handler
	private function closeHtaccess(){
		fclose($this -> file);
	}

	//create table with plugin rows from htaccess
	private function getRows(){
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

	// adds line to .htaccess
	// only redairect now
	private function addLine($code, $line, $to){
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

			fwrite($this->file, $tab[$i]);
			//echo $tab[$i].'<br>';
		}

		echo 'line added';



		//$this->closeHtaccess();
	}

	//adding theme to database
	private function addTheme($name, $des){
		global $wpdb;
		//adding row by build in wp method
		$rows_affective = $wpdb->insert( $wpdb->prefix.$this->tabtheme, array('name'=>$name, 'des'=>$des));
	}




	//get curent url
	private function getCurlUrl(){
		return $_SERVER['REQUEST_URI'];
	}

	//gets option with prefix
	private function getOption($name) {
		return get_option($this->pluginname.'_'.$name);
	}

	//set new option with prefix
	private function setOption($name, $val) {
		add_option($this->pluginname.'_'.$name, $val);
	}

	//edit option with prefix
	private function editOption($name, $val){
		update_option($this->pluginname.'_'.$name, $val);
	}

	//install 
	private function install(){
		//create table for themes
		global $wpdb;
		$table = "CREATE TABLE ".$wpdb->prefix.$this->tabtheme."(
			`id` int(9) NOT NULL auto_increment,
			`name` varchar(200) NOT NULL,
			`des` text NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE =MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		//echo $table;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($table);

		$this->editOption('installed', 'T');
	}

}

$plh =  new polcode_link_head();


?>