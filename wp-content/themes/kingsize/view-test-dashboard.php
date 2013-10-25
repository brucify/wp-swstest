<?php

function convert_time ($t_sec) {
	if ($t_sec > 60)
		return round($t_sec / 60) . " m";
	else
		return $t_sec . " s";
}

/*
 * VIEW 1 - dashboard
 */
if ( $_SESSION['view'] == 'dashboard' && isset($ticket_details) ) {  

	$assigned_tests = $tester->retrieve_assigned_tests($ticket_details['ticket_id']);
	?>
    
	<!-- Welcome Candidate -->
	<div id="welcome_candidate">
		<h3>Welcome <?php echo $ticket_details['candidate_name']; ?></h3>
		<p>You have <?php echo sizeof($assigned_tests) ?> assigned tests.</p>
	</div>
	<!-- Welcome Candidate ends here -->

	<!-- Tests -->
	<div id="tests">
		<h3>Tests</h3>
		<table class="table table-condensed">
			<tr>
				<th>Test Name</th>									
				<th>Test ID</th> 
				<th>Time Limit</th>
				<th>Status</th>
			</tr>
	
	<?php
    foreach ($assigned_tests as $assigned_test) {
        
        $assigned_test_details = $tester->retrieve_assigned_test_details($assigned_test['test_id']);

        /* if ($assigned_test['status'] == 1) {
          $test_status = ' "success" ';
          } */
    
        //echo '<tr class=', $test_status, '>
        ?>
            <tr >
                <td><a href="#<?php echo $assigned_test_details['test_id']; ?>" role="button" class="btn btn-block btn-primary" data-toggle="modal"><?php echo $assigned_test_details['test_name']; ?></a></td>
                <td><?php echo $assigned_test_details['test_id']; ?></td>
                <td><?php echo convert_time ( $assigned_test_details['time_limit'] ); ?></td>
                <td><span><i class="<?php if($assigned_test['status'] == 1) echo "icon-ok icon-white"; else echo "icon-remove icon-white"; ?>"></i></span>
                <?php 
					if ( isset($assigned_test['time_submitted'] ) )
						$status_text = "Submitted on " . $assigned_test['time_submitted'];
					else if ( isset( $assigned_test['time_started'] ) ) 
						$status_text = 'Started on ' . $assigned_test['time_started'];
					else
						$status_text = 'Not started';
					echo $status_text;
				?>
                </td>
            </tr>
        <?php
    
		if ($assigned_test['status'] == 1) $disabled = 'disabled="disabled"';
		else $disabled = "";
			
	
        $modals[] .= 
			'
				<!-- Modal -->
				<div id="' . $assigned_test_details['test_id'] . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header" style="color: black;">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">' . $assigned_test_details['test_name'] . '</h3>
					</div>
					<div class="modal-body" style="color: black;">
						<p>' . $assigned_test_details['test_description'] . '</p>
					</div>
					<div class="modal-footer" class="form-inline">
					
						<form method="post" action="' . site_url() . '/swstest/start-test" style="margin: 0 0 0">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<button type="submit" class="btn btn-primary" ' . $disabled . '>Start test</button>
							
							<input type="hidden" name="action" value="start_test" />
							<input type="hidden" name="test_id" value="' . $assigned_test_details['test_id'] . '" />
							<input type="hidden" name="ticket_id" value="' . $ticket_details['ticket_id'] . '" />
	
						</form>
					</div>
				</div>
			'; // some custom content here
	} ?>
                            <?php /*?><tr>
                                <td><a href="#t1" role="button" class="btn btn-block btn-info" data-toggle="modal">Example Test</a></td>
                                <td>Example</td>
                                <td>N/A</td>
                                <td>N/A</td>
                            </tr><?php */?>
                        </table>
    
                    </div>
                    <!-- Tests end here -->
    
                    <!-- Ticket Details -->
                    <div id="ticket_details">
                        <h3>Ticket Details</h3>
                        <dl class="dl-horizontal">
                            <dt>From employer</dt>
                            <dd>TODO</dd>
    
                            <dt>Sent on</dt>
                            <dd>TODO</dd>
    
                            <dt>Message from employer</dt>
                            <dd>TODO</dd>
                        </dl>
                    </div>
                    <!-- Ticket Details ends here -->

<?php 
} else {  // do something if logged out 
    /*
	 * VIEW 3 - enter token
	 */
?>
    <form class="form-inline" method="get">
        <input type="hidden" name="action" value="enter_token" />
        <input type="text" class="input-large" name="ticket_token" placeholder="Ticket token">
        <button type="submit" class="btn">Submit</button>
        <span class="help-block">Enter your ticket token to start the test.</span>
    </form>

    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Warning!</strong> Enter a valid ticket token.
    </div>

<?php } ?>