<?php 
// START OF FUNCTIONS
function add_question( $index, $question, $args_question ) {

	$question_type = get_post_meta( $question->ID, "sws_question_type", true );
	
	switch( $question_type ) {
		case "single_choice":
			add_single($index, $question, $args_question);
			break;
			
		case "multi_choice":
			add_multi($index, $question, $args_question);
			break;
		
		case "text":
			add_text($index, $question, $args_question);
			break;
			
		case "order":
			add_sorting($index, $question, $args_question);
			break;
	} 
	
}

function add_single( $index, $question, $args_question ) {
	$question_alt_ids = get_post_meta( $question->ID, "sws_question_alt_id", false);
?>
	
	<fieldset id="q<?php echo $index; ?>">
		<legend><?php echo $index . '. ' . $question->post_title; ?></legend>
		<div class="fieldset-content">
			<p><?php echo $question->post_content; ?></p>
			
			
			<!-- radio buttons here-->
			<p>
                <div class="btn-group btn-group-vertical btn-block" data-toggle="buttons-radio">
                  
					<?php 
                    	$alpha_index = "A";
							
                   		for ( $i = 0; $i < sizeof($question_alt_ids); $i++ ) {

							$question_alt = get_post( $question_alt_ids[$i] );
							
							?>
							<button type="button" class="btn btn-primary btn-block<?php if ( $question_alt->ID == $args_question['autosaved_answer'] ) echo " active"; ?>" 
                            	id="q<?php echo $question->ID; ?>" 
                                onclick='singleClicked(
                                        	"<?php echo $_SESSION['ticket_id']; ?>", 
                                   			"<?php echo $_SESSION['test_id']; ?>", 
                                            "<?php echo $question->ID; ?>",
                                            "<?php echo $question_alt->ID; ?>",
                                            "form_answers",
                                            "<?php echo $args_question['navaid']; ?>"
                                        )'><?php echo $alpha_index++, ". " ,$question_alt->post_content; ?></button>
		
							<?php
                        
						}
                    
                    ?>
                  
                  <br />
                  <input type="hidden" value="" name="q<?php echo $question->ID; ?>" id="q<?php echo $index; ?>align">
                </div>
        
<?php /*?>                <div id="q<?php echo $index; ?>submit"<?php echo $args_question['q_submit_class']; ?>><?php echo $args_question['q_submit_inner']; ?></div>
<?php */?>			</p>
            
			<br /><br /><br />
			<br /><br /><br />
			<br /><br /><br />
			<br />
		</div>
	</fieldset>
	<?php
}

function add_multi( $index, $question, $args_question ) {
	
	$question_alt_ids = get_post_meta( $question->ID, "sws_question_alt_id", false);
?>
	
	<fieldset id="q<?php echo $index; ?>">
		<legend><?php echo $index . '. ' . $question->post_title; ?></legend>
		<div class="fieldset-content">
			<p><?php echo $question->post_content; ?></p>
			
			
			<!-- radio buttons here-->
			<p>
                <div class="btn-group btn-group-vertical btn-block" id="g<?php echo $question->ID; ?>group" data-toggle="buttons-checkbox">
                  
					<?php 
                    	$alpha_index = "A";
							
                   		for ( $i = 0; $i < sizeof($question_alt_ids); $i++ ) {

							$question_alt = get_post( $question_alt_ids[$i] );
							
							?>
                                        
                            <button type="button" class="btn btn-primary btn-block<?php 
							
								for ( $j = 0; $j < sizeof( $args_question['autosaved_answer']); $j++ ) { 
									if ( $question_alt->ID == intval( $args_question['autosaved_answer'][$j] ) ) {
										echo " active"; 
									}	
								}
							 
							 ?>" 
                            	id="<?php echo $question_alt->ID; ?>" 
                                onclick='multiClicked(
                                	"<?php echo $_SESSION['ticket_id']; ?>",
                                	"<?php echo $_SESSION['test_id']; ?>", 
                                    "<?php echo $question->ID; ?>",
                                    this.id,
                                    "g<?php echo $question->ID; ?>group",
                                   	"form_answers",
                                    "<?php echo $args_question['navaid']; ?>")'>
									<?php echo $alpha_index++, ". " ,$question_alt->post_content; ?>
							</button>
                                        
   
		
							<?php
						}
                    ?>
                  
                  <br />
                  <input type="hidden" value="" name="q<?php echo $question->ID; ?>" id="q<?php echo $index; ?>align">
                </div>
        
<?php /*?>                <div id="q<?php echo $index; ?>submit"<?php echo $args_question['q_submit_class']; ?>><?php echo $args_question['q_submit_inner']; ?></div>
<?php */?>			</p>
			
			<br /><br /><br />
			<br /><br /><br />
			<br /><br /><br />
			<br />
		</div>
	</fieldset>
	<?php
}

function add_text( $index, $question, $args_question ) {
?>
	
	<fieldset id="q<?php echo $index; ?>">
		<legend><?php echo $index . '. ' . $question->post_title; ?></legend>
		<div class="fieldset-content">
			<p><?php echo $question->post_content; ?></p>
			
            
			
			<!-- textarea here-->
            <div style="width: 300px;">
                <p>Fill in your answer here:</p>
                <p>
				<textarea rows="5" wrap="physical" style="width: 300px; height: 150px;" id="ta<?php echo $question->ID; ?>"><?php echo $args_question['autosaved_answer']; ?></textarea>
				<br />
    
				<button type="button" class="btn btn-primary btn-block" id="textSave<?php echo $question->ID; ?>" data-loading-text="Saving..." onclick='textSaveClicked(
                                        	"<?php echo $_SESSION['ticket_id']; ?>", 
                                   			"<?php echo $_SESSION['test_id']; ?>", 
                                            "<?php echo $question->ID; ?>",
                                            "ta<?php echo $question->ID; ?>",
                                            "form_answers",
                                            "<?php echo $args_question['navaid']; ?>",
                                            this.id
                                        )'>Save</button>
				<input type="hidden" value="" name="q<?php echo $question->ID; ?>" id="q<?php echo $index; ?>align">
			</div>
            
<?php /*?>                <div id="q<?php echo $index; ?>submit"<?php echo $args_question['q_submit_class']; ?>><?php echo $args_question['q_submit_inner']; ?></div>
<?php */?>			</p>
            
			<br /><br /><br />
			<br /><br /><br />
			<br /><br /><br />
			<br />
		</div>
	</fieldset>
	<?php
}

function add_sorting($index, $question, $args_question) {
	$question_alt_ids = get_post_meta( $question->ID, "sws_question_alt_id", false);
?>
	
	<fieldset id="q<?php echo $index; ?>">
		<legend><?php echo $index . '. ' . $question->post_title; ?></legend>
		<div class="fieldset-content">
			<p><?php echo $question->post_content; ?></p>
			
						<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>  

			<!-- radio buttons here-->
			<p>
                <ul id="sortable" class="btn-group btn-group-vertical btn-block" style="margin: 0 0 0 0;">
                  	
					<?php 
                    	$alpha_index = "A";
							
                   		for ( $i = 0; $i < sizeof($question_alt_ids); $i++ ) {

							$question_alt = get_post( $question_alt_ids[$i] );
							
							?>
							<li class="btn btn-primary btn-block" id="<?php echo $question_alt->ID; ?>"><?php echo $alpha_index++, ". " ,$question_alt->post_content; ?></li>
		
							<?php
                        
						}
                    
                    ?>

                  <br />
                  <input type="hidden" value="" name="q<?php echo $question->ID; ?>" id="q<?php echo $index; ?>align">
                </ul>
        
<?php /*?>                <div id="q<?php echo $index; ?>submit"<?php echo $args_question['q_submit_class']; ?>><?php echo $args_question['q_submit_inner']; ?></div>
<?php */?>			</p>
            
			<br /><br /><br />
			<br /><br /><br />
			<br /><br /><br />
			<br />
		</div>
	</fieldset>
	<?php
} //END OF FUNCTIONS
?> 

 <?php


if ( $_SESSION['view'] == 'start_test' ) {

/*
 * VIEW 1 - show questions 
 */
	
	$question_ids = get_post_meta($display_test->ID, 'sws_question_id', false);	// !

	$submitted_answers = $tester->retrieve_autosaved_answers( $key );
	
	?>
	
	<div>
		<h1><?php echo $display_test->post_title; ?></h1>
		<hr/>
		<style type="text/css">
			.affix {
				top: 50px;
			}
		</style>
		<div class="row-fluid">
			<div class="span3" id="nav_q">
				<ul class="nav nav-list" data-spy="affix" data-offset-top="180" style="margin:0px; width:127px;">
					<li><i class="icon-time icon-white"></i> <span id="time_left"><?php /*echo $time_left / 1000;*/ ?></span></li>
					<?php /* 
					 
					<li><a href="#one"><i class="icon-chevron-right"></i> Question One</a></li>
					<li><a href="#two"><i class="icon-chevron-right"></i> Question Two</a></li>
					<li><a href="#three"><i class="icon-chevron-right"></i> Question Three</a></li>
					<li><a href="#four"><i class="icon-chevron-right"></i> Question Four</a></li>
					<li><a href="#five"><i class="icon-chevron-right"></i> Question Five</a></li>
					<li><a href="#six"><i class="icon-chevron-right"></i> Question Six</a></li>
					<li><a href="#seven"><i class="icon-chevron-right"></i> Question Seven</a></li>
					 
					 */ ?>
	
					<?php
					
					// start of for
					for ( $i = 1; $i <= sizeof($question_ids); $i++ ) { 
						
						$fontcolor = "";
						
						if( isset($submitted_answers) ) {
							foreach ( $submitted_answers as $submitted_answer ) {
								
								if ( $submitted_answer['question_id'] == $question_ids [ $i - 1 ]  && $submitted_answer['answer'] != "" ) {
							
									$fontcolor = 'style="background-color: green;"';
													
								}
							
							}
						}
					
					?>
						
                        
						<li><a href="#q<?php echo $i; ?>" id="<?php echo "nava".$i; ?>" <?php echo $fontcolor;?>;"><i class="icon-chevron-right"></i> <span>Question <?php echo $i; ?></span></a></li>
				
					<?php 
					
					} // end of for	
					
					?>
				</ul>
                <span id="due_time" style="visibility:hidden;"><?php echo $_SESSION['due_time']; ?></span>
			</div>
			<div class="span9" data-spy="scroll" data-target="#nav_q">
				<form id="submit_all" method="post" action="#">
					<?php 
						//start of for
						for ( $i = 1; $i <= sizeof($question_ids); $i++ ) {

							//$q_submit_inner = "";
//							$q_submit_class = "";	
							$autosaved_answer = "";
							
							if( isset($submitted_answers) ) {
								foreach ( $submitted_answers as $submitted_answer ) {
									
									if ( $submitted_answer['question_id'] == $question_ids [ $i - 1 ]  && $submitted_answer['answer'] != "" ) {
								
										$autosaved_answer = $submitted_answer['answer'];
										//$autosaved_time = $submitted_answer['time_saved'];
//											
//										$q_submit_inner = "Auto-saved on " . date('Y-m-d H:i:s', $autosaved_time);
//										$q_submit_class = ' class="alert alert-success"';
																
									}
								
								}
							}
							
							$args_question = array(
								'autosaved_answer' => $autosaved_answer,
								'navaid' => "nava".$i/*'q_submit_inner' => $q_submit_inner,
								'q_submit_class' => $q_submit_class	*/
							);		
								
							$question = get_post($question_ids[$i - 1]); // !
						
							add_question($i, $question, $args_question);				
						
						} // end of for
					?>      
					<input type="hidden" name="ticket_id" value="<?php echo $_SESSION['ticket_id']; ?>">
					<input type="hidden" name="test_id" value="<?php echo $_SESSION['test_id']; ?>">
					<input type="hidden" name="action" value="done">
					<input type="hidden" value='<?php echo json_encode($submitted_answers); ?>' name="form_answers" id="form_answers">

					<input type="submit" value="Submit" class="btn btn-block btn-success">
				</form>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="<?php bloginfo( 'template_url' );?>/js/view-test-questions.js"></script>
   
<?php 

} 
?>

<?php 
if ( $_SESSION['view'] == 'time_up') {
	/*
	 * VIEW 2 - test time up
	 */
?>

	<div class="jumbotron">
		<h1>Time is up!</h1>
		<p>This test has expired on <?php echo date( 'Y-m-d H:i:s', $_SESSION['due_time'] / 1000 );?>.</p>
		<p>
			<form method="post" action="<?php echo site_url(); ?>/swstest">
				<input type="hidden" name="action" value="dashboard">
				<input type="hidden" name="ticket_id" value="<?php echo $_SESSION['ticket_id']; ?>">
				<input type="submit" value="Go to Dashboard"class="btn btn-primary btn-large">
			</form>
		</p>
	</div>

<?php 	
}
?>

<?php 
if ( $_SESSION['view'] == 'submitted' ) {     
	/*
	 * VIEW 3 - test done
	 */
?>

    <div class="jumbotron">
    	<h1>THANK YOU!</h1>
		<p>Test submitted.</p>
		<p>
        	<form method="post" action="<?php echo site_url(); ?>/swstest">
            	<input type="hidden" name="action" value="dashboard">
            	<input type="hidden" name="ticket_id" value="<?php echo $_SESSION['ticket_id']; ?>">
				<input type="submit" value="Go to Dashboard"class="btn btn-primary btn-large">
			</form>
		</p>
    </div>

<?php 
}
?>

<?php 
if ( $_SESSION['view'] == 'submit_failed' ) {     
	/*
	 * VIEW 4 - test done
	 */
?>

    <div class="jumbotron">
    	<h1>Oops!</h1>
		<p>An error has occured. Please try submit again later.</p>
		<p>
        	<form method="post" action="#">
            	<input type="hidden" name="action" value="start_test">
            	<input type="hidden" name="ticket_id" value="<?php echo $_SESSION['ticket_id']; ?>">
            	<input type="hidden" name="test_id" value="<?php echo $_SESSION['test_id']; ?>">
				<input type="submit" value="Back to Test"class="btn btn-primary">
			</form>
		</p>
    </div>

<?php 
}
?>

