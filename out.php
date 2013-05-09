<?php
/**
 * Template Name: Out page
 *
 * @package WordPress
 * @subpackage Fashion Sale Alerts
 */

global $wpdb;

$id = $_GET['link'];
$data = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_red WHERE id = '.$id);

$head_text =  $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'polcode_link_head_theme WHERE id = '.$data[0]->theme); 

?>
<!DOCTYPE html >
<html >
    <head profile="http://gmpg.org/xfn/11">
        <meta name="verification" content="" />
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title>     </title>       
        <link rel="author" href="" />  
        <?php wp_head(); ?>
    </head>
     <body>
        <div class="polcode_box">
            <p>
                <?php 
                    echo $head_text[0]->des;
                ?>
            </p>
            <a href="#" id="polcode_close">X</a>
        </div>
       <iframe class="iframe" src="<?php echo $data[0]->link; ?>" sandbox=""><?php echo $data[0]->aft; ?></iframe>
    </body>
</html>