<?php

if ($_POST && 
		!empty($_POST['name']) && 
		!empty($_POST['city']) ) {
			
	echo "bonjour";
}




/*
require_once 'model/account-tester.php';
$tester = new Tester();
echo "bonjour";
if ($_POST && 
		!empty($_POST['q']) && 
		!empty($_POST['a']) && 
		!empty($_POST['t']) && 
		!empty($_POST['ticket_id'])&& 
		!empty($_POST['test_id']) ) {
			
	$question_id = $_POST['q'];
	$answer = $_POST['a'];
	$time_saved = $_POST['t'];
	$ticket_id = $_POST['ticket_id'];
	$test_id =  $_POST['test_id'];		
	
	$json_answer = json_encode( 
		array(
			"question_id" => $question_id,
			"answer" => $answer,
			"time_saved" => $time_saved
		)
	);
	
	$key = array(
		"ticket_id" => $ticket_id,
		"test_id" => $test_id
	);
	
	$result = $tester->autosave_answer($key, $json_answer);
	
	echo "<b>Saved</b>: Server has received Q: ", $_POST['q'], ", A: ", $_POST['a'], " T: ", $_POST['t'], "ticket_id: ", $_POST['ticket_id'], "test_id: ", $_POST['test_id']
	, "Result: ", $result;
}*/
?>