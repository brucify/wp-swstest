<?php include("../../../../wp-load.php");
//Ensures no one loads page and does simple spam check.
if(isset($_POST['name']) && empty($_POST['spam_check']))
{
	//Include our email validator for later use 
	require 'email-validator.php';
	$email_validator = new EmailAddressValidator();
	
	require 'sql-connector.php';
	$account_validator = new AccountValidator();
	
	//Declare our $errors variable we will be using later to store any errors.
	$errors = array();
	
	//Setup our basic variables
	$input_email = strip_tags($_POST['email']);
	$input_password = strip_tags($_POST['password']);

	//if($_POST['input_to_email']!='')
	//	$input_to_email = $_POST['input_to_email'];
	//else

	$input_to_email = webmaster_email;

	//We'll check and see if any of the required fields are empty.
	//We use an array to store the required fields.
	$required = array('Email field' => 'email', 'Password field' => 'password');
	
	//Loops through each required $_POST value 
	//Checks to ensure it is not empty.
	foreach($required as $key=>$value)
	{
		if(isset($_POST[$value]) && $_POST[$value] !== '') 
		{
			continue;
		}
		else {
			$errors[] = $key . ' cannot be left blank';
		}
	}
	
	//Make sure the email is valid. 
    if (!$email_validator->check_email_address($input_email)) {
           $errors[] = 'Email address is invalid.';
    }
	
	//Make sure the password is valid. 
    if (!$account_validator->check_account($input_email, $input_password)) {
           $errors[] = 'Account/Password is invalid.';
    }
	
	//Now check to see if there are any errors 
	if(empty($errors))
	{		
		
			echo 'You\'re logged in.';
	
		
	}
	else 
	{
		
		//Errors were found, output all errors to the user.
		echo implode('<br />', $errors);
		
	}
}
/*else
{
	die('Direct access to this page is not allowed.');
}*/
