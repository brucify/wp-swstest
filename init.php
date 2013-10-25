<?php 

include_once("sws_test.php");
include_once("sws_ticket.php");
include_once("sws_user.php");
include_once("sws_question.php");
include_once("sws_question_alternative.php");

function wpSwstest_install () {
	
	//$wpSwstest_options_arr = array();
	
	//update_option( 'wpSwstest_options', $wpSwstest_options_arr );
}

function wpSwstest_deactivate () {
	// do something
}

function wpSwstest_uninstall_hook () {
	// do something	
}

function wpSwstest_init () {
	
	/*
	 * register custom post types
	 */
	wpSwstest_register_test();
	wpSwstest_register_test_type_taxonomy();
	
	//wpSwstest_register_ticket();
	wpSwstest_register_user();
	wpSwstest_register_question();
	wpSwstest_register_question_alternative();

}

function wpSwstest_create_menu () {
	
	add_menu_page( 
		'Software Skills Test Setting Page', 
		'wp-swstest', 
		'manage_options', 
		'wpSwstest_settings', 
		'wpSwstest_settings_page' 
	);
}

function wpSwstest_settings_page () {
	?>
	<div class="wrap">
	    <h2>Software Skills Test</h2>			
	  
	</div>
	<?php
}

function wpSwstest_header_output () { 
		
	echo '
    	<link rel="stylesheet" href="' , plugins_url() , '/wp-swstest/includes/bootstrap/css/bootstrap.min.css">
	';

}

function wpSwstest_footer_output () { 
		
	echo '
		<script type="text/javascript" src="' , plugins_url() , '/wp-swstest/includes/bootstrap/js/bootstrap.min.js"></script>
	';

}
