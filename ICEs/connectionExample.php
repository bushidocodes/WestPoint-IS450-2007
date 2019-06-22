<?php

	require_once("adodb\adodb.inc.php");
	
	$conn = ADONewConnection('mysqlt');
	$myDb = $conn->Connect('localhost','root','abc','faq');
	
	if(!$myDb)
	{
		die("Failed to connect");
	}
	
	print("Connection Successful");
	
	$query = "SELECT * FROM personTable";
	
	$recordSet = $conn->Execute($query);
	
	while(!$recordSet->EOF)
	{
		print("Last Name: " . $recordSet->fields['lastName']);
		
	}
?>