<?php
include("sql-connector.php");

class db_connectorTest extends PHPUnit_Framework_TestCase{
	private $db_connector;
	
	function testVerify_account() {
		$db_connector = new db_connector();
		
		$result = $db_connector->verify_account("username@email.com", md5("password"));
		
		$this->assertEmpty($result);
	}
	
	
}

?>