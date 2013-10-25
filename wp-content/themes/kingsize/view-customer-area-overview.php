<?php include("navigation_tabs.php");
	get_navigation_tabs('overview');
	
	$customer_info = $membership->get_customer_info($_SESSION['customer_id']);
	$ticket_status = $membership->get_ticket_status($_SESSION['customer_id']);
	
?>
	
	<div id="customer_area_body">
		<div id="profile">
			<h3>Profile</h3>
			<p>Email: <?php echo $customer_info['email']; ?>
			Name: <?php echo $customer_info['name']; ?> </p>
		</div>
		<div id="tickets">
			<h3>Tickets</h3>
			<p>You have sent out <?php echo !empty($ticket_status["sent_ticket_count"]) ? $ticket_status["sent_ticket_count"] : 0; ?> tickets. You have received <?php echo !empty($ticket_status["responded_ticket_count"]) ? $ticket_status["responded_ticket_count"] : 0; ?> responses from candidates.</p>
		</div>
		<div id="credits">
			<h3>Credits</h3>
			<p>You have <?php echo !empty($customer_info['credits_left']) ? $customer_info['credits_left'] : 0; ?> credits.</p>
            <p> </p>
		</div>
		<div id="formlogout">
		<?php //the_content(); ?>
		
		<form method="post" action="" id="logout_form">
			<input type='hidden' name='action' value='logout'>
			<input type="submit" value="Log out" class="btn">
		</form>
        </div>
	</div>
