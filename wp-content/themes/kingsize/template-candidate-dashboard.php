<?php
/**
  Template Name: Candidate Dashboard Page
 * */
get_header();
global $postParentPageID, $data;
?>

<?php
// check the membership with submitted form data
require_once 'php/account-tester.php';
$tester = new Tester();
		 
if ( $_GET && 
		$_GET['action'] == 'enter_token' && 
		!empty( $_GET['ticket_token'] ) ) {
	/*
	 * GET 1
	 */
    $ticket_details = $tester->retrieve_ticket_details(
          
		    array(
                'tag' => 'ticket_token',
                'value' => $_GET['ticket_token']
            )
			
    );
	
	if ($ticket_details)
		$_SESSION['view'] = 'dashboard';
	else
		$_SESSION['view'] = 'other';

} elseif ($_POST &&
		$_POST['action'] == 'dashboard' &&
		!empty( $_POST['ticket_id'] ) ) {
	
	/*
	 * POST 1
	 */
	 
	$ticket_details = $tester->retrieve_ticket_details(
          
		    array(
                'tag' => 'ticket_id',
                'value' => $_POST['ticket_id']
            )
			
    );
	
	$_SESSION['view'] = 'dashboard';

} else {
	
	/*
	 * OTHER
	 */
	$_SESSION['view'] = 'other';
	


}
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <!-- Main wrap -->
        <div id="main_wrap">  

            <!-- Main -->
            <div id="main">

                <?php if ($data['wm_show_page_post_headers'] == "" || $data['wm_show_page_post_headers'] == "0") { ?>
                    <h2 class="section_title"> <?php the_title(); ?> </h2>
                <?php } else { ?>
                    <h2></h2>
                <?php } ?>
                <!-- This is your section title -->

                <?php /*?><?php if ($data['wm_sidebar_enabled'] == "1" && get_post_meta($postParentPageID, 'page_sidebar_hide', true) == "") { ?>
                    <div id="content" class="content_two_thirds contact">
                    <?php } else { ?><?php */?>
                        <div id="content" class="content_full_width contact">
<?php /*?>                        <?php } ?>
                        <!-- Content has class "content_two_thirds" to leave some place for the sidebar --><?php */?>

                        <?php 
						
						//dashboard view
						
						include("view-test-dashboard.php"); 
						?>

                    </div>
                    <!-- Content ends here -->

                    <?php /*?><!-- Sidebar -->
                        <?php if ($data['wm_sidebar_enabled'] == "1" && get_post_meta($postParentPageID, 'page_sidebar_hide', true) == "") { ?>
                        <div id="sidebar">
                            <?php if (!function_exists('generated_dynamic_sidebar') || !generated_dynamic_sidebar("Contact Page Sidebar")) : ?>
            <?php endif; ?>
                        </div> 
        <?php } ?>
                    <!-- Sidebar ends here--> 	<?php */?>




                    <?php endwhile;
                endif; ?>


<?php get_footer(); ?>

<?php
if(isset($modals)) {
	foreach ($modals as $modal)
		echo $modal; 
}?>