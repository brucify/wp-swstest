<?php

class db_connector {
    /*
     * 	customer DB connections
     */

    function verify_account($em, $pwd) {
        global $wpdb;

        $query =
                "
			SELECT id
			FROM data_customers
			WHERE email = %s AND password = %s
			";

        /* customer_id */
        $result = $wpdb->get_var($wpdb->prepare($query, $em, $pwd));

        return $result;
    }

	function insert_ticket($arg) {
        global $wpdb;
		
		$data = array(
			'customer_id' => $arg['customer_id'], // int (11)
			'candidate_name' => $arg['candidate_name'], //varchar (41) 
			'candidate_email' => $arg['candidate_email'], ///varchar (41)
			'ticket_option' => $arg['send_to_candidate'], // binary (4)
			'ticket_token' => $arg['ticket_token'] // varchar (23)
		);	
		
		$format = array(
			'%d',
			'%s',
			'%s',
			'%d',
			'%s'
		);
	
		$result_ticket_insert = $wpdb->insert( 'data_tickets', $data, $format );
		
		//echo "hejsan data = ", var_dump($data);
		//echo "hejsan result = ", var_dump($result_ticket_insert);
		
		if ($result_ticket_insert != false) {
			$ticket_id = $wpdb->insert_id;
			return $ticket_id;
		} else
			return false;
			
	}
	
	function insert_test_selections($arg) {
        global $wpdb;
		
		$flag = 0;
		
		foreach ($arg['selected_test_ids'] as $test_id) {
			
            $result = $wpdb->insert(
				'data_test_selection', array(
                'ticket_id' => $arg['ticket_id'],
                'test_id' => $test_id
            	)
            );
			
			if ($result == false)
				$flag = 1;
        }
		
		if ( $flag == 0 )
			return true;
		else 
			return false;
	}

/*    function create_ticket($customer_id, $candidate_name, $candidate_email, $send_to_candidate, $selected_tests_id, $ticket_token) {
        global $wpdb;

        //insert into data_tickets
        $result_ticket_insert = $wpdb->insert(
                'data_tickets', array(
									'customer_id' => $customer_id,
									'candidate_name' => $candidate_name,
									'candiate_email' => $candidate_email,
									'ticket_option' => $send_to_candidate,
									'ticket_token' => $ticket_token
								)
        );

        $ticket_id = $wpdb->insert_id;

        foreach ($selected_tests_id as $test_id) {
            //insert into data_test_selection
            // TODO test_id will change to multuple ***
            $result_test_selection_insert[] = $wpdb->insert(
                    'data_test_selection', array(
                'ticket_id' => $ticket_id,
                'test_id' => $test_id
                    )
            );
        }

        $result = array(
            'result_ticket_insert' => $result_ticket_insert,
            'result_test_selection_insert' => $result_test_selection_insert,
            'ticket_token' => $ticket_token
        );

		echo "hejsan = ", var_dump($result);
        // TODO error handling	***
        return $result;
    }*/

    function get_customer_info($customer_id) {
        global $wpdb;

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
        global $wpdb;

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
        global $wpdb;

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
        global $wpdb;

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
     * 	tester DB connections
     */

    function retrieve_ticket_details_by_token($ticket_token) {
        global $wpdb;

        $query =
                '
			SELECT *
			FROM data_tickets
			WHERE ticket_token = "' . $ticket_token . '"
			';


        $ticket_details = $wpdb->get_row($query, ARRAY_A);

        return $ticket_details;
    }

    function retrieve_ticket_details_by_id($ticket_id) {
        global $wpdb;

        $query =
                '
			SELECT *
			FROM data_tickets
			WHERE ticket_id = "' . $ticket_id . '"
			';


        $ticket_details = $wpdb->get_row($query, ARRAY_A);

        return $ticket_details;
    }

    function retrieve_assigned_tests($ticket_id) {
        global $wpdb;

        $query =
            "
			SELECT *
			FROM data_test_selection
			WHERE ticket_id = $ticket_id
			";

        $assigned_tests = $wpdb->get_results($query, ARRAY_A);

        return $assigned_tests;
    }

    /*
      function retrieve_assigned_test_details($test_id) {
      global $wpdb;

      $query =
      '
      SELECT *
      FROM data_tests
      WHERE test_id = "'. $test_id . '"
      ';

      $assigned_test_details = $wpdb->get_row($query, ARRAY_A);

      return $assigned_test_details;

      } */

    function retrieve_autosaved_answers($ticket_id, $test_id) {
        /*
          submitted_answer in SQL database: a JSON array

          $submitted_answer = [
          { "question_id" : "q1submit", "answer" : "A", "time_saved" : 123123123 },
          { "question_id" : "q2submit", "answer" : "A", "time_saved" : 123123123 },
          ];
         */
        global $wpdb;

        // query old values
        $query = $wpdb->prepare(
			'
			SELECT submitted_answer
			FROM data_test_selection
			WHERE ticket_id = %d AND test_id = %s
			', $ticket_id, $test_id
        );

        //echo "***query =  " , $query , "<br />";

        $submitted_answer = json_decode($wpdb->get_var($query), true);

        return $submitted_answer;
    }

	function update_time_started( $key, $time_started ) {
		
 		global $wpdb;

        // update database
        $data = array(
            "time_started" => $time_started
        );

        $where = array(
            "ticket_id" => $key["ticket_id"],
            'test_id' => $key["test_id"]
        );

        $result = $wpdb->update("data_test_selection", $data, $where, $format = null, $where_format = null);

        return $result;		
			
	}

    function update_answers ( $key, $submitted_answer ) {
       
	    global $wpdb;

        // update database
        $data = array(
            "submitted_answer" => json_encode( $submitted_answer )
        );

        $where = array(
            "ticket_id" => $key["ticket_id"],
            'test_id' => $key["test_id"]
        );

        $result = $wpdb->update("data_test_selection", $data, $where, $format = null, $where_format = null);
					echo "hejsan data " , "<br />", var_dump($data);
					echo "hejsan where " , "<br />", var_dump($where);
					echo "hejsan result " , "<br />", var_dump($result);

        return $result;
    }
	
	function change_submit_status ( $key, $status, $time_submitted ) {
		global $wpdb;

		$data = array(
			'time_submitted' => $time_submitted,
			'status' => $status
		);
		
		$where = array(
            "ticket_id" => $key["ticket_id"],
            'test_id' => $key["test_id"]
        );

        $result = $wpdb->update("data_test_selection", $data, $where, $format = null, $where_format = null);

        return $result;	
	
	}
	
	function retrieve_time_started ( $ticket_id, $test_id ) {
		global $wpdb;
		
		 $query = $wpdb->prepare(
			'
			SELECT time_started
			FROM data_test_selection
			WHERE ticket_id = %d AND test_id = %s
			', $ticket_id, $test_id
        );

        //echo "***query =  " , $query , "<br />";


        $time_started = $wpdb->get_var($query);

        return $time_started;
		
	}

    /* function autosave_answer($key, $value) {
      /*
      submitted_answer in SQL database: a JSON array

      $submitted_answer = [
      { "question_id" : "q1submit", "answer" : "A", "time_saved" : 123123123 },
      { "question_id" : "q2submit", "answer" : "A", "time_saved" : 123123123 },
      ];

      global $wpdb;

      // query old values
      $query =  $wpdb->prepare(
      '
      SELECT submitted_answer
      FROM data_test_selection
      WHERE ticket_id = %d AND test_id = %s
      ',
      $key["ticket_id"], $key["test_id"]
      );

      echo "***query =  " , $query , "<br />";


      $submitted_answer = json_decode( $wpdb->get_var( $query ) , true ); // this should be an array of arrays

      echo "***before: ", var_dump($submitted_answer), "<br />";


      $flag = 0;

      //find and replace existing values
      for ($i = 0; $i < sizeof($submitted_answer); $i++) {
      if ($submitted_answer[$i]['question_id'] == $value['question_id']) {

      $submitted_answer[$i]['answer'] = $value['answer'];
      $submitted_answer[$i]['time_saved'] = $value['time_saved'];

      echo "***answer['answer'] = ",  $submitted_answer[$i]['answer'], "<br />";
      $flag = 1;
      }
      }
      echo "***after: ", var_dump($submitted_answer), "<br />";

      // if NULL or not exist, add new value
      if ($flag == 0) {

      if (!$submitted_answer)
      $submitted_answer = array(); echo "***NEW array<br />";

      array_push($submitted_answer, $value); echo "***flag == 0 value= " , var_dump($value),"<br />" ;
      }

      // update database
      $data = array(
      "submitted_answer" => json_encode($submitted_answer)
      );

      $where = array (
      "ticket_id" => $key["ticket_id"],
      'test_id' => $key["test_id"]
      );

      $result = $wpdb->update( "data_test_selection", $data, $where, $format = null, $where_format = null );

      return $result;

      } */
}