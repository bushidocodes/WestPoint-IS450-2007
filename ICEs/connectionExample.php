<?php

	require_once(dirname(__FILE__) . '/../adodb/adodb.inc.php');

	$conn = ADONewConnection('mysqli');
	$conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$host = getenv('MYSQL_HOST') ?: 'localhost';
	$myDb = $conn->Connect($host,'root','abc','isd');

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
		$recordSet->MoveNext();
	}
?>
