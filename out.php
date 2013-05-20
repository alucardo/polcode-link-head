<?php
/**
 * Template Name: Out page
 *
 * @package WordPress
 * @subpackage Fashion Sale Alerts
 */?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, maximum-scale=1" />
<title><?php wp_title(""); ?></title>

<!-- TradeDoubler site verification 2261757 -->
<?php
    wp_head();
?>



<!--[if lt IE 9]>
<script src="<?= get_template_directory_uri() ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

</head>

 <?php

    global $wpdb;
    $link = $_SERVER["REQUEST_URI"];
    $link = substr($link, 4);
    $idls = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."polcode_link_head_red WHERE linkfrom = '".$link."'");
    $idl = $idls[0]->id;
    $head_text =  $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_theme WHERE id = '.$idls[0]->theme); 
    //var_dump($idls);

    //statistic

    //get visits
    $link_visit = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_stat WHERE link = '.$idl);
    //$date = new DateTime();
    if($link_visit==null){

       
        $wpdb->insert($wpdb->prefix.'polcode_link_head_stat', array('link'=>$idl, 'visit'=>1, 'last'=>time()));
       
    }
    else {
        $x = $link_visit[0]->visit;
        $x++;
        $wpdb -> get_results("UPDATE ".$wpdb->prefix."polcode_link_head_stat SET visit = ".$x.", last = ".time()." WHERE link = ".$idl);

    }
?>


     <body>
        <div class="polcode_box">
            <p>
                  <?php 
                    echo $head_text[0]->des;
                    
                ?>
            </p>
            <a href="#" id="polcode_close">Sluiten<span>×</span></a>
        </div>
        <iframe class="iframe" src="<?php echo $idls[0]->link; ?>" sandbox=""><?php echo $idls[0]->aft; ?></iframe>
    </body>
</html>

<?php 
    exit();
?>