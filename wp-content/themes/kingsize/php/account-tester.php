<?php

require 'sql-connector.php';

class Tester {

    function retrieve_ticket_details( $arg ) {

        $db_connector = new db_connector();

        switch ($arg['tag']) {

            case 'ticket_token':

                $ticket_details = $db_connector->retrieve_ticket_details_by_token($arg['value']);
                break;

            case 'ticket_id':

                $ticket_details = $db_connector->retrieve_ticket_details_by_id($arg['value']);
                break;
        }

        return $ticket_details;
    }

    function retrieve_assigned_tests( $ticket_id ) {
		
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
        /*$db_connector = new db_connector();
        $assigned_test_details = $db_connector->retrieve_assigned_test_details($test_id); */

        return $assigned_test_details;
	
    }
	
	function retrieve_time_started ( $key ) {
		
		$db_connector = new db_connector();

		$time_started = $db_connector->retrieve_time_started( $key['ticket_id'], $key['test_id'] );

		return $time_started;
	
	}
	
	function retrieve_autosaved_answers ( $key ) {
		
		$db_connector = new db_connector();

		$submitted_answers = $db_connector->retrieve_autosaved_answers( $key['ticket_id'], $key['test_id'] );

		return $submitted_answers;
	
	}

    /*
     * save answer
     */
	 
	function start_test( $key ) {
		// return date( 'Y-m-d H:i:s' )
		$db_connector = new db_connector();

		$now = date( 'Y-m-d H:i:s' );

		$result = $db_connector->update_time_started( $key, $now );

		if ( $result != false )
			return $now;
		else 
			return false;
	}

    function autosave_answer($key, $value, $option) {
       
	    $db_connector = new db_connector();

        // retrieve old values
        $submitted_answers = $db_connector->retrieve_autosaved_answers($key['ticket_id'], $key['test_id']);

		//if ( $option['question_type'] == 'single_choice' ) {
			
			$flag = 0;
	
			//find and replace existing values
			for ($i = 0; $i < sizeof( $submitted_answers ); $i++) {
				if ($submitted_answers[$i]['question_id'] == $value['question_id']) {
	
					$submitted_answers[$i]['answer'] = $value['answer'];
					$submitted_answers[$i]['time_saved'] = $value['time_saved'];
	
					$flag = 1;
				}
			}
	
			// if NULL or not exist, add new value 
			if ($flag == 0) {
	
				if (!$submitted_answers)
					$submitted_answers = array(); //echo "***NEW array<br />";	
	
				array_push($submitted_answers, $value); //echo "***flag == 0 value= " , var_dump($value),"<br />" ;
			}
		
		//}
		
		//else if ( $option['question_type'] == 'multi_choice' ) {
			
			/*// search and replace question
			$flag_q = 0;
			for ($i = 0; $i < sizeof( $submitted_answers ); $i++) {
				
				if ( $submitted_answers[$i]['question_id'] == $value['question_id'] ) {
					
					// search and replace answer
					$flag_a = 0;
					for ( $j = 0; $j < sizeof( $submitted_answers[$i]['answer'] ); $j++) {
					
						if ( $submitted_answers[$i]['answer'][$j] == $value['answer'] ) {
												
							array_splice( $submitted_answers[$i]['answer'], $j ); // ?
							$flag_a = 1;
						}
						
					}
					
					// if not found, insert new
					if ($flag_a == 0) 
						array_push( $submitted_answers[$i]['answer'], $value['answer'] );
					
					$submitted_answers[$i]['time_saved'] = $value['time_saved'];
					
					$flag_q = 1; 			
				}
				
			}

			// if not found, insert new
			if ( $flag_q == 0 ){
				array_push( $submitted_answers, 
					array(
						'question_id' => $value['question_id'],
						'time_saved' => $value['time_saved'],
						'answer' => array( $value['answer'] )
					)
				);
			}	
			
			echo "flag_q = ", $flag_q, "flag_a = ", $flag_a;*/
		
		//}
		
		
        //update values
        $response = $db_connector->update_answers($key, $submitted_answers);

        return $response;
    }

	function submit_all_answers ( $key, $values ) {
		/*
		 * $values : array of (question_id, answer)
		 */
		$db_connector = new db_connector();

        // retrieve old values
        $submitted_answers = $db_connector->retrieve_autosaved_answers($key['ticket_id'], $key['test_id']);
		
		$flag = 0;
				
        for ($i = 0; $i < sizeof( $submitted_answers ); $i++) {
			
			for ($j = 0; $j < sizeof( $values ); $j++) {
				
				if ($submitted_answers[$i]['question_id'] == $values[$j]['question_id'] && 
						$submitted_answers[$i]['answer'] != $values[$j]['answer']) {
	
					$submitted_answers[$i]['answer'] = $values[$j]['answer'];
					
					$flag = 1;
	
				}
			}
        }
		
		if ($flag == 1) {
			$response = $db_connector->update_answers( $key, $submitted_answers );
		} else {
			$response = true;	
		}
			
		if ($response != false) 
			$response = $db_connector->change_submit_status( $key, 1, date('Y-m-d H:i:s') );
		
		return $response;

	}
	
	function submit_all_answers_in_json ( $key, $json ) {
		$db_connector = new db_connector();

        // retrieve old values
        $submitted_answers = $db_connector->retrieve_autosaved_answers( $key['ticket_id'], $key['test_id'] );
		
		if ( json_encode( $submitted_answers ) != $json ) {
		
			echo "tjena old: ", var_dump( json_encode($submitted_answers)), "<br />";
			echo "tjena new: ", var_dump($json), "<br />";
		
			$response = $db_connector->update_answers( $key, json_decode($json) );
			
			if ($response != false) 
				$response = $db_connector->change_submit_status( $key, 1, date('Y-m-d H:i:s') );
		
		} else {
			$response = true;	
		}
		
		return $response;
	}
	
	
}