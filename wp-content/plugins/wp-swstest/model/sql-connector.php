<?php


class db_connector {
	
	function __construct() {
		global $wpdb;
	}
	
	/*
	 *	customer DB connections
	 */
	function verify_account($em, $pwd) {
		//global $wpdb; 
				
		echo "em = ". $em;
		echo "pwd = ". $pwd; 

		$query = 
			"
			SELECT id
			FROM data_customers
			WHERE email = %s AND password = %s
			";
		
		/* customer_id */				
		$result = $wpdb->get_var($wpdb->prepare($query, $em, $pwd));
		
		echo "empty? = ".!empty($result);

		return $result;
	}
	
	function create_ticket($customer_id, 
							$candidate_name, 
							$candidate_email, 
							$send_to_candidate,
							$selected_tests_id, 
							$ticket_token) {
		//global $wpdb; 
		
		//insert into data_tickets
		$result_ticket_insert = $wpdb->insert(
								'data_tickets', 
								array(
									'customer_id' => $customer_id,
									'candidate_name' => $candidate_name,
									'candiate_email'=> $candidate_email,
									'ticket_option' => $send_to_candidate,
									'ticket_token' => $ticket_token
								)
						);
						
		$ticket_id = $wpdb->insert_id;
						
		foreach ($selected_tests_id as $test_id) {
			//insert into data_test_selection
			// TODO test_id will change to multuple ***
			$result_test_selection_insert[] = $wpdb->insert(
									'data_test_selection', 
									array(
										'ticket_id' => $ticket_id,
										'test_id' => $test_id
									)
							);	
			echo "tjenare * 1";
		}
		
		$result = array (
			'result_ticket_insert' => $result_ticket_insert,
			'result_test_selection_insert' => $result_test_selection_insert,
			'ticket_token' => $ticket_token
		);
		
		// TODO error handling	***
		return $result;
	}
	
	function get_customer_info($customer_id) {
		//global $wpdb;
		
		$query = 
			"
			SELECT * 
			FROM data_customers
			WHERE id = $customer_id
			";
		
		
		
		/*
			customer_id
			email
			password
			name
			credits_left
		*/
		$customer_info = $wpdb->get_row($query, ARRAY_A);
		
		return $customer_info;
	
	}
	
	function get_ticket_status($customer_id) {
		//global $wpdb;
		
		$query = 
			"
			SELECT COUNT(ticket_id)
			FROM data_tickets
			WHERE customer_id = $customer_id
			";			
			
		/* COUNT(ticket_id) */
		$sent_ticket_count = $wpdb->get_var($query);
		
		$query = 
			"
			SELECT COUNT(ticket_id)
			FROM data_tickets
			WHERE customer_id = $customer_id AND ticket_status = 1
			";
		
		/* COUNT(ticket_id) */
		$responded_ticket_count = $wpdb->get_var($query);
	
		return array(
			"sent_ticket_count" => $sent_ticket_count,
			"responded_ticket_count" => $responded_ticket_count
		);	
	}
	
	function get_ticket_details($customer_id) {
		//global $wpdb;
		
		$query = 
			"
			SELECT * 
			FROM data_tickets
			WHERE customer_id = $customer_id
			";
			
		$results = $wpdb->get_results($query);
		
		return $results;		
	}
	
	function get_available_tests($customer_id) {
		//global $wpdb;
		
		$query = 
			"
			SELECT data_tests.*
			FROM data_tests
			INNER JOIN data_test_permission
			ON data_tests.test_id = data_test_permission.test_id
			WHERE data_test_permission.customer_id = $customer_id
			";
			
		$available_tests = $wpdb->get_results($query, ARRAY_A);
		
		return $available_tests;
	}
	
	/*
	 *	tester DB connections
	 */
	 
	function retrieve_ticket_details ($ticket_token) {
	//	global $wpdb;
	
		$query =
			'
			SELECT *
			FROM data_tickets
			WHERE ticket_token = "' . $ticket_token . '"
			';
			
					
		$ticket_details = $wpdb->get_row($query, ARRAY_A);
		
		return $ticket_details;
	}
	
	function retrieve_assigned_tests ($ticket_id) {
		//global $wpdb;
		
		$query =
			"
			SELECT test_id, status
			FROM data_test_selection
			WHERE ticket_id = $ticket_id
			";
		
		$assigned_tests = $wpdb->get_results($query, ARRAY_A);
		
		return $assigned_tests;
	}
	
	function retrieve_assigned_test_details($test_id) {
		//global $wpdb;
		
		$query =
			'
			SELECT *
			FROM data_tests
			WHERE test_id = "'. $test_id . '"
			';
				
		$assigned_test_details = $wpdb->get_row($query, ARRAY_A);
		
		return $assigned_test_details;
	
	}
	
	function autosave_answer($key, $value) {
		/*
		 $submitted_answer = [
		 	{ "question_id" : "q1submit", "answer" : "A", "time_saved" : 123123123 },
		 	{ "question_id" : "q2submit", "answer" : "A", "time_saved" : 123123123 },
		 ];
		 */
	//	global $wpdb;
		echo "hejsan";
		
		$query =  $wpdb->prepare(
			'
			SELECT submitted_answer
			FROM data_test_selection
			WHERE ticket_id = %d AND test_id = %s
			',
			$key["ticket_id"], $key["test_id"]
		);
		
		
		$submitted_answer = json_decode( $wpdb->get_var( $query ), true );
		
		$flag = 0;
		
		foreach ($submitted_answer as $answer) {
			
			if ($answer['question_id'] == $value['question_id']) {
				
				$answer['answer'] = $value['answer'];
				$answer['time_saved'] = $value['time_saved'];
				
				$flag = 1;
			}
		}
		
		if ($flag == 0) 
			$submitted_answer[] .= $value;

		$data = array(
			"submitted_answer" => $submitted_answer
		);
		
		$where = array (
			"ticket_id" => $key["ticket_id"],
			'test_id' => $key["test_id"]
		);
		
		$result = $wpdb->update( "data_test_selection", $data, $where, $format = null, $where_format = null );
			
		return $result;
	
	}
	
	
}
