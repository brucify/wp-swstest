<?php

require 'sql-connector.php';

class Tester {
	
	function __construct() {
		
		$db_connector = new db_connector();

	}
	
	/*
	 * retrieve
	 */
	function retrieve_ticket_details($ticket_token) {
	//	$db_connector = new db_connector();
		$ticket_details = $db_connector->retrieve_ticket_details($ticket_token);				
		
		return $ticket_details;
		
	} 
	
	function retrieve_assigned_tests($ticket_id) {
	//	$db_connector = new db_connector();
		$assigned_tests = $db_connector->retrieve_assigned_tests($ticket_id);
		
		return $assigned_tests;
	}
	
	function retrieve_assigned_test_details($test_id) {
	//	$db_connector = new db_connector();
		$assigned_test_details = $db_connector->retrieve_assigned_test_details($test_id);
		
		return $assigned_test_details;
	}
	
	/*
	 * save answer
	 */
	function autosave_answer($key, $value) {
	//	$db_connector = new db_connector();


		$response = $db_connector->autosave_answer($key, $value);
		
		return $response;
	}
	
}