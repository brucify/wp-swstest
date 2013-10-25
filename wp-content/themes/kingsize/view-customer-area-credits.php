<?php include("navigation_tabs.php");
	get_navigation_tabs('credits');
	$customer_info = $membership->get_customer_info($_SESSION['customer_id']);

?>
	
	<div id="customer_area_body">
		<h3>Credits</h3>
					<p>You have <?php echo !empty($customer_info['credits_left']) ? $customer_info['credits_left'] : 0; ?> credits.</p>
 
	</div>
