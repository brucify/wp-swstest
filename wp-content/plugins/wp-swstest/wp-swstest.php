<?php
/*
Plugin Name: wp-swstest
Plugin URI: http://www.softwareskills.se
Description: An online test system for Software Skills.
Version: 0.1
Author: Bruce Yinhe
Author URI: http://bruceyinhe.com
Licencse: GPLv2
*/

include_once("init.php");

register_activation_hook ( __FILE__, 'wpSwstest_install' );
register_deactivation_hook ( __FILE__, 'wpSwstest_deactivate' );
register_uninstall_hook ( __FILE__, 'wpSwstest_uninstall_hook' );

add_action( 'wp_head', 'wpSwstest_header_output' );
add_action( 'wp_footer', 'wpSwstest_footer_output' );

add_action( 'init', 'wpSwstest_init');
add_action( 'admin_menu', 'wpSwstest_create_menu' );

/*
 * views
 */

include_once("view/customer_area.php");
add_shortcode( 'wpSwstest_customer_area', 'wpSwstest_customer_area' );


/*
 * controllers
 */

//save test & question
add_action( 'save_post', 'wpSwstest_save_sws_test', 10, 2 );
add_action( 'save_post', 'wpSwstest_save_sws_question', 10, 2 );

?>