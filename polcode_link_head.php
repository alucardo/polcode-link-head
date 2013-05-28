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
		$this->tabstat = $this->pluginname."_stat";

		add_action('init',  array($this, 'red'));

		//js for iframe
		wp_register_script('if_js', plugins_url( 'js/iframe-param.js', __FILE__ ), array('jquery'));
		wp_enqueue_script( 'if_js' );	

		//css files for iframes
		wp_register_style( 'if_css', plugins_url('css/if_css.css', __FILE__) );
    	wp_enqueue_style( 'if_css' );

    	

		//adding css for admin

		wp_register_style( 'linkhead', plugins_url('css/polcode_link_head.css', __FILE__) );
    	wp_enqueue_style( 'linkhead' );
		
		if(is_admin()) {
			add_action('admin_menu', array($this, 'initAdminAction'));

			//adding js files
			wp_register_script('phl_js', plugins_url( 'js/polcode_link_head.js', __FILE__ ), array('jquery'));
			wp_enqueue_script( 'phl_js' );		
		}
	}

	function red(){
		$link = $_SERVER["REQUEST_URI"];

		$i = strpos($link, 'red/');
		if($i>0){
		//var_dump($i);
			
			require( 'out.php' );
			
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

		




    	//adding menu elements
		add_menu_page('Link Head', 'Link Head', 'manage_options', 'polcode_link_head', array($this, 'indexAction'));
			//submenu
			//add_submenu_page('polcode_link_head', 'Polcode Link Header', 'Htaccess', 'manage_options', 'polcode_link_head_htaccess', array($this, 'htaAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Header Add', 'Link Add', 'manage_options', 'polcode_link_head_add', array($this, 'addAction') );
			add_submenu_page('polcode_link_head', 'Polcode Link Head Theme', 'Header theme', 'manage_options', 'polcode_link_head_theme', array($this, 'headerAction') );
			add_submenu_page('polcode_link_head', 'Theme add', 'Theme Add', 'manage_options', 'polcode_link_head_theme_add', array($this, 'addThemeAction') );
			add_submenu_page('polcode_link_head', 'Statistic', 'Statistic', 'manage_options', 'polcode_link_head_statistic', array($this, 'statisticAction') );
			add_submenu_page('polcode_link_head', 'Import', 'Import', 'manage_options', 'polcode_link_head_import', array($this, 'importAction') );
				//invisible link 
				add_submenu_page('polcode_link_head_head_add', 'Link delete', 'Delete', 'manage_options', 'polcode_link_head_delete', array($this, 'deleteAction') );
				add_submenu_page('polcode_link_head_head_add', 'Link edit', 'Edit', 'manage_options', 'polcode_link_head_edit', array($this, 'editAction') );
				add_submenu_page('polcode_link_head_head_add', 'Theme edit', 'Theme Edit', 'manage_options', 'polcode_link_head_theme_edit', array($this, 'editThemeAction') );
				add_submenu_page('polcode_link_head_head_add', 'Theme delete', 'Theme delete', 'manage_options', 'polcode_link_head_theme_delete', array($this, 'deleteThemeAction') );
				add_submenu_page('polcode_link_head_head_add', 'Reset all', 'Reset all', 'manage_options', 'polcode_link_head_reset_all', array($this, 'resetAllAction') );
				add_submenu_page('polcode_link_head_head_add', 'Reset', 'Reset', 'manage_options', 'polcode_link_head_reset', array($this, 'resetAction') );
				add_submenu_page('polcode_link_head_head_add', 'delete stat', 'delete stat', 'manage_options', 'polcode_link_head_stat_delete', array($this, 'statDeleteAction') );
				add_submenu_page('polcode_link_head_head_add', 'delete all links', 'delete all links', 'manage_options', 'polcode_link_head_stat_delete_all_links', array($this, 'allDeleteAction') );
				add_submenu_page('polcode_link_head_import', 'Import all', 'Import all', 'manage_options', 'polcode_link_head_import_all', array($this, 'importAllAction') );
				add_submenu_page('polcode_link_head_import', 'Import single', 'Import single', 'manage_options', 'polcode_link_head_import_single', array($this, 'importSingleAction') );
	}


	/********************** view action ************************************/

	function indexAction() {

		global $wpdb;

		//$this->getRows();

		$dblinks = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix.$this->tabred);

		include "theme/index.php";
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
			$this->addLine($_POST['code'], $_POST['link'], $_POST['to'], $_POST['them'], $_POST['rob'], $_POST['ifr']);
		}
		$themes = $this->getAllThemes();

		//var_dump($themes);

		include "theme/add.php";

	}

	function deleteAction(){
		global $wpdb;
		$id = $_GET['id'];
		$wpdb -> get_results('DELETE FROM '.$wpdb->prefix.$this->tabred.' WHERE id = '.$id);
		echo 'Line was deleted. <a href="';
		echo get_admin_url();
		echo 'admin.php?page=polcode_link_head">Back to main page</a>';
		$this->redirectAdmin();
	}

	function editAction(){
		$id = $_GET['id'];		
		global $wpdb;
		$dbd = $this->getRed($id);
		$themes = $this->getAllThemes();		
		$li = $dbd->link;
		$lif = $dbd->linkfrom;
		$th = $dbd->theme;
		$af = $dbd->aft;
		$ifr = $dbd->iframe;
		//var_dump($_POST);

		if(isset($_POST['link'])){			
			//var_dump($id);
			$wpdb->get_results("UPDATE ".$wpdb->prefix.$this->tabred." SET link ='".$_POST['link']."', theme = ".$_POST['them'].", aft= '".$_POST['aft']."', linkfrom = '".$_POST['linkfrom']."', iframe=".$_POST['ifr']." WHERE id = ".$id);

			
			$li = $_POST['link'];
			$lif = $_POST['linkfrom'];
			$th = $_POST['them'];
			$af = $_POST['aft'];
			$ifr = $_POST['ifr'];
			echo 'link edited';
		}// \ if /
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


	function statisticAction(){
		global $wpdb;
		$links = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.$this->tabstat.' ORDER BY last DESC');
		//var_dump($this->getLinkInfo(2));
		include 'theme/statistic.php';	
	}

	function resetAction() {
		$id = $_GET['id'];
		global $wpdb;
		$reset = $wpdb->get_results("UPDATE ".$wpdb->prefix.$this->tabstat." SET visit = 0, last = 0 WHERE id = ".$id);
		//$r = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabstat." WHERE id = ".$id);
		//var_dump($r);
		$this->redirectAdmin("_statistic");
	}

	function resetAllAction() {
		global $wpdb;
		$reset = $wpdb->get_results("UPDATE ".$wpdb->prefix.$this->tabstat." SET visit = 0, last = 0 ");
		$this->redirectAdmin("_statistic");
	}

	function statDeleteAction(){
		global $wpdb;
		$id = $_GET['id'];
		$wpdb -> get_results('DELETE FROM '.$wpdb->prefix.$this->tabstat.' WHERE id = '.$id);
		$this->redirectAdmin("_statistic");
	}

	function importAction() {
		global $wpdb;
		$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."prli_links ");
		include 'theme/import.php';	
	}

	function allDeleteAction() {
		global $wpdb;
		$wpdb -> get_results('DELETE FROM '.$wpdb->prefix.$this->tabstat);
		$wpdb -> get_results('DELETE FROM '.$wpdb->prefix.$this->tabred);
		$this->redirectAdmin("");
	}

	function importAllAction() {
		global $wpdb;
		$theme = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabtheme." LIMIT 0, 1");
		$imports = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."prli_links ");
		//var_dump($theme);

		
		$this->openHtaccess('a+');
		foreach ($imports as $import) {
			// add to table
			$ins = $wpdb->insert( $wpdb->prefix.$this->tabred, array('link'=>$import->url, 'theme'=>$theme->id, 'aft'=>'', 'linkfrom'=>"/".$import->slug));
			// add htacces 
			$link = "Redirect 301 ".$_SERVER['SERVER_NAME']."/".$import->slug." /red/".$import->slug." \n";
			fwrite($this->file, $link);
		}
		$this->closeHtaccess();
		$this->redirectAdmin("");
		
	}


	function importSingleAction() {
		global $wpdb;
		//var_dump($_POST);
		$theme = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabtheme." LIMIT 0, 1");
		$imports = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."prli_links ");
		$this->openHtaccess('a+');
		foreach ($_POST as $k => $l) {
			foreach ($imports as $import) {				
				if($import->id == $l ) {
					//echo $l."<br>";
					$ins = $wpdb->insert( $wpdb->prefix.$this->tabred, array('link'=>$import->url, 'theme'=>$theme->id, 'aft'=>'', 'linkfrom'=>"/".$import->slug));
					$link = "Redirect 301 ".$_SERVER['SERVER_NAME']."/".$import->slug." /red/".$import->slug." \n";
					fwrite($this->file, $link);
				}
			}			
		}
		$this->closeHtaccess();
		$this->redirectAdmin("");


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
	private function addLine($code, $line, $to, $theme=0, $aft="", $ifr=1){
		$this->openHtaccess('r+');
		//prepare link 

		/*
		*	if 1 = use header
		*	else use simple Redirect
		*/

		if($code == '1'){

			$l = $this->addRed($to, $theme, $aft, $line, $ifr);
			$pid = $this->getOption('postid');
			//$link ="Redirect 301 /red{$line} /?page_id={$pid}&link={$l} \n";
			//$link = "RewriteRule ^/red{$line}/$ /?page_id={$pid}&link={$l} \n";

		}
		else{
			$link = "Redirect {$code} {$line} {$to} \n";
		
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

		$this->closeHtaccess();
		}
		echo 'line added';
		
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


	private function addRed($link, $theme, $aft, $linkfrom, $ifr) {
		global $wpdb;
		$rows_affective = $wpdb->insert( $wpdb->prefix.$this->tabred, array('link'=>$link, 'linkfrom'=>$linkfrom , 'theme'=>$theme, 'aft'=>$aft, 'iframe'=>$ifr));
		return $wpdb->insert_id;
	}

	//gets names 
	function getLinkInfo($id){
		global $wpdb;

		$rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabred." WHERE id = ".$id);
		//echo 'test';
		return $rows[0];
	}


	
	//gets single theme by id
	function getThemeById($ids){
		global $wpdb;
		$theme = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabtheme." WHERE id = ".$ids);
		
		return $theme[0];
	}

	//get red db id by id in ht
	function getRedById($str) {
		$pos = strpos($str, 'link=');		
		if($pos!=false){
			return substr($str, $pos+5);			
		}
		else {
			return 0;
		}
	}

	function getRed($id){
		global $wpdb;
		$ent = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix.$this->tabred." WHERE id =".$id);
		//var_dump($ent);
		
		return $ent[0];
	}

	function redirectAdmin($url=""){
		$path = get_admin_url()."admin.php?page=polcode_link_head".$url;
		echo "<script>window.location='".$path."'</script>";
	}


	//install 
	private function install(){
		//create table for themes
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


		//create theme header table

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
			`linkfrom` varchar(200) NULL,
			`theme` varchar(200) NOT NULL,			
			`aft` varchar(200) NOT NULL,
			`iframe` tinyint NULL,			
			PRIMARY KEY (`id`)
			) ENGINE =MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		dbDelta($table2);

		// create statistic table

		$table3 = "CREATE TABLE ".$wpdb->prefix.$this->tabstat."(
			`id` int(9) NOT NULL auto_increment,
			`link` int(9) NOT NULL,
			`visit` int(11) NOT NULL,
			`last` int(11) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE =MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		dbDelta($table3);


		//add info to robbot.txt
		
		$this->openHtaccess('a', "robots.txt");
			fwrite($this->file, "\nDisallow: /red/");
		$this->closeHtaccess();
		


		//add line to .htaccess
		/*
		$this->openHtaccess('a');
			fwrite($this->file, "\n# polcode_link_head start \n");
			fwrite($this->file, "# polcode_link_head end \n");
		$this->closeHtaccess();
		*/

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