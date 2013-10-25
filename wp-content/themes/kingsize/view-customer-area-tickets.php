<?php 
include("navigation_tabs.php");
	get_navigation_tabs('tickets');
	
	$ticket_details = $membership->get_ticket_details( $_SESSION['customer_id'] );
	
?>
	
	<div id="customer_area_body">
		<h3>Tickets</h3>
			<p>You have sent out <?php // get ticket ?> test tickets.</p>
			<a class="button blue" href="../create-Ticket"> Create a Ticket </a>
				<table class="table table-hover">
					<thead>
					<tr>
						<th>Ticket ID</th>
						<th>Candidate Name</th>
						<th>Candidate Email</th>
						<th>Details</th>
						<th>Ticket Link</th>
					</tr>
					</thead>
					<tbody>
					<?php 
					foreach($ticket_details as $ticket_detail) {
						
						$assigned_tests = $membership->retrieve_assigned_tests( $ticket_detail->ticket_id );

						?>
						<tr class="odd">
							<td><?php echo $ticket_detail->ticket_id; ?></td>
							<td><?php echo $ticket_detail->candidate_name; ?></td>
							<td><?php echo $ticket_detail->candidate_email; ?></td>
							<td><a href="<?php echo "#t", $ticket_detail->ticket_id; ?>" role="button" class="btn btn-mini" data-toggle="modal"><i class="icon-resize-full"></i>
</a>
</td>
							<td><?php 
							$test_url = get_site_url() . "/swstest/?action=enter_token&ticket_token=" . $ticket_detail->ticket_token;;
							echo '<a href=',$test_url,'>',substr($ticket_detail->ticket_token, -5, 5),'</a>' ?></td>
						</tr>
						<?php
						
						$modal = 
							'
							<!-- Modal -->
							<div id="t' . $ticket_detail->ticket_id . '" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-header" style="color: black;">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">' . $ticket_detail->candidate_name . '</h3>
								</div>
								<div class="modal-body" style="color: black;">
									<h4>Ticket ID</h4>
									<p>' . $ticket_detail->ticket_id . '</p>
									<h4>Candidate Name</h4>
									<p>' . $ticket_detail->candidate_name . '</p>
									<h4>Candidate Email</h4>
									<p>' . $ticket_detail->candidate_email . '</p>
							';
												
						foreach ( $assigned_tests as $assigned_test ) {
								
							$assigned_test_details = $membership->retrieve_assigned_test_details($assigned_test['test_id']);

							if ( isset($assigned_test['time_submitted'] ) )
								$status = "Submitted on " . $assigned_test['time_submitted'];
							else if ( isset( $assigned_test['time_started'] ) ) 
								$status = 'Started on ' . $assigned_test['time_started'];
							else
								$status = 'Not started';

							$modal .= 
								'
									<hr>
									<h4>Test #' . $assigned_test_details['test_id'] . '. ' . $assigned_test_details['test_name'] . '</h4>
									<h5>Status:</h5>
									<p>' . $status . '</p>	
								';
						
						}
								
								
						 $modal .= 
						 	'
								</div>
									<div class="modal-footer" class="form-inline">
										<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									</div>
								</div>
							';
							
						$modals[] = $modal;
					}
					
					?>
					</tbody>
					</table>
	</div>
