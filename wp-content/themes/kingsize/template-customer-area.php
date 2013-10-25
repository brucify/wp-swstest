<?php
/**
 Template Name: Customer Area Page
 **/
// start the session 
session_start(); 
ob_start();

$tpl_body_id = 'loginpage';
get_header(); 
global $postParentPageID,$data;
$postParentPageID = $post->ID; //Page POSTID
?>

<?php 
// check the membership with submitted form data
require_once 'php/account-membership.php';
$membership = new Membership();

if ($_POST && $_POST['action'] == 'login' && !empty($_POST['login_email']) && !empty($_POST['login_password'])) {
	$response = $membership->verify_account($_POST['login_email'], $_POST['login_password']);
} 

if ($_POST && $_POST['action'] == 'logout') {
	session_destroy();
//		$membership->log_User_Out();

}
?>


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<!-- Main wrap -->
		<div id="main_wrap">  
			  
    		<!-- Main -->
   			<div id="main">
   			 	
      			<?php if ( $data['wm_show_page_post_headers'] == "" || $data['wm_show_page_post_headers'] == "0" ) {?>
                <h2 class="section_title"> <?php the_title(); ?> </h2>
				<?php } else { ?>
                <h2></h2>
				<?php } ?>
                <!-- This is your section title -->
      			
                <?php if ( $data['wm_sidebar_enabled'] == "1"  && get_post_meta($postParentPageID, 'page_sidebar_hide', true ) == "") {?>
                <div id="content" class="content_two_thirds contact">
                <?php } else { ?>
                <div id="content" class="content_full_width contact">
                <?php } ?>
				<!-- Content has class "content_two_thirds" to leave some place for the sidebar -->

					<?php 
                    if (isset($_SESSION['customer_id'])) {  //do something if logged in
						wp_redirect("overview");
						exit;
                     
                    } else {  // do something if logged out
                    	include("login.php");
					}?>
     			</div>
                <!-- Content ends here -->
			 
  			   <!-- Sidebar -->
			   <?php if ( $data['wm_sidebar_enabled'] == "1" && get_post_meta($postParentPageID, 'page_sidebar_hide', true ) == "") {?>
			    <div id="sidebar">
					<?php if ( !function_exists('generated_dynamic_sidebar') || !generated_dynamic_sidebar("Contact Page Sidebar") ) : ?>
					<?php endif; ?>
			    </div> 
				<?php } ?>
			    <!-- Sidebar ends here--> 	
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
