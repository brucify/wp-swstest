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
	
	function create_ticket(
		$customer_id, 
		$candidate_name, 
		$candidate_email, 
		$send_to_candidate, 
		$selected_test_ids
	) {
							
		$db_connector = new db_connector();

		// Generate ticket token here
		$ticket_token = sha1( uniqid( $customer_id, true ) );
		
		$result = $db_connector->insert_ticket( 
			array(
				'customer_id' => $customer_id,
				'candidate_name' => $candidate_name,
				'candidate_email' => $candidate_email,
				'ticket_option' => $send_to_candidate,
				'ticket_token' => $ticket_token
			) 
		);
		
		if ( $result != false ) {
			
			$ticket_id = $result;
			
			$result = $db_connector->insert_test_selections(
				array(
					'selected_test_ids' => $selected_test_ids,
					'ticket_id' => $ticket_id
				)
			);
			
			if ( $result != false) 
				return $ticket_token;
			else 
				return -1;//"Failed to insert test selections"
			
		} else {
			return -1; //"Failed to insert ticket."
		}
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
		//$db_connetor = new db_connector();
//
//		$available_tests = $db_connetor->get_available_tests($customer_id);
		
		$available_tests = get_posts(
			array('post_type' => 'sws_test')
		);
		
		return $available_tests;
	}
	
	function retrieve_assigned_tests($ticket_id) {
		$db_connector = new db_connector();

		$assigned_tests = $db_connector->retrieve_assigned_tests($ticket_id);
		
		return $assigned_tests;
	}
	
	function retrieve_assigned_test_details( $test_id ) {
		
		$assigned_test = get_post( $test_id );
		
		$time_limit = get_post_meta($assigned_test->ID, 'sws_test_time_limit', true);
		$test_status = get_post_meta($assigned_test->ID, 'sws_test_status', true);
		
		$assigned_test_details = array (
			'test_id' => $assigned_test->ID,
			'test_name' => $assigned_test->post_title,
			'time_limit' => $time_limit,
			'status' => $test_status,
			'test_description' => $assigned_test->post_content
		
		);
		
        return $assigned_test_details;
	
    }
	
	
}