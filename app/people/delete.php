<?php
	require_once(dirname(__FILE__) . "/../includes/auth_check.php");
	require_once(dirname(__FILE__) . "/../../classes/Person.php");

	if(session_status() === PHP_SESSION_NONE) session_start();

	if($_SERVER['REQUEST_METHOD'] !== 'POST')
	{
		header("Location: index.php");
		exit;
	}

	$userID = trim($_POST['userID'] ?? '');
	$person = new Person();

	if($userID !== '' && $person->deletePerson($userID))
	{
		header("Location: index.php?message=" . urlencode("Deleted " . $userID));
	}
	else
	{
		header("Location: index.php?message=" .
			urlencode("Could not delete: " . ($_SESSION['ERROR'] ?? 'unknown error')));
	}
	exit;
?>
