<?php
/**
 * Template Name: Out page
 *
 * @package WordPress
 * @subpackage Fashion Sale Alerts
 */?>
<!DOCTYPE html >
<html >
    <head profile="http://gmpg.org/xfn/11">
        <meta name="verification" content="" />
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title>     </title>       
        <link rel="author" href="" />  
        <?php wp_head(); ?>
    </head>

 <?php

//global $wpdb;

$idl = $_GET['link'];
$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_red WHERE id = '.$idl);
//var_dump($data);

    
$head_text =  $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_theme WHERE id = '.$data[0]->theme); 

//statistic

    //get visits
    $link_visit = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_stat WHERE link = '.$idl);
    $date = new DateTime();
    if($link_visit==null){

       
        $wpdb->insert($wpdb->prefix.'polcode_link_head_stat', array('link'=>$idl, 'visit'=>1, 'last'=>$date->getTimestamp()));
       
    }
    else {
        $x = $link_visit[0]->visit;
        $x++;
        $wpdb -> get_results("UPDATE ".$wpdb->prefix."polcode_link_head_stat SET visit = ".$x.", last = ".$date->getTimestamp()." WHERE link = ".$idl);

    }

    

?>
     <body>
        <div class="polcode_box">
            <p>

                <?php 
                    echo $head_text[0]->des;
                    //var_dump($link_visit);
                    
                ?>
            </p>
            <a href="#" id="polcode_close">X</a>
        </div>
       <iframe class="iframe" src="<?php echo $data[0]->link; ?>" sandbox=""><?php echo $data[0]->aft; ?></iframe>
    </body>
</html>