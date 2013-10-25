<?php
/**
  Template Name: Candidate Test Page
 * */
?>

<?php
require_once 'php/account-tester.php';
$tester = new Tester();

if ($_POST &&
		isset($_POST['action']) &&
        isset($_POST['test_id']) &&
        isset($_POST['ticket_id'])) {

	$tpl_body_id = 'candidateTestPage';
	get_header();
	global $postParentPageID, $data;
	$postParentPageID = $post->ID; //Page POSTID

	//POST 1
	if ( $_POST['action'] == 'start_test' ) {
		
		$ticket_details = $tester->retrieve_ticket_details(
				array(
					'tag' => 'ticket_id',
					'value' => $_POST['ticket_id']
				)
		);
		

		$display_test = get_post($_POST['test_id']); // !
		
		$time_limit = get_post_meta($display_test->ID, 'sws_test_time_limit', true); // !
		
		$key = array (
			'ticket_id' => $_POST['ticket_id'],
			'test_id' => $_POST['test_id']
		);
		
		// check if already started and if expired
		$now = time(); //date( 'Y-m-d H:i:s' );
		$time_started = $tester->retrieve_time_started( $key );
		
		if ( empty($time_started) ) {
			// if not started before
			
			$time_started = $tester->start_test( $key );
			
		}
		
		$due_time = strtotime( $time_started ) * 1000 + $time_limit * 1000;
		$time_left = $due_time - $now * 1000;

/*		echo "hejsan : timestarted", $time_started, "<br />";
		echo "hejsan : time_limit", $time_limit, "<br />";
		echo "hejsan : now", $now, "<br />";
		echo "hejsan : due_time", $due_time, "<br />";
		echo "hejsan : time_left", $time_left, "<br />";
*/
		if ( $time_left > 0 ) {
			$_SESSION['view'] = 'start_test';
		} else
			$_SESSION['view'] = 'time_up';
			
		$_SESSION['due_time'] = $due_time; // millisec

		$_SESSION['test_id'] = $_POST['test_id'];
		$_SESSION['ticket_id'] = $_POST['ticket_id'];
		
	}
	
	//POST 2
	if ( $_POST['action'] == 'done' ) {	
		
		$key = array (
			'ticket_id' => $_POST['ticket_id'],
			'test_id' => $_POST['test_id']
		);
		
		$question_ids = get_post_meta( $_POST['test_id'], 'sws_question_id', false );
		
		$form_answers_json = stripslashes($_POST['form_answers']);
				
		$_SESSION['form_answers'] = $form_answers_json;
		
		$form_answers = json_decode($form_answers_json, true);
		
		$values = array();
		
		if ( isset($form_answers) ) {
			foreach ( $form_answers as $form_answer ) {
	
				array_push( $values, array(
					'question_id' => $form_answer['question_id'],
					'answer' => $form_answer['answer']
				) );
			}
		}

		// do something
		$response = $tester->submit_all_answers( $key, $values );


		
//		$values = array();
//		
//		foreach ( $question_ids as $question_id ) {
//			
//			$name = "q" . $question_id;
//			$answer = $_POST[$name];
//			
//			array_push( $values, array(
//				'question_id' => $question_id,
//				'answer' => $answer
//			) );
//			
//			$_SESSION[$name] = $_POST[$name];
//		}
//		
//		// do something
//		$response = $tester->submit_all_answers( $key, $values );
		
		if( $response != false)
			$_SESSION['view'] = 'submitted';
		else
			$_SESSION['view'] = 'submit_failed';

		$_SESSION['test_id'] = $_POST['test_id'];
		$_SESSION['ticket_id'] = $_POST['ticket_id'];
		
	} 
	

//} 
    ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <!-- Main wrap -->
            <div id="main_wrap" >  

                <!-- Main -->
                <div id="main" >

                    <?php if ($data['wm_show_page_post_headers'] == "" || $data['wm_show_page_post_headers'] == "0") { ?>
                        <h2 class="section_title"> <?php echo "Software Skills Test";//the_title(); ?> </h2>
                    <?php } else { ?>
                        <h2></h2>
                    <?php } ?>
                    <!-- This is your section title -->

<?php /*?>                    <?php if ($data['wm_sidebar_enabled'] == "1" && get_post_meta($postParentPageID, 'page_sidebar_hide', true) == "") { ?>
                        <div id="content" class="content_two_thirds contact">
                        <?php } else { ?><?php */?>
                            <div id="content" class="content_full_width contact">
                            <?php /*?><?php } ?>
                            <!-- Content has class "content_two_thirds" to leave some place for the sidebar --><?php */?>

                            <?php
                            //	if (!empty($ticket_details))  {  //do something if logged in
                            if ( isset($_SESSION['view']) && isset($_SESSION['test_id']) && isset($_SESSION['ticket_id'])) {

								//or require?		
								include("view-test-questions.php");
                               
                            } else {  // do something if logged out
                                echo "nej";
                            }
                            ?>

                        </div>
                        <!-- Content ends here -->

                       <?php /*?> <!-- Sidebar -->
                        <?php if ($data['wm_sidebar_enabled'] == "1" && get_post_meta($postParentPageID, 'page_sidebar_hide', true) == "") { ?>
                            <div id="sidebar">
                                <?php if (!function_exists('generated_dynamic_sidebar') || !generated_dynamic_sidebar("Contact Page Sidebar")) : ?>
                                <?php endif; ?>
                            </div> 
                        <?php } ?>
                        <!-- Sidebar ends here--> 	<?php */?>




		<?php
        endwhile;
    endif;
    ?>


	<?php get_footer(); ?>

    <?php
				
	// insert modals
	if( isset($modals) )
		foreach ($modals as $modal) echo $modal;
              
} // end of POST 1


// POST 2 AJAX post
if ($_POST &&
	!empty($_POST['q']) &&
	isset($_POST['a']) &&
	!empty($_POST['t']) &&
	!empty($_POST['ticket_id']) &&
	!empty($_POST['test_id']) &&
	!empty($_POST['question_type'])) {


	$question_id = $_POST['q'];
	$answer = $_POST['a'];
	$time_saved = $_POST['t'];
	$ticket_id = $_POST['ticket_id'];
	$test_id = $_POST['test_id'];
	$question_type = $_POST['question_type'];
	
	if ($question_type == 'multi_choice' )  
		$answer = json_decode( stripslashes($answer) ); // important
	
	$value = array(
		"question_id" => $question_id,
		"answer" =>  $answer,
		"time_saved" => $time_saved
	);
	
	$key = array(
		"ticket_id" => $ticket_id,
		"test_id" => $test_id
	);
	
	$result = $tester->autosave_answer($key, $value, array('question_type' => $question_type) );
	
	echo $result;
} //end of POST 2


?>