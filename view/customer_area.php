<?php

function wpSwstest_customer_area () {
	
	session_start(); 
	ob_start();
		echo "hejsana";

	
	// check the membership with submitted form data
	require_once ( plugin_dir_path (__FILE__) . '/model/account-membership.php');
		echo "hejsan 1";

	$membership = new Membership();
	
	if ($_POST && $_POST['action'] == 'login' && !empty($_POST['login_email']) && !empty($_POST['login_password'])) {
		$response = $membership->verify_account($_POST['login_email'], $_POST['login_password']);
	} 
	
	if ($_POST && $_POST['action'] == 'logout') {
		session_destroy();
	//		$membership->log_User_Out();
	
	}
		
	if (isset($_SESSION['customer_id'])) {  //do something if logged in
		wp_redirect("overview");
		exit;
	 
	} else {  // do something if logged out
		include("login.php");
	}
}

	
?>