<?php

require 'sql-connector.php';

class Membership {
	
	function verify_account($em, $pwd) {
		$db_connector = new db_connector();
		$customer_id = $db_connector->verify_account($em, md5($pwd));
		
		if($customer_id != NULL) {
			$_SESSION['customer_id'] = $customer_id;
			//header("Location: index.php");
			return "Inloggad";	

		} else {
			return "Please enter a correct username and password";	
		}
		
	} 
	
	function create_ticket($customer_id, 
						$candidate_name, 
						$candidate_email, 
						$send_to_candidate, 
						$selected_tests_id) {
		$db_connector = new db_connector();

		$ticket_token = uniqid('', true);
		
		$result = $db_connector->create_ticket($customer_id,
												$candidate_name, 
												$candidate_email, 
												$send_to_candidate, 
												$selected_tests_id, 
												$ticket_token);
		return $result;
	}
	
	function get_customer_info($customer_id) {
		$db_connetor = new db_connector();
		
		$customer_info = $db_connetor->get_customer_info($customer_id);
		
		return $customer_info;
	}
	
	function get_ticket_status($customer_id) {
		$db_connetor = new db_connector();

		$ticket_status = $db_connetor->get_ticket_status($customer_id);
		
		return $ticket_status;
	}
	
	function get_ticket_details($customer_id) {
		$db_connetor = new db_connector();

		$ticket_details = $db_connetor->get_ticket_details($customer_id);
		
		return $ticket_details;
	}
	
	function get_available_tests($customer_id) {
		$db_connetor = new db_connector();

		$available_tests = $db_connetor->get_available_tests($customer_id);
		
		return $available_tests;
	}
	
}