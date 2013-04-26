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
	private $tabred;
	private $pluginname;
	const VER = 0.1;

	function __construct() { 
		$this->pluginname = "polcode_link_head";
		$this->tabtheme = $this->pluginname."_theme";
		$this->tabred = $this->pluginname."_red";


		if(is_page($this->getOption(postid))) {

		}

		/*
		add_filter( 'page_template', 'wpa3396_page_template' );
		function wpa3396_page_template( $page_template )
		{
		    if ( is_page( 'my-custom-page-slug' ) ) {
		        $page_template = dirname( __FILE__ ) . '/custom-page-template.php';
		    }
		    return $page_template;
		}
		*/
		
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAdminAction'));
		}
	}


	function initAdminAction() {


		//check if plugin is first use
		if(!$this->getOption('installed')) {
			$this->setOption('installed', 'N');
			echo 'Plugin Polcode Link Head is not installed. Please refresh page.';
		}

		//checking if it is installed
		if($this->getOption('installed')=='N'){
			$this->install();
		}

		//adding js files
		wp_register_script('phl_js', plugins_url( 'js/polcode_link_head.js', __FILE__ ));
		wp_enqueue_script( 'phl_js' );		

		//adding css for admin

		wp_register_style( 'linkhead', plugins_url('css/polcode_link_head.css', __FILE__) );
    	wp_enqueue_style( 'linkhead' );




    	//adding menu elements
		add_menu_page('Link Head', 'Link Head', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));
			//submenu
			add_submenu_page('polcode_link_head', 'Polcode Link Header', 'Htaccess', 'manage_options', 'polcode_link_head_htaccess', array($this, 'htaAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Header Add', 'Link Add', 'manage_options', 'polcode_link_head_add', array($this, 'addAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Head Theme', 'Header theme', 'manage_options', 'polcode_link_head_theme', array($this, 'headerAction') );
			add_submenu_page('polcode_link_head', 'Theme add', 'Theme Add', 'manage_options', 'polcode_link_head_theme_add', array($this, 'addThemeAction') );
				//invisible link 
				add_submenu_page('polcode_link_head_htaccess', 'Link delete', 'Delete', 'manage_options', 'polcode_link_head_delete', array($this, 'deleteAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Link edit', 'Edit', 'manage_options', 'polcode_link_head_edit', array($this, 'editAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Theme edit', 'Theme Edit', 'manage_options', 'polcode_link_head_theme_edit', array($this, 'editThemeAction') );
				add_submenu_page('polcode_link_head_htaccess', 'Theme delete', 'Theme delete', 'manage_options', 'polcode_link_head_theme_delete', array($this, 'deleteThemeAction') );
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
			$this->addLine($_POST['code'], $_POST['link'], $_POST['to'], $_POST['them'], $_POST['rob']);
		}
		$themes = $this->getAllThemes();

		//var_dump($themes);

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
			$this->addTheme($_POST['nametheme'], $_POST['des']);
			echo 'Theme added';
		}
		include "theme/addtheme.php";
	}

	function deleteThemeAction() {
		$id = $_GET['id'];
		global $wpdb;
		$wpdb -> get_results('DELETE FROM '.$wpdb->prefix.$this->tabtheme.' WHERE id = '.$id); 
			echo 'Theme was deleted. <a href="';
			echo get_admin_url();
			echo 'admin.php?page=polcode_link_head_theme">Back to themes page</a>';
		
		//echo 'del';

	}

	function editThemeAction() {
		$id = $_GET['id'];
		global $wpdb;
		if(isset($_POST['nametheme'])) {
			$wpdb -> get_results("UPDATE ".$wpdb->prefix.$this->tabtheme." SET name = '".$_POST['nametheme']."', des='".$_POST['des']."' WHERE id = ".$id);
		}
		$theme = $wpdb -> get_results('SELECT * FROM '.$wpdb->prefix.$this->tabtheme.' WHERE id = '.$id);
		//var_dump($theme);
		include 'theme/edittheme.php';  
	}

	/******************** helpers ***************************************/

	// not working with external var
	private function themeHelper($name){
		include "theme/{$name}.php";
	}

	//opening htaccess file with $typ
	private function openHtaccess($typ, $f=".htaccess") {

		$this->htpath = $_SERVER["DOCUMENT_ROOT"].'/'.$f;

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
	private function addLine($code, $line, $to, $theme=0, $aft=""){
		$this->openHtaccess('r+');
		//prepare link 

		/*
		*	if 1 = use header
		*	else use simple Redirect
		*/

		if($code == '1'){

			$l = $this->addRed($to, $theme, $aft);

			$link ="Redirect 301 /red{$line} /link={$l} \n";


		}
		else{
			$link = "Redirect {$code} {$line} {$to} \n";
		}
		//echo $link
		$x = 0; // itteratorfor tab
		$tab; //table with all .htacces lines
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
		//save file with new line
		for($i=0; $i<sizeof($tab); $i++){

			fwrite($this->file, $tab[$i]);
			//echo $tab[$i].'<br>';
		}
		echo 'line added';
		$this->closeHtaccess();
		
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

	private function getAllThemes() {
		global $wpdb;
		$themes = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.$this->tabtheme);
		//var_dump($themes);
		return $themes;
	}


	private function addRed($link, $theme, $aft) {
		global $wpdb;
		$rows_affective = $wpdb->insert( $wpdb->prefix.$this->tabred, array('link'=>$link, 'theme'=>$theme, 'aft'=>$aft));
		return $wpdb->insert_id;
	}


	//install 
	private function install(){
		//create table for themes
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


		$table = "CREATE TABLE ".$wpdb->prefix.$this->tabtheme."(
			`id` int(9) NOT NULL auto_increment,
			`name` varchar(200) NOT NULL,
			`des` text NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE =MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";


		dbDelta($table);

		//create table for redirect info


		$table2 = "CREATE TABLE ".$wpdb->prefix.$this->tabred."(
			`id` int(9) NOT NULL auto_increment,
			`link` varchar(200) NOT NULL,
			`theme` varchar(200) NOT NULL,			
			`aft` varchar(200) NOT NULL,			
			PRIMARY KEY (`id`)
			) ENGINE =MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		dbDelta($table2);


		//add info to robbot.txt
		$this->openHtaccess('a', "robots.txt");
			fwrite($this->file, "\nDisallow: /red/");
		$this->closeHtaccess();



		//add line to .htaccess
		$this->openHtaccess('a');
			fwrite($this->file, "\n# polcode_link_head start \n");
			fwrite($this->file, "# polcode_link_head end \n");
		$this->closeHtaccess();


		//prepare new post

		$my_post = array(
		  'post_title'    => '',
		  'post_content'  => ' ',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_name'   => '/red',
		  'post_type'   => 'page' 
		);	


		//add post and option with new post id
		$p = wp_insert_post( $my_post );
		echo 'Id nowego posta: '.$p.'<br>';

		$this->setOption('postid', $p);


		$this->editOption('installed', 'T');
	}

}

$plh =  new polcode_link_head();


?>