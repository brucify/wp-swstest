<?php
/**
 Template Name: Customer Area Create Ticket Page
 **/
// start the session 
session_start(); 

$tpl_body_id = 'loginpage';
get_header(); 
global $postParentPageID,$data;
$postParentPageID = $post->ID; //Page POSTID
?>

<?php 
// check the membership with submitted form data
require_once 'php/account-membership.php';
$membership = new Membership();

if ($_POST 
	&& !empty($_POST['login_email']) 
	&& !empty($_POST['login_password'])) {
	// if login info submitted
	$response = $membership->verify_account($_POST['login_email'], $_POST['login_password']);
} 

if ($_POST 
	&& !empty($_POST['candidate_name']) 
	&& !empty($_POST['candidate_email'])
	&& !empty($_POST['selected_tests_id'])) {
		
	// if create ticket info submitted
	
	$candidate_name = $_POST['candidate_name'];
	$candidate_email = $_POST['candidate_email'];
	$test_id = $_POST['selected_tests_id'];
	
	$result_create_ticket = $membership->create_ticket($_SESSION['customer_id'], 
											$_POST['candidate_name'], 
											$_POST['candidate_email'], 
											$_POST['send_to_candidate'], 
											$_POST['selected_tests_id']);

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

				<?php include("view-customer-area-create-ticket.php"); ?>
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
